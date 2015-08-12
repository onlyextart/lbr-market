<?php
Yii::import('ext.yiiext.sidebartabs.STabbedForm');
class ProductmakerController extends Controller
{
    public $sidebarContent;
    
    public function actionIndex()
    {
        //if(Yii::app()->user->checkAccess('shopReadUser'))
        //{
            $model = new ProductMaker('search');
            $model->unsetAttributes();

            if (!empty($_GET['ProductMaker']))
                $model->attributes = $_GET['ProductMaker'];

            $dataProvider = $model->search();
            $dataProvider->pagination->pageSize = 15;

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
            $model = new ProductMaker;
            $model->published = true;
            //$form = new STabbedForm('application.modules.admin.views.productmaker.form', $model);
            //$form->additionalTabs = array(
            //    'Изображение' => $this->renderPartial('_images', array('model'=>$model), true),
            //);
            
            if(!empty($_POST['ProductMaker'])) {
                $model->attributes = $_POST['ProductMaker'];
                $model->update_time= date('Y-m-d H:i:s');
                
                if($model->validate()) {
                    $image = CUploadedFile::getInstance($model,'logo');
                    if(isset($image)) {
                        //foreach($images as $image) {
                             $filePath = '/images/productmaker/'.$image->name;
                             $image->saveAs(Yii::getPathOfAlias('webroot').$filePath);
                             $model->logo = $filePath;
                         //}
                    }
                    if($model->save()) {
                        $message = 'Создан производитель запчастей "'.$model->name.'"';
                        Changes::saveChange($message);
                        Yii::app()->user->setFlash('message', $message);
                        $this->redirect(array('edit', 'id'=>$model->id));
                    } else {
                        $errors = $model->getErrors();
                        Yii::log($errors, 'error');
                        //Yii::app()->user->setFlash('error', $errors);
                        Yii::app()->user->setFlash('error', "Ошибка при сохранении");
                        $this->render('edit', array(
                            'model'=>$model,
                        ));
                    }
                } else {
                    $this->render('edit', array('model'=>$model), false, true);
                }
            } else $this->render('edit', array('model'=>$model), false, true);
            
        //}else {
            //throw new CHttpException(403,Yii::t('yii','У Вас недостаточно прав доступа.'));
    }
    
    public function actionEdit($id)
    {
        $model = ProductMaker::model()->findByPk($id);
        //$form = new STabbedForm('application.modules.admin.views.productmaker.form', $model);
        //$form->additionalTabs = array(
        //    'Изображение' => $this->renderPartial('_images', array('model'=>$model), true),
        //);

        if(!empty($_POST['ProductMaker'])) {
            if ($model->attributes != $_POST['ProductMaker']){
                $message.= 'Редактирование производителя запчастей "'.$model->name.'" (id='.$model->id;
                if(!empty($model->external_id)) $message .= ', external_id = "'.$model->external_id;
                $message .='"), изменены следующие поля:';
                if($model->name != $_POST['ProductMaker']['name']){
                    $i++;
                    $message.=' '.$i.') поле "'.$model->getAttributeLabel('name').'" c "'.$model->name.'" на "'.$_POST['ProductMaker']['name'].'"';
                }
                if($model->description != $_POST['ProductMaker']['description']){
                    $i++;
                    $message.=' '.$i.') поле "'.$model->getAttributeLabel('description').'"';
                }
                if($model->logo != $_POST['ProductMaker']['logo']){
                    $i++;
                    $message.=' '.$i.') поле "'.$model->getAttributeLabel('logo').'"';
                }
                if($model->published != $_POST['ProductMaker']['published']){
                    $i++;
                    $message.=' '.$i.') поле "'.$model->getAttributeLabel('published').'" c "'.Yii::app()->params['boolLabel'][$model->published].'" на "'.Yii::app()->params['boolLabel'][$_POST['ProductMaker']['published']].'"';
                }
                if($model->country != $_POST['ProductMaker']['country']){
                    $i++;
                    $message.=' '.$i.') поле "'.$model->getAttributeLabel('country').'" c "'.$model->country.'" на "'.$_POST['ProductMaker']['country'].'"';
                }
            }
            
            $imgTemp=$model->logo;
            $model->attributes = $_POST['ProductMaker'];
            $model->logo=$imgTemp;
            $model->update_time= date('Y-m-d H:i:s');
            
           if($model->validate()) {
                //$images = CUploadedFile::getInstancesByName('Images');
                $image = CUploadedFile::getInstance($model,'logo');
                if(isset($image)) {
                    //foreach($images as $image) {
                         $filePath = '/images/productmaker/'.$image->name;
                         $image->saveAs(Yii::getPathOfAlias('webroot').$filePath);
                         $model->logo = $filePath;
                     //}
               }
                if($model->save()) {
                    if(!empty($message)) Changes::saveChange($message);
                    Yii::app()->user->setFlash('message', 'Производитель сохранен успешно.');
                    $this->redirect(array('edit', 'id'=>$model->id));
                } else {
                    $errors = $model->getErrors();
                    Yii::log($errors, 'error');
                    //Yii::app()->user->setFlash('error', $errors);
                    Yii::app()->user->setFlash('error', "Ошибка при сохранении");
                    $this->render('edit', array(
                        'model'=>$model,
                    ));
                }
            } else $this->render('edit', array('model'=>$model), false, true);
        } else $this->render('edit', array('model'=>$model), false, true);
    }
    
        
    public function actionDelete($id)
    {
        if(!empty($id)){
            $page = ProductMaker::model()->findByPk($id);
            $message = 'Удален производитель запчасти "'.$page->name.'" (external_id = "'.$page->external_id.'")';
            if(!empty($page)) {
                $page->delete();
                Changes::saveChange($message);
                Yii::app()->user->setFlash('message', 'Производитель удален.');
                $this->redirect(array('index'));
            }
        }
    }
}