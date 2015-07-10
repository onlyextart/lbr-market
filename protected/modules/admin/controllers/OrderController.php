<?php

class OrderController extends Controller
{
    public function actionIndex()
    {
       // if(Yii::app()->user->checkAccess('shopReadOrder'))
       // {
            $model = new Order('search');
            
            $model->unsetAttributes();

            if (!empty($_GET['Order']))
                $model->attributes = $_GET['Order'];
                    
            $dataProvider = $model->search();
            
            $dataProvider->pagination->pageSize = 10;
            $dataProvider->sort->defaultOrder = 'status_id, date_created desc';
            
            $this->render('order', array(
                    'model'=>$model,
                    'data'=>$dataProvider,
            ));
       // } else {
      //      $this->render('application.modules.admin.views.default.error', array('error' => 'У Вас недостаточно прав доступа.'));
      //  }
    }
    
    public function actionEdit($id)
    {
        $model = Order::model()->with('status','orderProducts','user')->findByPk($id);
        $form=new OrderForm;
        $form->attributes=$model->attributes;
        if (!empty($model->user_id)){
            $form->user_name=$model->user->name;
            $form->user_email=$model->user->email;
            $form->user_phone=$model->user->phone;
        }
        
        $criteria=new CDbCriteria;
        $criteria->condition='order_id=:order_id';
        $criteria->params=array(':order_id'=>$id);
        $model_product=OrderProduct::model()->with('product')->findAll($criteria);

        if(!empty($model_product)){
            foreach ($model_product as $key => $one_product) {
              $form_product[$key]=new OrderProductForm;
              $form_product[$key]->id=$one_product->id;
              $form_product[$key]->name=$one_product->product->name;
              $form_product[$key]->total_price=$one_product->total_price;
              $form_product[$key]->price=$one_product->price;
              $form_product[$key]->count=$one_product->count;
              $form_product[$key]->catalog_number=$one_product->product->catalog_number;
              $form_product[$key]->currency=$one_product->currency;
              $form_product[$key]->currency_symbol=Currency::model()->findByPk($one_product->currency_code)->symbol;
            }
             
        }
        
//        if(Yii::app()->user->checkAccess('shopEditOrder')) {
            if (!empty($_POST['OrderForm'])&&!empty($_POST['OrderProductForm'])) {
                $valid=true;
                $model->attributes=$_POST['OrderForm'];
                $model->date_updated = date('Y-m-d H:i:s');
                $valid=$model->validate()&&$valid;
                
                foreach($model_product as $i=>$item)
                {
                    if(isset($_POST['OrderProductForm'][$i])){
                        $model_product[$i]->attributes=$_POST['OrderProductForm'][$i];
                    }
                }
                
               $transaction=$model->dbConnection->beginTransaction();
               try{
                 $save=true;
                 foreach ($model_product as $i=>$item){
                    if (!$item->save()){
                        $save=false;
                        $errors_product = $item->getErrors();
                        break;
                    }
                 }
                       
                 if(!$model->save()) {
                    $save=false;
                    $errors_order=$model->getErrors();
                 }
                       

                 if($save){
                    $transaction->commit();
                    Yii::app()->user->setFlash('message', 'Заказ сохранен успешно.');
                    $this->redirect(array('edit', 'id'=>$model->id)); 
                 }
                 else{
                   $transaction->rollback();
                   empty($errors_product)? $errors=$errors_order:$errors=$errors_product['count']['0'];
                   Yii::app()->user->setFlash('error', $errors);
                   Yii::log($errors, 'error');
                   $this->render('editOrder', array('model'=>$model, 'form' => $form,'model_product'=>$model_product,'form_product'=>$form_product), false, true);
                 }
                       
                }
                
                catch(Exception $e)
                {
                    $transaction->rollback();
                    throw $e;
                }

           } else 
              $this->render('editOrder', array('model'=>$model,'form'=>$form,'model_product'=>$model_product,'form_product'=>$form_product), false, true);
            

    }
    
    
    public function actionDelete($id)
    {
        if(!empty($id)){
            $order = Order::model()->findByPk($id);
            if(!empty($order)) {
                $order->delete();
                Yii::app()->user->setFlash('message', 'Заказ удален.');
                $this->redirect(array('/admin/order/'));
            }
        }
    }
}
