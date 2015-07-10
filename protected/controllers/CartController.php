<?php

class CartController extends Controller
{
    public $form;
    
    public function actionIndex()
    {
        $items = $temp = array();
        $showLabelForNoPrice = false;
        $this->form = new OrderCreateForm;
        Yii::app()->params['meta_title'] = 'Корзина';
        $totalLabel = '';
        
        if(Yii::app()->user->isGuest) {
            if(Yii::app()->request->isPostRequest && Yii::app()->request->getPost('create'))
            {
                if(isset($_POST['OrderCreateForm']))
                {
                    $this->form->attributes = $_POST['OrderCreateForm'];
                    if($this->form->validate())
                    {   
                        $success = false;
                        $products = $_POST['products'];
                        if(!empty($products)) {
                            $app = Yii::app();
                            $transaction = $app->db_auth->beginTransaction();
        
                            $order = new Order;
                            $order->attributes = $this->form->attributes;
                            $order->status_id = Order::ORDER_NEW;
                            
                            if($order->save()) {
                                foreach($products as $productId => $count){
                                   $orderProduct = new OrderProduct;
                                   $orderProduct->order_id = $order->id;
                                   $orderProduct->product_id = $productId;
                                   $orderProduct->count = 1;
                                   if((int)$count > 0) $orderProduct->count = $count;
                                   
                                   if($orderProduct->save()) 
                                       $success = true;
                                   else {
                                       $transaction->rollback();
                                       $success = false;
                                   }
                                }
                                if($success){
                                    $transaction->commit();
                                    Yii::app()->session['cart'] = array();
                                    Yii::app()->user->setFlash('message', 'Спасибо. Ваш заказ принят.');
                                    Yii::app()->request->redirect($this->createUrl('view', array('secret_key'=>$order->secret_key)));
                                }
                            } else {
                                $transaction->rollback();
                                Yii::app()->user->setFlash('error', 'Произошла ошибка при сохранении заказа');
                            }
                        }
                    }
                }
            }
            
            if(!empty(Yii::app()->session['cart'])) {
                foreach(Yii::app()->session['cart'] as $productId => $count) {
                    $product = Product::model()->findByPk($productId);
                    $prodImage = '/images/no-photo.png';
                    if(!empty($product->image)) $prodImage = 'http://api.lbr.ru/images/shop/spareparts/'.$product->image;
                    $items[] = array(
                        'path' => $product->path,
                        'id' => $productId,
                        'name' => $product->name,
                        'img' => $prodImage,
                        'count' => $count,
                        'liquidity' => $product->liquidity,
                    );
                }
            }
        } else if(empty(Yii::app()->user->isShop)) {
            $this->redirect('/');
        } else { // logged user
            if(Yii::app()->request->isPostRequest) { 
                if(Yii::app()->request->getPost('create')) {
                    if(isset($_POST['OrderCreateForm'])) {
                        $this->form->attributes = $_POST['OrderCreateForm'];
                        if($this->form->validate()) {
                            $success = false;
                            $products = $_POST['products'];
                            
                            if(!empty($products)) {
                                $app = Yii::app();
                                $transaction = $app->db_auth->beginTransaction();

                                $order = new Order;
                                $order->attributes = $this->form->attributes;
                                $order->status_id = Order::ORDER_NEW;
                                $order->filial = User::model()->findByPk(Yii::app()->user->_id)->filial;
                                
                                if($order->save()) {
                                   //$productsWithoutPrice = array();
                                   foreach($products as $productId => $count) {
                                       $priceInFilial = PriceInFilial::model()->find('product_id=:product_id and filial_id=:filial_id', array(':product_id'=>$productId, ':filial_id'=>User::model()->findByPk(Yii::app()->user->_id)->filial));
                                       //if(!empty($priceInFilial)) {
                                            $orderProduct = new OrderProduct;
                                            $orderProduct->order_id = $order->id;
                                            $orderProduct->product_id = $productId;
                                            $orderProduct->count = 1;
                                            if((int)$count > 0) $orderProduct->count = $count;
                                            if(!empty($priceInFilial)) {
                                                $result = $this->getPrice($productId, $count);
                                                $orderProduct->total_price = $result['total'];
                                                $orderProduct->price = $priceInFilial->price;
                                                $orderProduct->currency = Currency::model()->findByPk($priceInFilial->currency_code)->exchange_rate;
                                                $orderProduct->currency_code = $priceInFilial->currency_code;
                                            }
                                            $orderProduct->save();
                                       /*} else {
                                           $productsWithoutPrice[$productId] = $count;
                                       }*/
                                    }

                                    /*$countProductsInOrder = OrderProduct::model()->count(
                                        'order_id=:order_id', 
                                        array(':order_id'=>$order->id)
                                    );
                                    
                                    if($countProductsInOrder) {*/
                                        $order->total_price = $this->setTotalPriceForOrder($order);
                                        $order->save();
                                        
                                        Order::model()->deleteAll(
                                                'status_id=:cart_status and user_id=:user', 
                                                array(':cart_status'=>Order::CART, ':user'=>Yii::app()->user->_id)
                                        );
                                        
                                        //$this->saveProductsWithoutPrice($productsWithoutPrice);
                                        
                                        $this->sendMail($order, $model);
                                        
                                        $transaction->commit();
                                        Yii::app()->user->setFlash('message', 'Ваш заказ принят.');
                                        Yii::app()->request->redirect($this->createUrl('view', array('secret_key'=>$order->secret_key)));
                                    /*} else {
                                        $order->delete();
                                        $transaction->rollback();
                                        Yii::app()->user->setFlash('error', 'В заказе присутствуют исключительно товары, на которые нет цены.');
                                    }*/
                                    
                                } else {
                                    $transaction->rollback();
                                    Yii::app()->user->setFlash('error', 'Произошла ошибка при сохранении заказа.');
                                }
                            }
                        }
                    }
                } else if(Yii::app()->request->getPost('recount') && Yii::app()->params['showPrices']) {
                    $success = false;
                    $products = $_POST['products'];
                    if(!empty($products)) {
                       foreach($products as $productId => $count) {
                          if((int)$count < 1) $count = 1;
                          
                          $order = Yii::app()->db->createCommand()
                             ->select('o.id order, p.id productId')
                             ->from('order o')
                             ->join('order_product p', 'o.id=p.order_id')
                             ->where('o.status_id=:cart_status and o.user_id=:user_id and p.product_id=:product_id', array(':cart_status'=>Order::CART, ':user_id'=>Yii::app()->user->_id, ':product_id'=>$productId))
                             ->queryRow()
                          ;

                          if(!empty($order)) {
                             $product = OrderProduct::model()->findByPk($order['productId']);
                             $product->count = $count;
                             $product->save();
                          }
                       }
                    }
                }
            }
            
            if(!empty(Yii::app()->session['cart'])) {
                foreach(Yii::app()->session['cart'] as $productId => $count) {
                    $order = Yii::app()->db->createCommand()
                        ->select('o.id')
                        ->from('order o')
                        ->join('order_product p', 'o.id=p.order_id')
                        ->where('o.status_id=:cart_status and o.user_id=:user_id and p.product_id=:product_id', array(':cart_status'=>Order::CART, ':user_id'=>Yii::app()->user->_id, ':product_id'=>$productId))
                        ->queryRow()
                    ;
                    
                    if(!empty($order)) {
                        $updateOrder = OrderProduct::model()->find('product_id=:product_id and order_id=:order_id', array(':product_id'=>$productId, ':order_id'=>$order[id]));
                        $updateOrder->count = (int)$updateOrder->count + (int)$count;
                        //$updateOrder->price = '';
                        $updateOrder->save();
                    } else {
                        $order = new Order;
                        $order->user_id = Yii::app()->user->_id;
                        $order->delivery_id = 1;
                        $order->status_id = Order::CART; // статус "корзина"

                        if($order->save()) {
                            $orderProduct = new OrderProduct;
                            $orderProduct->order_id = $order->id;
                            $orderProduct->product_id = $productId;
                            $orderProduct->count = $count;
                            $orderProduct->save();
                        }
                    }
                }
                Yii::app()->session['cart'] = null;
            }
            
            $allOrdersInCart = Order::model()->findAll('status_id=:cart_status and user_id=:user', array(':cart_status'=>Order::CART, ':user'=>Yii::app()->user->_id));
            
            foreach($allOrdersInCart as $orderInCart) {
                $temp[] = $orderInCart->id;
            }
            
            $criteria = new CDbCriteria();
            $criteria->addInCondition("order_id", $temp);
            $items = OrderProduct::model()->with('product', 'order')->findAll($criteria);
            
            $totalPrice = 0;
            foreach($items as $item) {
                if(is_numeric($totalPrice)) {
                    $user = User::model()->findByPk(Yii::app()->user->_id);   
                    $price = PriceInFilial::model()->findByAttributes(array('product_id'=>$item->product->id, 'filial_id'=>$user->filial));

                    if(!empty($price)) {
                        $currency = Currency::model()->findByPk($price->currency_code);
                        if($currency->exchange_rate) {
                           $totalPrice += ($price->price*$item->count*$currency->exchange_rate);
                        }
                    } else {
                        $showLabelForNoPrice = true;
                        $totalPrice = 'XXX';
                    }
                }
            }
            
            if(is_numeric($totalPrice)) 
               $totalLabel = $totalPrice.' руб.';
            else $totalLabel = 'стоимость будет указана в счет-фактуре.';
        }
        
        $deliveryMethods = Delivery::model()->findAll();
        $this->render('cart', array('items'=>$items, 'deliveryMethods'=>$deliveryMethods, 'total' => $totalLabel, 'showLabelForNoPrice'=>$showLabelForNoPrice));
    }
    
    public function sendMail($order, $model)
    {
        // send mail
        $address = 'webmaster@lbr.ru';
        $name = 'Интернет-магазин ЛБР АгроМаркет';
        $mail = new YiiMailer ('mail_cart', 
            array( 
                'name' => $order->user_name,
                'id' => $order->id,
                //'count' => $orderProduct->count,
                'email' => $order->user_email,
                'phone' => $order->user_phone))
        ;
        //устанавливаем свойства
        $mail->setFrom($address, $name);
        $mail->setSubject("Письмо с сайта ".Yii::app()->params['host'].". Создана заявка от ".$model->name);
        $mail->setTo('shop@lbr.ru');
        $mail->send();
        // end send mail
    }
    
    public function setTotalPriceForOrder($order)
    {
        $totalPrice = 0;
        $allProducts = OrderProduct::model()->findAll(
            'order_id=:order_id', 
            array(':order_id'=>$order->id)
        );
        foreach ($allProducts as $product) {
            $result = $this->getPrice($product->product_id, $product->count);
            $totalPrice += $result['total'];
        }
        return $totalPrice;
    }
    
    public function saveProductsWithoutPrice($productsWithoutPrice)
    {
        foreach($productsWithoutPrice as $product) {
            $order = new Order;
            $order->user_id = Yii::app()->user->_id;
            $order->status_id = Order::CART;

            if($order->save()) {
               foreach($productsWithoutPrice as $productId => $count) {
                    $orderProduct = new OrderProduct;
                    $orderProduct->order_id = $order->id;
                    $orderProduct->product_id = $productId;
                    $orderProduct->count = 1;
                    if((int)$count > 0) $orderProduct->count = $count;

                    $orderProduct->save();
                }
            }                 
        }
    }
    
    public function getProductPrice($id, $count)
    {
        $priceLabel = $totalPriceLabel = Yii::app()->params['textNoPrice'];
        $result = $this->getPrice($id, $count);
        if(!empty($result['one']))$priceLabel = $result['one'].' руб.';
        if(!empty($result['total']))$totalPriceLabel = $result['total'].' руб.';
        return array('one'=>$priceLabel, 'total'=>$totalPriceLabel);
    }
    
    public function getPrice($id, $count)
    {
        $priceLabel = $totalPriceLabel = '';
        
        if(Yii::app()->params['showPrices']) {
            if(!Yii::app()->user->isGuest && !empty(Yii::app()->user->isShop)) $filial = User::model()->findByPk(Yii::app()->user->_id)->filial; 
            else $filial = Yii::app()->request->cookies['lbrfilial']->value;
            
            $price = PriceInFilial::model()->findByAttributes(array('product_id'=>$id, 'filial_id'=>$filial));
            
            if(!empty($price)) {
               $currency = Currency::model()->findByPk($price->currency_code);
               if($currency->exchange_rate) {
                  $priceLabel = ($price->price*$currency->exchange_rate);
                  $totalPriceLabel = ($price->price*$count*$currency->exchange_rate);
               }
            }   
        }
        
        return array('one'=>$priceLabel, 'total'=>$totalPriceLabel);
    }
    
    public function actionView()
    {
        Yii::app()->params['meta_title'] = 'Просмотр заказа';
        $showLabelForNoPrice = false;
        
        $secret_key = Yii::app()->request->getParam('secret_key');
        $order = Order::model()->with('user', 'delivery')->find('secret_key=:secret_key', array(':secret_key'=>$secret_key));
        if(!$order)
           throw new CHttpException(404, 'Ошибка. Заказ не найден.');

        $items = OrderProduct::model()->with('product', 'order')->findAll('order_id = :id', array(':id'=>$order->id));
        ////////////////////
        $totalPrice = 0;
        $totalLabel = '<span>стоимость будет указана в счет-фактуре.</span>';
        foreach($items as $item) {
            if(is_numeric($totalPrice)) {
                $price = PriceInFilial::model()->findByAttributes(array('product_id'=>$item->product->id, 'filial_id'=>$order->filial));
                
                if(!empty($price)) {
                    $currency = Currency::model()->findByPk($price->currency_code);
                    if($currency->exchange_rate) {
                       $totalPrice += ($price->price*$item->count*$currency->exchange_rate);
                    }
                } else {
                    $showLabelForNoPrice = true;
                    $totalPrice = 'XXX';
                }
            }
        }
        
        if(is_numeric($totalPrice)) 
            $totalLabel = $totalPrice.' руб.';
        
        ///////////////////
        $this->render('view', array('items'=>$items, 'order'=>$order, 'showLabelForNoPrice'=>$showLabelForNoPrice, 'total'=>$totalLabel));
    }
    
    public function actionAdd()
    {
        $cartCount = 0;
        $maxCountInCart = Yii::app()->params['maxInCart'];
        
        if(Yii::app()->request->isAjaxRequest) {
            $productId = Yii::app()->request->getPost('id');
            $count = Yii::app()->request->getPost('count');

            if(!Yii::app()->user->isGuest && Yii::app()->user->isShop) { // logged user
                $allOrdersInCart = Order::model()->findAll('status_id=:cart_status and user_id=:user', array(':cart_status'=>Order::CART, ':user'=>Yii::app()->user->_id));
                if(count($allOrdersInCart) <= $maxCountInCart) {
                    //search if this product already was added to cart
                    
                    $temp = array();
                    foreach($allOrdersInCart as $orderInCart) {
                        $temp[] = $orderInCart->id;
                    }

                    $criteria = new CDbCriteria();
                    $criteria->compare("product_id", $productId);
                    $criteria->addInCondition("order_id", $temp);
                    $orderProduct = OrderProduct::model()->find($criteria);
                    
                    if(empty($orderProduct)) {
                        $order = new Order;
                        $order->user_id = Yii::app()->user->_id;
                        $order->delivery_id = 1;
                        $order->status_id = Order::CART; // статус "корзина"
                    
                        if($order->save()) {
                            $orderProduct = new OrderProduct;
                            $orderProduct->order_id = $order->id;
                            $orderProduct->product_id = $productId;
                            $orderProduct->count = $count;

                            if($orderProduct->save()) {
                               $allOrdersInCart = Order::model()->findAll('status_id=:cart_status and user_id=:user', array(':cart_status'=>Order::CART, ':user'=>Yii::app()->user->_id));
                                
                               foreach($allOrdersInCart as $orderInCart){
                                   $cartCount += OrderProduct::model()->find('order_id=:order', array(':order'=>$orderInCart->id))->count;
                               }
                               $array = array('message'=>"Товар добавлен в корзину. <br><a href='/cart/' style='color: orange'>Перейти к оформлению товара</a>", 'count'=>$cartCount);
                            } else {             
                               Order::model()->deleteAll('id = :id', array(':id'=>$order->id));
                               $array = array('message'=>'Произошла ошибка');
                               //print_r($orderProduct->getErrors());
                            }
                        } else $array = array('message'=>'Произошла ошибка');
                    } else {
                        $orderProduct->count += $count;
                        
                        if($orderProduct->save()) {
                            $allOrdersInCart = Order::model()->findAll('status_id=:cart_status and user_id=:user', array(':cart_status'=>Order::CART, ':user'=>Yii::app()->user->_id));
                            $cartCount = 0;
                            foreach($allOrdersInCart as $orderInCart){
                                $cartCount += OrderProduct::model()->find('order_id=:order', array(':order'=>$orderInCart->id))->count;
                            }
                            $array = array('message'=>"Товар добавлен в корзину. <br><a href='/cart/' style='color: orange'>Перейти к оформлению товара</a>", 'count'=>$cartCount);
                         } else {             
                            Order::model()->deleteAll('id = :id', array(':id'=>$order->id));
                            $array = array('message'=>'Произошла ошибка');
                            //print_r($orderProduct->getErrors());
                         }
                        
                    }
                } else $array = array('message'=>'В корзине не может быть более '.$maxCountInCart.' товаров.');
                
                echo json_encode($array);
            } else { // Guest
                $count = Yii::app()->session['cart'][$productId] + $count;
                $newCartElements = array($productId => $count);
                if(empty(Yii::app()->session['cart'])) Yii::app()->session['cart'] = array();
                Yii::app()->session['cart'] = $newCartElements + Yii::app()->session['cart'];
                foreach(Yii::app()->session['cart'] as $item) {
                    $cartCount += $item;
                }
                $array = array('message'=>"Товар добавлен в корзину. <br><a href='/cart/' style='color: orange'>Перейти к оформлению товара</a>", 'count'=>$cartCount);
                echo json_encode($array);   
            }
        }
    }
    
    /*public function actionRemove($id)
    {
        if(!Yii::app()->user->isGuest && Yii::app()->user->isShop) { // logged user
           $order = Order::model()->find('status_id=:cart_status and user_id=:user and id=:id', array(':cart_status'=>Order::CART, ':user'=>Yii::app()->user->_id, ':id'=>$id)); 
           if(!empty($order)) $order->delete();
        }
        
        $this->redirect('/cart/');
    }*/
    
    public function actionRemove($path)
    {
        if(!Yii::app()->user->isGuest && Yii::app()->user->isShop) { // logged user
           $order = Yii::app()->db->createCommand()
                ->select('o.id order, p.id id')
                ->from('order o')
                ->join('order_product op', 'o.id=op.order_id')
                ->join('product p', 'p.id=op.product_id')
                ->where('status_id=:cart_status and user_id=:user and p.path=:path', array(':cart_status'=>Order::CART, ':user'=>Yii::app()->user->_id, ':path'=>'/'.$path.'/'))
                ->queryRow()
           ;
           
           if(!empty($order)) {
               $curOrder = Order::model()->findByPk($order[order]);
               if(!empty($curOrder)) {
                   $countProducts = OrderProduct::model()->count('order_id=:order_id', array(':order_id'=>$curOrder->id));
                   if($countProducts == 1) {
                       $curOrder->delete();
                   } else {
                       OrderProduct::model()->deleteAll('order_id=:order_id and product_id=:product_id', array(':order_id'=>$curOrder->id, ':product_id'=>$order[id]));
                   }
               }
           }
        }
        
        $this->redirect('/cart/');
    }
    
    public function actionGuestremove($path)
    {
        $temp = array();
        if(Yii::app()->user->isGuest) {
            $id = Product::model()->find(
                'path=:path',
                array(':path'=>'/'.$path.'/')
            )->id;
            
            if(!empty($id)){
                $temp = Yii::app()->session['cart'];
                unset($temp[$id]);
                unset(Yii::app()->session['cart']);
                Yii::app()->session['cart'] = $temp;
            }
        }
        $this->redirect('/cart/');
    }
    
    public function actionCount($path)
    {   
        $count = 0;
        if(!Yii::app()->user->isGuest && Yii::app()->user->isShop) { // logged user
           /*$order = Yii::app()->db->createCommand()
                ->select('o.id')
                ->from('order o')
                ->join('order_product op', 'o.id=op.order_id')
                ->join('product p', 'p.id=op.product_id')
                ->where('status_id=:cart_status and user_id=:user and p.path=:path', array(':cart_status'=>Order::CART, ':user'=>Yii::app()->user->_id, ':path'=>'/'.$path.'/'))
                ->queryRow()
           ;
           if(!empty($order)) $curOrder = Order::model()->findByPk($order[id]);
           if(!empty($curOrder)) $curOrder->delete();*/
           //$allOrdersInCart = Order::model()->findAll('status_id=:cart_status and user_id=:user', array(':cart_status'=>Order::CART, ':user'=>Yii::app()->user->_id));
           $orders = Yii::app()->db->createCommand()
                ->select('op.count')
                ->from('order o')
                ->join('order_product op', 'o.id=op.order_id')
                ->join('product p', 'p.id=op.product_id')
                ->where('status_id=:cart_status and user_id=:user', array(':cart_status'=>Order::CART, ':user'=>Yii::app()->user->_id))
                ->queryAll()
           ;
           
           foreach($order as $order){
               $count += $order[count];
           }
        }
        
        echo $count;
    }
}

