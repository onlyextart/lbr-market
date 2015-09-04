<?php

class ChangesController extends Controller
{
    public function actionIndex($input = null)
    {
        //if(Yii::app()->user->checkAccess('readChanges'))
        //{
        
            $model = new Changes('search');
            
            $model->unsetAttributes();

            if (!empty($_GET['Changes']))
                $model->attributes = $_GET['Changes'];
                    
            $dataProvider = $model->search();
            
            $dataProvider->pagination->pageSize = 12;

            $this->render('changes', array(
                    'model'=>$model,
                    'data'=>$dataProvider,
            ));
        /*} else {
            throw new CHttpException(403,Yii::t('yii','У Вас недостаточно прав доступа.'));
        }*/
    }
    
    public function actionDetail($id)
    {
        $model = Changes::model()->findByPk($id);
        
        if (!$model)
	    $this->render('application.modules.admin.views.default.error', array('error' => 'Запись не найдена.'));
       
        
        if(Yii::app()->user->checkAccess('shopEditUser')) {
           $this->render('detail', array(
                    'model'=>$model,
            )); 
        } else {
            $this->render('application.modules.admin.views.default.error', array('error' => 'Для редактирования недостаточно прав доступа.'));
        }
    }
}
