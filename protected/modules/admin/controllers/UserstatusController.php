<?php

class UserstatusController extends Controller
{
    public function actionIndex()
    {
        //if(Yii::app()->user->checkAccess('shReadUserStatus'))
        //{
            $dependecy = new CDbCacheDependency('SELECT MAX(created) FROM user');
            $dataProvider = new CActiveDataProvider(UserStatus::model()->cache(1000, $dependecy, 2), array ( 
                'pagination' => array ( 
                    'pageSize' => 10, 
                ) 
            ));
            $this->render('user_status', array('data'=>$dataProvider));
        /*} else {
            throw new CHttpException(403,Yii::t('yii','У Вас недостаточно прав доступа.'));
        }*/
    }
}