<?php
class UserAccount extends CWidget
{
    public function init()
    {
        $model = new LoginForm;
        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
        {
                echo CActiveForm::validate($model);
                Yii::app()->end();
        }
        // collect user input data
        if(isset($_POST['LoginForm']))
        {
            $returnUrl = Yii::app()->request->requestUri;
            $model->attributes=$_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->login())
               $this->controller->redirect($returnUrl);
        }
        
        $sale = $this->getSaleProducts();
        $cartCount = $this->getCartCount();
        
        $this->render('index',array('model'=>$model, 'sale'=>$sale, 'cartCount'=>$cartCount));
    }
    
    private function getCartCount()
    {
        $cartCount = 0;
        if(!Yii::app()->user->isGuest && !empty(Yii::app()->user->isShop)) 
        {            
            if (!empty(Yii::app()->session['cart'])) {
                foreach (Yii::app()->session['cart'] as $productId => $count) {
                    $originalId = ''; // for analog products
                    if (strpos($productId, '-') !== false) {
                        $pos = strpos($productId, '-');
                        $originalId = substr($productId, $pos+1);
                        $productId = substr($productId, 0, $pos);
                    }
                    
                    $order = Yii::app()->db->createCommand()
                        ->select('o.id')
                        ->from('order o')
                        ->join('order_product p', 'o.id=p.order_id')
                        ->where('o.status_id=:cart_status and o.user_id=:user_id and p.product_id=:product_id', array(':cart_status' => Order::CART, ':user_id' => Yii::app()->user->_id, ':product_id' => $productId))
                        ->queryRow()
                    ;

                    if (!empty($order)) {
                        $updateOrder = OrderProduct::model()->find('product_id=:product_id and order_id=:order_id', array(':product_id' => $productId, ':order_id' => $order['id']));
                        $updateOrder->count = (int) $updateOrder->count + (int) $count;
                        //$updateOrder->price = '';
                        $updateOrder->save();
                    } else {
                        $order = new Order;
                        $order->user_id = Yii::app()->user->_id;
                        $order->delivery_id = 1;
                        $order->status_id = Order::CART; // статус "корзина"

                        if ($order->save()) {
                            $orderProduct = new OrderProduct;
                            $orderProduct->order_id = $order->id;
                            $orderProduct->product_id = $productId;
                            $orderProduct->count = $count;
                            if(!empty($originalId)) $orderProduct->original_product_id = $originalId;
                            $orderProduct->save();
                        }
                    }
                }
                Yii::app()->session['cart'] = null;
            }
            
            $allOrdersInCart = Order::model()->findAll('status_id=:cart_status and user_id=:user', array(':cart_status'=>Order::CART, ':user'=>Yii::app()->user->_id));
            foreach($allOrdersInCart as $orderInCart) {
                $orderModel = OrderProduct::model()->find('order_id=:order', array(':order'=>$orderInCart->id));
                $cartCount += $orderModel->count;
                //$cartCount = $orderModel->id;
            }
        } else if(Yii::app()->user->isGuest) {
            if(!empty(Yii::app()->session['cart'])) {
                foreach(Yii::app()->session['cart'] as $productId => $count){
                   $cartCount += $count;
                }
            }
        }
        
        $cartLabel = ' товаров';
        if($cartCount == 1) {
            $cartLabel = ' товар';
        } else if($cartCount == 2 || $cartCount == 3 || $cartCount == 4){
            $cartLabel = ' товарa';
        }

        $cartCount .= $cartLabel;
        
        return $cartCount;
    }
    
    private function getSaleProducts()
    {
        $sql = '';
        $sale = $temp = array();
        $count = 12;
        $category_dependency = new CDbCacheDependency('SELECT MAX(update_time) FROM category');
        if(Yii::app()->controller->id != 'sale') {
            ////////////////////////////////////////
            $sql = '';
            $ids = $elements = array();

            if(!empty(Yii::app()->params['currentSale'])) {
                $ids[] = Yii::app()->params['currentSale'];
                $category = Category::model()->cache(1000, $category_dependency)->findByPk(Yii::app()->params['currentSale']);
                $descendants = $category->children()->findAll();
                foreach($descendants as $descendant){
                    $ids[] = $descendant->id;
                }
            }
            
//            if(!empty(Yii::app()->params['currentMaker'])) {
//                $sql = ' and m.maker_id = '.Yii::app()->params['currentMaker'];
//            }
            $flag=true;
            if(!empty(Yii::app()->params['currentMaker'])||!empty($ids)) {
                $query = "SELECT DISTINCT p.id
                    FROM model_line as m
                    JOIN product_in_model_line as pm ON m.id=pm.model_line_id
                    JOIN product as p ON p.id=pm.product_id
                    WHERE p.liquidity = 'D' and p.image not NULL and p.published=".$flag.$sql;
            }
            else{
                $query = "SELECT DISTINCT p.id
                FROM product as p
                WHERE p.liquidity = 'D' and p.image not NULL and p.published=".$flag;
            }

            //$depend = new CDbCacheDependency('SELECT MAX(update_time) FROM product');
                if(!empty(Yii::app()->params['currentMaker'])){
                    $sql = ' and m.maker_id = '.Yii::app()->params['currentMaker'];
                    $query.=$sql;
                }
                if(!empty($ids)){
                $query.=" AND m.category_id in (";
                $ids_count=count($ids);
                for($i=0; $i < $ids_count;$i++) {
                    if($i!=0){
                        $query.=',';
                    }
                    $query.=$ids[$i];
                }
                $query.=")";
            } 
            $query.=";"; 
            $elements = Yii::app()->db->createCommand($query)->queryColumn();
            ////////////////////////////////////////
            
            //$dependency = new CDbCacheDependency('SELECT MAX(update_time) FROM product');
            
            $max = count($elements);
            if ($max > 0) {
                if ($max >= $count) {
                    $random_elem = array_rand($elements, $count);
                } else {
                    $random_elem = array_rand($elements, $max);
                }
                $random_count = count($random_elem);
                $query = "SELECT * from product where id in (";
                for ($i = 0; $i < $random_count; $i++) {
                    if ($i != 0) {
                        $query.=',';
                    }
                    $query.=$elements[$random_elem[$i]];
                }
                $query.=");";
                $result = Yii::app()->db->createCommand($query)->query();
                $sale = $result->readAll();
            }

        }
        

        
        
        return $sale;
    }
}
