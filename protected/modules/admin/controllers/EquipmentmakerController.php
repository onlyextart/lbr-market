<?php
Yii::import('ext.yiiext.sidebartabs.STabbedForm');
class EquipmentmakerController extends Controller
{
    public $sidebarContent;
    
    public function actionIndex()
    {
        //if(Yii::app()->user->checkAccess('shopReadUser'))
        //{
            $model = new EquipmentMaker('search');
            $model->unsetAttributes();

            if (!empty($_GET['EquipmentMaker']))
                $model->attributes = $_GET['EquipmentMaker'];

            $dataProvider = $model->search();
            $dataProvider->pagination->pageSize = 9;

            $this->render('index', array(
                'model'=>$model,
                'data'=>$dataProvider,
            ));
        //} else {
        //    $this->render('application.modules.admin.views.default.error', array('error' => 'У Вас недостаточно прав доступа.'));
        //}
    }
    
    public function actionCreate()
    {
       // if(Yii::app()->user->checkAccess('shopCreateUser')) {
            $model = new EquipmentMaker;
            $model->published = true;
           // $form = new STabbedForm('application.modules.admin.views.equipmentmaker.form', $model);
           // $form->additionalTabs = array(
           //     'Изображение' => $this->renderPartial('_images', array('model'=>$model), true),
           // );
            
            if(!empty($_POST['EquipmentMaker'])) {
                $model->attributes = $_POST['EquipmentMaker'];
                if($model->validate()) {
                    $image = CUploadedFile::getInstance($model,'logo');
                    //$images = CUploadedFile::getInstancesByName('Images');
                    if(isset($image)) {
                        //foreach($images as $image) {
                         $filePath = '/images/equipmentmaker/'.$image->name;
                         $image->saveAs(Yii::getPathOfAlias('webroot').$filePath);
                         $model->logo = $filePath;
                        //}
                    }
                    if($model->save()) {
                        Yii::app()->user->setFlash('message', 'Производитель создан успешно.');
                        $this->redirect(array('edit', 'id'=>$model->id));
                    } else {
                        $errors = $model->getErrors();
                        Yii::log($errors, 'error');
                        //Yii::app()->user->setFlash('error', $errors);
                        Yii::app()->user->setFlash('error', 'Ошибка при сохранении');
                        $this->render('edit', array(
                            'model'=>$model,
                        ));
                    }
                } else $this->render('edit', array('model'=>$model), false, true);
            } else $this->render('edit', array('model'=>$model), false, true);
            
        //}else {
            //throw new CHttpException(403,Yii::t('yii','У Вас недостаточно прав доступа.'));
    }
    
    public function actionEdit($id)
    {
        $model = EquipmentMaker::model()->findByPk($id);
//        $form = new STabbedForm('application.modules.admin.views.equipmentmaker.form', $model);
//        $form->additionalTabs = array(
//            'Изображение' => $this->renderPartial('_images', array('model'=>$model), true),
//        );

        if(!empty($_POST['EquipmentMaker'])) {
            $imgTemp=$model->logo;
            $model->attributes = $_POST['EquipmentMaker'];
            $model->logo=$imgTemp;
            
            if($model->validate()) {
                $image = CUploadedFile::getInstance($model,'logo');
                //$images = CUploadedFile::getInstancesByName('Images');
                if(isset($image)) {
                    //foreach($images as $image) {
                         $filePath = '/images/equipmentmaker/'.$image->name;
                         $image->saveAs(Yii::getPathOfAlias('webroot').$filePath);
                         $model->logo = $filePath;
                     //}
               }
                if($model->save()) {
                    Yii::app()->user->setFlash('message', 'Производитель сохранен успешно.');
                    $this->redirect(array('edit', 'id'=>$model->id));
                } else {
                    $errors = $model->getErrors();
                    Yii::log($errors, 'error');
                    //Yii::app()->user->setFlash('error', $errors);
                    Yii::app()->user->setFlash('error', 'Ошибка при сохранении');
                    $this->render('edit', array(
                        'model'=>$model
                    ));
                }
            } else $this->render('edit', array('model'=>$model), false, true);
        } else $this->render('edit', array('model'=>$model), false, true);
    }
    
        
    public function actionDelete($id)
    {
        if(!empty($id)){
            $page = EquipmentMaker::model()->findByPk($id);
            if(!empty($page)) {
                $page->delete();
                Yii::app()->user->setFlash('message', 'Производитель удален.');
                $this->redirect(array('index'));
            }
        }
    }
}