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
        
        // generate CActiveDataProvider
         $model_maker = new ProductMaker('search');
         $model_maker->unsetAttributes();
         if (!empty($_GET['ProductMaker']))
            $model_maker->attributes = $_GET['ProductMaker'];
         $dataProvider = $model_maker->search();
         $dataProvider->pagination->pageSize = 15;

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
                
                $success=true;
                $connection=Yii::app()->db; 
                $transaction=$connection->beginTransaction();
                if(!$model->save()){
                    $success=false;
                }
                if(!empty($_POST['makers'])){
                    foreach($_POST['makers'] as $maker_id){
                        $sql_insert="INSERT INTO bestoffer_makers(bestoffer_id,maker_id) VALUES('".$model->id."','".$maker_id."')";
                        $rowCount=$connection->createCommand($sql_insert)->execute();
                        if ($rowCount==0){
                            $success=false;
                        }
                    }
                }
                
                if ($success) {
                    $transaction->commit();
                    $message = 'Создано спецпредложение "' . $model->name . '"';
                    Changes::saveChange($message);
                    Yii::app()->user->setFlash('message', $message);
                    $this->redirect(array('edit', 'id' => $model->id));
                } else {
                    $transaction->rollback();
                    $errors = "Ошибка при сохранении";
                    //$errors = $model->getErrors();
                    Yii::log($errors, 'error');
                    Yii::app()->user->setFlash('error', $errors);
                    $this->render('edit', array(
                        'model' => $model,
                    ));
                }
            } else
                $this->render('edit', array('model' => $model,'makers'=>$dataProvider,'model_maker'=>$model_maker), false, true);
        } else
            $this->render('edit', array('model' => $model,'makers'=>$dataProvider,'model_maker'=>$model_maker), false, true);

        //}else {
        //throw new CHttpException(403,Yii::t('yii','У Вас недостаточно прав доступа.'));
    }

    public function actionEdit($id) {
        $message = '';
        $fieldsShortInfo=array('img','description');
        $file=array('img');
        $model = BestOffer::model()->findByPk($id);

        // generate CActiveDataProvider
         $model_maker = new ProductMaker('search');
         $model_maker->unsetAttributes();
         if (!empty($_GET['ProductMaker']))
            $model_maker->attributes = $_GET['ProductMaker'];
         $dataProvider = $model_maker->search();
         $dataProvider->pagination->pageSize = 15;
         
         //selected makers
         $selected_makers=$this->getIdMakers($id);
         if(!empty($_POST['BestOffer'])) {
            $editFieldsMessage=Changes::getEditMessage($model,$_POST['BestOffer'],$fieldsShortInfo,$file);
            if (!empty($editFieldsMessage)){
                $message.= 'Редактирование спецпредложения "'.$model->name.'", ';
                $message.= $editFieldsMessage;
            }
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
                
                $success=true;
                $connection=Yii::app()->db; 
                $transaction=$connection->beginTransaction();
                
                if($_POST['makers']!=$selected_makers){
                    if(!empty($selected_makers)){
                            $sql_delete="DELETE FROM bestoffer_makers WHERE bestoffer_id=".$id.";";
                            $rowCount=$connection->createCommand($sql_delete)->execute();
                            if ($rowCount==0){
                                $success=false;
                            }
                    }
                    if(!empty($_POST['makers'])){
                            foreach($_POST['makers'] as $maker_id){
                                $sql_insert="INSERT INTO bestoffer_makers(bestoffer_id,maker_id) VALUES('".$id."','".$maker_id."')";
                                $rowCount=$connection->createCommand($sql_insert)->execute();
                                if ($rowCount==0){
                                    $success=false;
                                }
                           }
                    }
                    if(empty($message)){
                        $message.= 'Редактирование спецпредложения "'.$model->name.'", ';
                    }
                    else{
                         $message.="; ";
                    }
                    $message.="изменен список производителей";
                }
                if (!empty($_POST['BestOffer'])){
                    if(!$model->save()){
                            $success=false;
                    }
                }
                if ($success){
                    $transaction->commit();
                    if(!empty($message)) Changes::saveChange($message);
                    Yii::app()->user->setFlash('message', 'Спецпредложение сохранено успешно.');
                    $this->redirect(array('edit', 'id'=>$model->id));
                }
                else{
                    $transaction->rollback();
                    $errors = "Ошибка при сохранении";
                    Yii::log($errors, 'error');
                    Yii::app()->user->setFlash('error', $errors);
                    $this->render('edit', array(
                        'model' => $model,
                    ));
                }

            } else
                $this->render('edit', array('model' => $model, 'makers'=>$dataProvider, 'model_maker'=>$model_maker, 'selected_makers'=>$selected_makers), false, true);
        } else
            $this->render('edit', array('model' => $model, 'makers'=>$dataProvider, 'model_maker'=>$model_maker,'selected_makers'=>$selected_makers), false, true);
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
    
    private function getIdMakers($bestoffer_id){
        $sql="SELECT maker_id FROM bestoffer_makers WHERE bestoffer_id=".$bestoffer_id.";";
        $connection=Yii::app()->db;
        $command=$connection->createCommand($sql);
        $makers=$command->queryColumn();
        return $makers;
    }
    
//    private function checkMakerPublish($m_id,$b_id) {
//        $criteria=new CDbCriteria;
//        $criteria->condition='maker_id=:maker_id and bestoffer_id=:bestoffer_id';
//        $criteria->params=array(':maker_id'=>$m_id,':bestoffer_id'=>$b_id,);
//        $bestoffer_maker=BestofferMakers::model()->findAll($criteria);
//        if (!empty ($bestoffer_maker) ){
//            return true;
//        }
//        else{
//            return false;
//        }
//    }
}
