<?php
class SaleController extends Controller
{
    public function actionIndex()
    {   
        $sql = $filial = '';
        
        if (!Yii::app()->user->isGuest && !empty(Yii::app()->user->isShop)) {
           $user = User::model()->findByPk(Yii::app()->user->_id);   
           $filial = $user->filial;
        } else if(!empty(Yii::app()->request->cookies['lbrfilial']->value)) { // guest
           $filial = Yii::app()->request->cookies['lbrfilial']->value;
        }
        
        $dependency = new CDbCacheDependency('SELECT MAX(update_time) FROM product');     
        $criteria = new CDbCriteria();
        $criteria->distinct = true;
        
        $criteria->join = 'JOIN product_in_model_line p ON p.product_id = t.id ' .
                          'JOIN model_line m ON m.id = p.model_line_id '.
                          'JOIN category c ON c.id = m.category_id '.
                          'JOIN price_in_filial pr ON pr.product_id = t.id'
        ;
        
        /*
        if(!empty(Yii::app()->session['maker'])) {
            $sql = 'and m.maker_id = '.Yii::app()->session['maker'];
        }
        if(!empty(Yii::app()->session['category'])) {
            $allCategories = Category::model()->findByPk(Yii::app()->session['category'])->children()->findAll(array('order'=>'name', 'condition'=>'published=:published', 'params'=>array(':published' => true)));
            foreach($allCategories as $cat){
                $categories[] = $cat->id;
            }
            
            $criteria->addInCondition('m.category_id', $categories);
        }
        */
        
        $criteria->addCondition('liquidity = "D" and count > 0 and image not NULL '.$sql); 
        if(!empty($filial)) {
            $criteria->addCondition('pr.filial_id = :filial'); // price more 500
            $criteria->params = array(':filial'=>$filial);
        }
        
        $data = new CActiveDataProvider(Product::model()->cache(1000, $dependency),
            array(
                'criteria' => $criteria,
                'pagination'=>array(
                   'pageSize' => 7,
                   'pageVar' => 'page',
                ),
                'sort'=>array(
                    'attributes'=>array(
                        'name'=>array(
                            'asc'=>'t.name ASC',
                            'desc'=>'t.name DESC',
                            'default'=>'asc',
                        ),
                        /*'count'=>array(
                            'asc'=>'t.count DESC',
                            'desc'=>'t.count ASC',
                        ),*/
                    ),
                    'defaultOrder'=>array(
                        'name' => CSort::SORT_ASC,
                    ),
                ),
            )
        );
        
        //echo '<pre>';
        //var_dump($data->getData());
        //exit;
        
        Yii::app()->params['meta_title'] = 'Распродажа';
        $breadcrumbs[] = 'Распродажа';
        Yii::app()->params['breadcrumbs'] = $breadcrumbs;  

        $this->render('index', array('data' => $data));
    }
    
    /*public function getPrice($price, $currencyCode)
    {
        $priceLabel = '';
        
        if(Yii::app()->params['showPrices']) {
            $currency = Currency::model()->findByPk($currencyCode);
            if($currency->exchange_rate) {
                $priceLabel = ($price*$currency->exchange_rate).' руб.';
            }
        }
        
        return $priceLabel;
    }*/
    
    public function getPrice($productId)
    {
        //echo ' = '.$productId; exit;
        $priceLabel = '';
        if(Yii::app()->params['showPrices']) {
            // logged user
            if (!Yii::app()->user->isGuest && !empty(Yii::app()->user->isShop)) {
               $user = User::model()->findByPk(Yii::app()->user->_id);   
               $filialId = $user->filial;
               $priceLabel = $this->getPriceInFilial($productId, $filialId);
            } else if(Yii::app()->user->isGuest && !empty(Yii::app()->request->cookies['lbrfilial']->value)) { //guest
               $filialId = Yii::app()->request->cookies['lbrfilial']->value;
               $priceLabel = $this->getPriceInFilial($productId, $filialId);
            }
        }
        
        return $priceLabel;
    }
    
    public function getPriceInFilial($productId, $filialId)
    {
        $priceLabel = '';
        
        $priceInFilial = PriceInFilial::model()->find('product_id = :id and filial_id = :filial', array('id'=>$productId, 'filial'=>$filialId));
        if(!empty($priceInFilial)) {
            $currency = Currency::model()->findByPk($priceInFilial->currency_code);
            if(!empty($currency)) {
                $priceLabel = ($priceInFilial->price*$currency->exchange_rate).' руб.';
            }
        }
        
        return $priceLabel;
    }
}
