<?php
Yii::import('ext.yiiext.sidebartabs.STabbedForm');
class OrderProductController extends Controller
{
    public function actionIndex()
    {
       
    }
   
    public function actionDelete($id)
    {
        if(!empty($id)){
            $orderProduct = OrderProduct::model()->findByPk($id);
            if(!empty($orderProduct)) {
                $orderProduct->delete();
                Yii::app()->user->setFlash('message', 'Товар удален из заказа.');
                $this->redirect(array('/admin/order/edit','id'=>$orderProduct->order_id));
            }
        }
    }
}


