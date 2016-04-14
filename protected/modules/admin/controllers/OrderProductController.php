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
            $orderProduct = OrderProduct::model()->with('product')->findByPk($id);
            $message = 'Из заказа с id ='.$orderProduct->order_id.' удален товар "'.$orderProduct->product->name.'"';
            if(!empty($orderProduct)) {
                $orderProduct->delete();
                Changes::saveChange($message, Changes::ITEM_ORDER);
                Yii::app()->user->setFlash('message', 'Товар удален из заказа.');
                $this->redirect(array('/admin/order/edit','id'=>$orderProduct->order_id));
            }
        }
    }
}


