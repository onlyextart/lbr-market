<?php
class WishlistController extends Controller
{        
    public function actionAdd()
    {
        if(Yii::app()->user->isGuest || empty(Yii::app()->user->isShop)) {
            echo json_encode(array('redirect'=>$this->createUrl('/site/login')));
        } else {
            if(Yii::app()->request->isAjaxRequest) { 
               $userId = Yii::app()->user->_id;
               $productId = Yii::app()->request->getPost('id');
               $exists = Wishlist::model()->find('product_id = :id and user_id = :user', array(':id'=>$productId, ':user'=>$userId));
               if(empty($exists)){
                    $model = new Wishlist;
                    $model->product_id = $productId;
                    $model->user_id = $userId;
                    $model->date_created = date("Y-m-d H:i:s");

                    if($model->save()) 
                        $array = array('message'=>'Товар добавлен в "Блокнот"');
                    else 
                        $array = array('message' => '<div class="mes-notify"><span></span><div>Произошла ошибка при добавлении товара в "Блокнот"</div></div>');	
               } else {
                    $array = array('message' => '<div class="mes-notify"><span></span><div>Этот товар уже добавлен в "Блокнот"</div></div>');	
               }
           }
           echo json_encode($array); 
        }
    }
    
    public function actionRemove($path)
    {
        if(!Yii::app()->user->isGuest && Yii::app()->user->isShop) { // logged user
           //$product = Wishlist::model()->find('product_id=:product_id and user_id=:user', array(':product_id'=>$id, ':user'=>Yii::app()->user->_id)); 
           //if(!empty($product)) $product->delete();
           $productId = Product::model()->find(
                'path=:path',
                array(':path'=>'/'.$path.'/')
           )->id;
           
           $product = Wishlist::model()->find('product_id=:product_id and user_id=:user', array(':product_id'=>$productId, ':user'=>Yii::app()->user->_id));
           if(!empty($product)) $product->delete();
        }
        
        $this->redirect('/user/wishlist/show/');
    }
}