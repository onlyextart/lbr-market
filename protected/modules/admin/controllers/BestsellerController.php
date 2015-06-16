<?php
Yii::import('ext.yiiext.sidebartabs.STabbedForm');
class BestsellerController extends Controller
{
    public $sidebarContent;
    
    public function actionIndex()
    {
        //if(Yii::app()->user->checkAccess('shopReadUser'))
        //{
            $model = new Bestseller('search');
            $model->unsetAttributes();

            if (!empty($_GET['Bestseller']))
                $model->attributes = $_GET['Bestseller'];

            $dataProvider = $model->search();
            $dataProvider->pagination->pageSize = 10;

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
            $model = new Bestseller;
            
            if(!empty($_POST['Bestseller'])) {
               $model->attributes = $_POST['Bestseller'];
               
               if($model->validate()) {
                   $image=CUploadedFile::getInstance($model,'img');
                    if (isset($image)){
                        $filePath = '/images/testSale/'.$image->name;
                        $image->saveAs(Yii::getPathOfAlias('webroot').$filePath);
                        $model->img = $filePath;
                    } 
                    if($model->save()) {
                        Yii::app()->user->setFlash('message', 'Хит продаж создан успешно.');
                        $this->redirect(array('edit', 'id'=>$model->id));
                    } else {
                        $errors="Ошибка при сохранении";
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
        $model = Bestseller::model()->findByPk($id);

        if(!empty($_POST['Bestseller'])) {
            $model->attributes = $_POST['Bestseller'];
            
            if($model->validate()) {
                $image=CUploadedFile::getInstance($model,'img');
                if (isset($image)){
                        $filePath = '/images/testSale/'.$image->name;
                        $image->saveAs(Yii::getPathOfAlias('webroot').$filePath);
                        $model->img = $filePath;
                }
                if($model->save()) {
                    Yii::app()->user->setFlash('message', 'Хит продаж сохранен успешно.');
                    $this->redirect(array('edit', 'id'=>$model->id));
                } else {
                    //$errors = $model->getErrors();
                    $errors="Ошибка при сохранении";
                    Yii::log($errors, 'error');
                    Yii::app()->user->setFlash('error', $errors);
                    $this->render('edit', array(
                        'model'=>$model,
                        'form'=>$form,
                    ));
                }
            } else $this->render('edit', array('model'=>$model), false, true);
        } else $this->render('edit', array('model'=>$model), false, true);
    }
    
        
    public function actionDelete($id)
    {
        if(!empty($id)){
            $page = Bestseller::model()->findByPk($id);
            if(!empty($page)) {
                $page->delete();
                Yii::app()->user->setFlash('message', 'Хит продаж удален.');
                $this->redirect(array('index'));
            }
        }
    }
}

