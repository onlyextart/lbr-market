<?php

Yii::import('ext.yiiext.sidebartabs.STabbedForm');

class BestofferController extends Controller {

    public $sidebarContent;

    public function actionIndex() {
        //if(Yii::app()->user->checkAccess('shopReadUser'))
        //{
        $model = new BestOffer('search');
        $model->unsetAttributes();

        if (!empty($_GET['BestOffer']))
            $model->attributes = $_GET['BestOffer'];

        $dataProvider = $model->search();
        $dataProvider->pagination->pageSize = 10;
        $dataProvider->sort->defaultOrder = 'IFNULL(level, 1000000000)';

        $this->render('index', array(
            'model' => $model,
            'data' => $dataProvider,
        ));
        //} else {
        //    $this->render('application.modules.admin.views.default.error', array('error' => 'У Вас недостаточно прав доступа.'));
        //}
    }

    public function actionCreate() {
        // if(Yii::app()->user->checkAccess('shopCreateUser')) {
        $model = new BestOffer;
        $model->published = true;
        $model->level = 1;

        if (!empty($_POST['BestOffer'])) {
            $model->attributes = $_POST['BestOffer'];
            if (empty($model->level))
                $model->level = 1;
            if ($model->validate()) {
                $image = CUploadedFile::getInstance($model, 'img');
                if(isset($image)) {
                    $uploadedImage = ImageController::saveImage($image, '/images/bestoffer/');
                    if(!empty($uploadedImage)) $model->img = $uploadedImage;
                }
                
                if ($model->save()) {
                    $message = 'Создано спецпредложение "' . $model->name . '"';
                    Changes::saveChange($message);
                    Yii::app()->user->setFlash('message', $message);
                    $this->redirect(array('edit', 'id' => $model->id));
                } else {
                    $errors = "Ошибка при сохранении";
                    //$errors = $model->getErrors();
                    Yii::log($errors, 'error');
                    Yii::app()->user->setFlash('error', $errors);
                    $this->render('edit', array(
                        'model' => $model,
                    ));
                }
            } else
                $this->render('edit', array('model' => $model), false, true);
        } else
            $this->render('edit', array('model' => $model), false, true);

        //}else {
        //throw new CHttpException(403,Yii::t('yii','У Вас недостаточно прав доступа.'));
    }

    public function actionEdit($id) {
        $message = '';
        $i = 0; //номер изменения
        $model = BestOffer::model()->findByPk($id);
        if(!empty($_POST['BestOffer'])) {
//            if ($model->attributes != $_POST['BestOffer']){
//                $message.= 'Редактирование спецпредложения "'.$model->name.'", изменены следующие поля:';
//                if($model->name != $_POST['BestOffer']['name']){
//                    $i++;
//                    $message.=' '.$i.') поле "'.$model->getAttributeLabel('name').'" c "'.$model->name.'" на "'.$_POST['BestOffer']['name'].'"';
//                }
//                if($model->img != $_POST['BestOffer']['img']){
//                    $i++;
//                    $message.=' '.$i.') поле "'.$model->getAttributeLabel('img').'"';
//                }
//                if($model->published != $_POST['BestOffer']['published']){
//                    $i++;
//                    $message.=' '.$i.') поле "'.$model->getAttributeLabel('published').'" c "'.$model->published.'" на "'.$_POST['BestOffer']['published'].'"';
//                }
//                if($model->level != $_POST['BestOffer']['level']){
//                    $i++;
//                    $message.=' '.$i.') поле "'.$model->getAttributeLabel('level').'" c "'.$model->level.'" на "'.$_POST['BestOffer']['level'].'"';
//                }
//                if($model->description != $_POST['BestOffer']['description']){
//                    $i++;
//                    $message.=' '.$i.') поле "'.$model->getAttributeLabel('description').'"';
//                }
//            }
            $imgTemp = $model->img;
            $model->attributes = $_POST['BestOffer'];
            $model->img = $imgTemp;
            if(empty($model->level)) $model->level = 1;
            if($model->validate()) {
                $image = CUploadedFile::getInstance($model, 'img');
                if(isset($image)) {
                    $uploadedImage = ImageController::saveImage($image, '/images/bestoffer/');
                    if(!empty($uploadedImage)) $model->img = $uploadedImage;
                }
                if($model->save()) {
                    //if(!empty($message)) Changes::saveChange($message);
                    Yii::app()->user->setFlash('message', 'Спецпредложение сохранено успешно.');
                    $this->redirect(array('edit', 'id'=>$model->id));
                } else {
                    //$errors = $model->getErrors();
                    $errors = "Ошибка при сохранении";
                    Yii::log($errors, 'error');
                    Yii::app()->user->setFlash('error', $errors);
                    $this->render('edit', array(
                        'model' => $model,
                    ));
                }
            } else
                $this->render('edit', array('model' => $model), false, true);
        } else
            $this->render('edit', array('model' => $model), false, true);
    }

    public function actionDelete($id) {
        if (!empty($id)) {
            $page = BestOffer::model()->findByPk($id);
            $message = 'Удалено спецпредложение "'.$page->name.'"';
            if(!empty($page)) {
                $page->delete();
                Changes::saveChange($message);
                Yii::app()->user->setFlash('message', $message);
                $this->redirect(array('index'));
            }
        }
    }
}
