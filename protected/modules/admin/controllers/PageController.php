<?php
Yii::import('ext.yiiext.sidebartabs.STabbedForm');
class PageController extends Controller
{
    public $sidebarContent;
    
    public function actionIndex()
    {
        $model = new Page('search');
        $model->unsetAttributes();

        if (!empty($_GET['Page']))
            $model->attributes = $_GET['Page'];

        $dataProvider = $model->search();
        $dataProvider->pagination->pageSize = 10;
        
        $this->render('page', array(
            'model'=>$model,
            'data'=>$dataProvider
        ));
    }
    
    public function actionEdit($id)
    {
        //if(Yii::app()->user->checkAccess('shReadUserStatus'))
        //{
            $model = Page::model()->findByPk($id);
            $message = '';
            $fieldsShortInfo=array('short_description','full_descripion');
            if(!empty($_POST['Page'])) {
                $editFieldsMessage=Changes::getEditMessage($model,$_POST['Page'],$fieldsShortInfo);
                if (!empty($editFieldsMessage)){
                    $message.= 'Редактирование страницы "'.$model->title.'", ';
                    $message.= $editFieldsMessage;
                }
                $model->attributes = $_POST['Page'];
                $model->date_edit = date('Y-m-d H:i:s');
                //$model->url = trim($model->url);
                if($model->save()) {
                    if(!empty($message)) Changes::saveChange($message);
                    Yii::app()->user->setFlash('message', 'Страница сохранена успешно.');
                    $this->redirect(array('edit', 'id'=>$model->id));
                } else {
                    $errors = $model->getErrors();
                    Yii::log($errors, 'error');
                    Yii::app()->user->setFlash('error', 'Ошибка');
                }
            }

            $form = new STabbedForm('application.modules.admin.views.page.pageForm', $model);
            $this->render('editPage', array(
                'model'=>$model,
		'form'=>$form,
            ));

        /*} else {
            throw new CHttpException(403,Yii::t('yii','У Вас недостаточно прав доступа.'));
        }*/
    }
    
    public function actionCreate()
    {
       // if(Yii::app()->user->checkAccess('shopCreateUser')) {
            $model = new Page;
            $form = new STabbedForm('application.modules.admin.views.page.pageForm', $model);
            
            if(!empty($_POST['Page'])) {
                $model->attributes = $_POST['Page'];
                $model->date_edit = date('Y-m-d H:i:s');
                
                if($form->validate()) {
                    if($model->save()) {
                        $message = 'Создана страница "' . $model->title . '"';
                        Changes::saveChange($message);
                        Yii::app()->user->setFlash('message', 'Страница создана успешно.');
                        $this->redirect(array('edit', 'id'=>$model->id));
                    } else {
                        $errors = $model->getErrors();
                        Yii::log($errors, 'error');
                        Yii::app()->user->setFlash('error', 'Ошибка');
                        $this->render('editPage', array('model'=>$model, 'form'=>$form));
                    }
                } else $this->render('editPage', array('model'=>$model, 'form' => $form), false, true);
            } else $this->render('editPage', array('model'=>$model, 'form' => $form), false, true);
            
        //}else {
            //throw new CHttpException(403,Yii::t('yii','У Вас недостаточно прав доступа.'));
    }
    
    public function actionDelete($id)
    {
        if(!empty($id)){
            $page = Page::model()->findByPk($id);
            $message = 'Удалена страница "' . $page->title . '"';
            if(!empty($page)) {
                $page->delete();
                Changes::saveChange($message);
                Yii::app()->user->setFlash('message', 'Страница удалена.');
                $this->redirect(array('index'));
            }
        }
    }
}

