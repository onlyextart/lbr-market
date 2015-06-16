<?php
class OrdersController extends Controller
{
    public function actionShow()
    {
        Yii::app()->params['meta_title'] = 'Мои заказы';
        
        $criteria = new CDbCriteria();
        $criteria->condition = 'status_id > :cart_status and user_id = :user_id';
        $criteria->params = array(':cart_status' => Order::CART, ':user_id' => Yii::app()->user->_id);
        $criteria->order = 'date_created desc';
        
        $dataProvider = new CActiveDataProvider('Order',
            array(
                'criteria' => $criteria,
                'pagination'=>array(
                   'pageSize' => 20,
                   'pageVar' => 'page',
                )
            )
        );
        
        $this->render('index', array('data'=>$dataProvider));
    }
}

