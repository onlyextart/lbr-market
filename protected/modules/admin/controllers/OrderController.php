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
        $model = Order::model()->with('status','orderProducts','user','filials')->findByPk($id);
        $message = '';
        $fieldsShortInfo = array();
        $file = array();
        // $foreignKeys=('foreign_key'=>'related_table');
        $foreignKeys=array('status_id'=>'order_status','delivery_id'=>'delivery','filial'=>'filial');
        
        $form=new OrderForm;
        $form->attributes=$model->attributes;
        if (!empty($model->user_id)){
            if(empty($model->user_name))$form->user_name=$model->user->name;
            if(empty($model->user_email))$form->user_email=$model->user->email;
            if(empty($model->user_phone))$form->user_phone=$model->user->phone;
        }
        if($model->user->organization_type==User::LEGAL_PERSON){
            $form->user_inn=$model->user->inn;
        }
        $form->order_filial=$model->filials->name;
        $criteria=new CDbCriteria;
        $criteria->condition='order_id=:order_id';
        $criteria->params=array(':order_id'=>$id);
        $model_product=OrderProduct::model()->with('product')->findAll($criteria);

        if(!empty($model_product)){
            foreach ($model_product as $key => $one_product) {
              $form_product[$key]=new OrderProductForm;
              $form_product[$key]->id=$one_product->id;
              $form_product[$key]->name=$one_product->product->name.' (Артикул = '.$one_product->product->external_id.')';
              $form_product[$key]->total_price=$one_product->total_price;
              $form_product[$key]->price=$one_product->price;
              $form_product[$key]->count=$one_product->count;
              $form_product[$key]->catalog_number=$one_product->product->catalog_number;
              $form_product[$key]->currency=$one_product->currency;
              $form_product[$key]->path=$one_product->product->path;
              $form_product[$key]->currency_symbol=Currency::model()->findByPk($one_product->currency_code)->symbol;
            }
             
        }
        
//        if(Yii::app()->user->checkAccess('shopEditOrder')) {
            if (!empty($_POST['OrderForm'])||!empty($_POST['OrderProductForm'])) {
                $valid=true;
                $editFieldsMessage=Changes::getEditMessage($model,$_POST['OrderForm'],$fieldsShortInfo,$file,$foreignKeys);
                if (!empty($editFieldsMessage)){
                    $message.= 'Редактирование заказа с id='.$model->id.', ';
                    $message.= $editFieldsMessage;
                }
                $model->attributes=$_POST['OrderForm'];
                
                // присваивание необходимо для валидации в OrderForm
                $form->attributes=$_POST['OrderForm'];
                
                $model->date_updated = date('Y-m-d H:i:s');
                $valid=$model->validate()&&$form->validate();
                
                foreach($model_product as $i=>$item)
                {
                    if(isset($_POST['OrderProductForm'][$i])){
                        if($model_product[$i]->count!=$_POST['OrderProductForm'][$i]['count']&&!is_null($_POST['OrderProductForm'][$i]['count'])){
                            if (empty($message)) $message='Редактирование заказа с id='.$model->id;
                            $message.= ', изменено количество товара "'.$model_product[$i]->product->name.'" c "'.$model_product[$i]->count.'" на "'.$_POST['OrderProductForm'][$i]['count'].'"';
                        }
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
                       
                 if(!$model->save()||!$valid) {
                    $save=false;
                    $errors_order=$model->getErrors();
                 }
                       
                 
                 if($save){
                    $transaction->commit();
                    if(!empty($message)) Changes::saveChange($message, Changes::ITEM_ORDER);
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
            $additionalInfo = '';
            
            if(!empty($order->user_name)) $additionalInfo .= 'пользователя '.$order->user_name.' ';
            if(!empty($order->user_email)) $additionalInfo .= '('.$order->user_email.')';
            
            $message = 'Удален заказ с id "'.$order->id.'" '.$additionalInfo;
            
            if(!empty($order)) {
                $order->delete();
                Changes::saveChange($message, Changes::ITEM_ORDER);
                Yii::app()->user->setFlash('message', 'Заказ удален.');
                $this->redirect(array('/admin/order/'));
            }
        }
    }
}
