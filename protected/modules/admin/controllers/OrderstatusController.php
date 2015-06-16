<?php

class OrderstatusController extends Controller
{
    public function actionIndex()
    {
        //if(Yii::app()->user->checkAccess('shReadOrderStatus'))
        //{
            $dependecy = new CDbCacheDependency('SELECT MAX(date_created) FROM order');
            $dataProvider = new CActiveDataProvider(OrderStatus::model()->cache(1000, $dependecy, 2), array ( 
                'pagination' => array ( 
                    'pageSize' => 10, 
                ) 
            ));
            $this->render('order_status', array('data'=>$dataProvider));
        /*} else {
            throw new CHttpException(403,Yii::t('yii','У Вас недостаточно прав доступа.'));
        }*/
    }
}