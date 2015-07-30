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
            $allOrdersInCart = Order::model()->findAll('status_id=:cart_status and user_id=:user', array(':cart_status'=>Order::CART, ':user'=>Yii::app()->user->_id));
            foreach($allOrdersInCart as $orderInCart){
                $cartCount += OrderProduct::model()->find('order_id=:order', array(':order'=>$orderInCart->id))->count;
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
            if(!empty(Yii::app()->params['currentMaker'])||!empty($ids)) {
                $query = "SELECT DISTINCT p.id
                    FROM model_line as m
                    JOIN product_in_model_line as pm ON m.id=pm.model_line_id
                    JOIN product as p ON p.id=pm.product_id
                    WHERE p.liquidity = 'D' and p.image not NULL".$sql;
            }
            else{
                $query = "SELECT DISTINCT p.id
                FROM product as p
                WHERE p.liquidity = 'D' and p.image not NULL";
            }

            //$depend = new CDbCacheDependency('SELECT MAX(update_time) FROM product');
                if(!empty(Yii::app()->params['currentMaker'])){
                    $sql = ' and m.maker_id = '.Yii::app()->params['currentMaker'];
                    $query.=$sql;
                }
                if(!empty($ids)){
//                    $elements = Yii::app()->db->createCommand()
//                    ->selectDistinct('p.id')
//                    ->from('model_line m')
//                    ->join('product_in_model_line pm', 'm.id=pm.model_line_id')
//                    ->join('product p', 'p.id=pm.product_id')
//                    ->where(
//                       array('and', 
//                            'p.liquidity = "D" and p.image not NULL'.$sql,
//                             array('in', 'm.category_id', $ids)
//                       )
//                    )
//                    ->queryColumn()
//                ;
                $query.=" AND m.category_id in (";
                $ids_count=count($ids);
                for($i=0; $i < $ids_count;$i++) {
                    if($i!=0){
                        $query.=',';
                    }
                    $query.=$ids[$i];
                }
                $query.=")";
            } //else {
//                $elements = Yii::app()->db->createCommand()
//                    ->selectDistinct('p.id')
//                    ->from('model_line m')
//                    ->join('product_in_model_line pm', 'm.id=pm.model_line_id')
//                    ->join('product p', 'p.id=pm.product_id')
//                    ->where(
//                       'p.liquidity = "D" and p.image not NULL'.$sql
//                    )
//                    ->queryColumn()
//                ;
//            }
            
            $query.=";"; 
            $elements = Yii::app()->db->createCommand($query)->queryColumn();
            ////////////////////////////////////////
            
            //$dependency = new CDbCacheDependency('SELECT MAX(update_time) FROM product');
            /*$max = Product::model()->cache(1000, $dependency)->count(array(
                'condition' => 'liquidity = "D" and image not NULL', // price more 500 
            ));*/
            $max = count($elements);
            if($max>=$count){
                $random_elem=array_rand($elements,$count);
            }
            else{
                $random_elem=array_rand($elements,$max);
            }
            $random_count=count($random_elem);
            $query="SELECT * from product where id in (";
            for($i=0; $i < $random_count;$i++) {
                if($i!=0){
                    $query.=',';
                }
                $query.=$elements[$random_elem[$i]];
            }
            $query.=");";
            $result = Yii::app()->db->createCommand($query)->query();
            $sale=$result->readAll();
//            if($max > $count) {
//                if(Yii::app()->params['randomImages']) {
//                    $temp = array();
//                    for($i=0; $i < $count; ) {
//                        $offset = mt_rand(0, $max);
//                        //$productId = Product::model()->cache(1000, $dependency)->find(array(
//                        //    'condition' => 'liquidity = "D" and image not NULL', // price more 500 
//                        //    'offset' => $offset,
//                        //    'limit' => 1,
//                        //))->id;
//
//                        //$productId = Product::model()->cache(1000, $dependency)->findByAttributes(
//                        $productId = Product::model()->findByAttributes(
//                            array(
//                                'id' => $elements,
//                            ), 
//                            array(
//                                'offset' => $offset,
//                                'limit' => 1,
//                        ))->id;
//
//                        if(!in_array($productId, $temp) && !empty($productId)) {
//                           $temp[] = $productId;
//                           $i++;
//                        }
//                    }
//
//                    //$sale = Product::model()->cache(1000, $dependency)->findAllByAttributes(array('id'=>$temp));
//                    $sale = Product::model()->findAllByAttributes(array('id'=>$temp));
//                } else {
//                    $offset = mt_rand(0, $max);
//                    //$sale = Product::model()->cache(1000, $dependency)->findAllByAttributes(
//                    $sale = Product::model()->findAllByAttributes(
//                        array(
//                            'id' => $elements,
//                        ), 
//                        array(
//                            'offset' => $offset,
//                            'limit' => $count,
//                    ));
//                }
//            } else if($max > 0) {
//                /*
//                $sale = Product::model()->cache(1000, $dependency)->findAll(array(
//                    'condition' => 'liquidity = "D" and image not NULL', // price more 500
//                    'limit' => $count,
//                ));
//                */
//                
//                //$sale = Product::model()->cache(1000, $dependency)->findByAttributes(
//                $sale = Product::model()->findByAttributes(
//                    array(
//                        'id' => $elements,
//                    ), 
//                    array(
//                        'limit' => 1,
//                ));
//            }
        }
        
//        if(!is_array($sale)){
//            $temp = $sale;
//            $sale = array();
//            $sale[] = $temp->attributes;
//        }
        
        
        return $sale;
    }
}