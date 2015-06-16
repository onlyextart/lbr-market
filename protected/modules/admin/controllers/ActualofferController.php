<?php
Yii::import('ext.yiiext.sidebartabs.STabbedForm');
class ActualofferController extends Controller
{
    public $sidebarContent;
    
    public function actionIndex()
    {
        //if(Yii::app()->user->checkAccess('shopReadUser'))
        //{
            $model = new ActualOffer('search');
            $model->unsetAttributes();

            if (!empty($_GET['ActualOffer']))
                $model->attributes = $_GET['ActualOffer'];

            $dataProvider = $model->search();
            $dataProvider->pagination->pageSize = 10;
            $dataProvider->sort->defaultOrder = 'IFNULL(level, 1000000000)';

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
            $model = new ActualOffer;
            $model->published = true;
            $model->level = 1;
            
            if(!empty($_POST['ActualOffer'])) {
                $model->attributes = $_POST['ActualOffer'];
                if(empty($model->level)) $model->level = 1;
                if($model->validate()) {
                    $image = CUploadedFile::getInstance($model,'img');
                    if (!empty($image)) {
                        $filePath = '/images/actualoffer/'.$image->name;
                        $image->saveAs(Yii::getPathOfAlias('webroot').$filePath);
                        $model->img = $filePath;
                    }

                    if($model->save()) {
                        Yii::app()->user->setFlash('message', 'Актуальное предложение создано успешно.');
                        $this->redirect(array('edit', 'id'=>$model->id));
                    } else {
                        $errors = "Ошибка при сохранении актуального предложения";
                        //$errors = $model->getErrors();
                        Yii::log($errors, 'error');
                        Yii::app()->user->setFlash('error', $errors);
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
        $model = ActualOffer::model()->findByPk($id);
        if(!empty($_POST['ActualOffer'])) {
            $imgTemp = $model->img;
            $model->attributes = $_POST['ActualOffer'];
            $model->img = $imgTemp;
            if(empty($model->level)) $model->level = 1;
            
            if($model->validate()) {
                $image=CUploadedFile::getInstance($model,'img');
                if (!empty($image)){
                    $filePath = '/images/actualoffer/'.$image->name;
                    $image->saveAs(Yii::getPathOfAlias('webroot').$filePath);
                    $model->img = $filePath;
                }
                //echo '<pre>';
                //var_dump($model->attributes);exit;
                if($model->save()) {
                    Yii::app()->user->setFlash('message', 'Актуальное предложение сохранено успешно.');
                    $this->redirect(array('edit', 'id'=>$model->id));
                } else {
                    //$errors = $model->getErrors();
                    $errors="Ошибка при сохранении";
                    Yii::log($errors, 'error');
                    Yii::app()->user->setFlash('error', $errors);
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
            $page = ActualOffer::model()->findByPk($id);
            if(!empty($page)) {
                $page->delete();
                Yii::app()->user->setFlash('message', 'Актуальное предложение удалено.');
                $this->redirect(array('index'));
            }
        }
    }
}

