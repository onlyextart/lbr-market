<?php

class ChangesController extends Controller
{
    public function actionIndex($input = null)
    {
        //if(Yii::app()->user->checkAccess('readChanges'))
        //{
            $filter = $idString = $idNumeric = array();
            $model = new Changes('search');
            $model->unsetAttributes();

            if (!empty($_GET['Changes']))
                $model->attributes = $_GET['Changes'];
                    
            $dataProvider = $model->search();
            $dataProvider->pagination->pageSize = 12;
            
            $users = Changes::model()->findAll(array(
                'select'=>'user',
                'group'=>'user',
                'distinct'=>true,
            ));
            
            foreach($users as $user){
                $id = $user['user'];
                if(is_numeric($id)) {
                    $idNumeric[] = $id;
                } else {
                    $idString[] = $id;
                }
            }
            
            if(!empty($idNumeric)) {
                $intResults = Yii::app()->db_auth->createCommand()
                    ->select('id, surname, name, secondname')
                    ->from('user')
                    ->where(array('in', 'id', $idNumeric))
                    ->queryAll()
                ;
                
                foreach($intResults as $result){
                    $filter[$result['id']] = $result['surname'].' '.$result['name'].' '.$result['secondname'];
                }
            }
            
            if(!empty($idString)) {
                $stringResults = Yii::app()->db_auth->createCommand()
                    ->select('login, surname, name, secondname')
                    ->from('user')
                    ->where(array('in', 'login', $idString))
                    ->queryAll()
                ;
                
                foreach($stringResults as $result){
                    $name = $result['surname'].' '.$result['name'].' '.$result['secondname'];
                    if(!in_array($name, $filter))
                       $filter[$result['login']] = $name;
                }
            }            

            asort($filter);
            
            $this->render('changes', array(
                    'model'=>$model,
                    'data'=>$dataProvider,
                    'filter'=>$filter,
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
