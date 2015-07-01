<?php
class ModelController extends Controller
{
    public $flag = false;
    public function actionShow($id, $sort = '', $order = '')
    {     
        set_time_limit(0);
        
        $output = '';
        $data = $hitProducts = array();

        $dependency = new CDbCacheDependency('SELECT MAX(update_time) FROM model_line');
        $model = ModelLine::model()->cache(1000, $dependency)->findByPk($id);
        if(!$model)
            throw new CHttpException(404, 'Модель не найдена');

        $title = $model->name;

        $criteria = new CDbCriteria;
        $criteria->select = 't.*';
        $criteria->join ='JOIN product ON product.id = t.product_id';
        $criteria->condition = 't.model_line_id=:model_line_id';
        $criteria->params = array(':model_line_id'=>$id);

        if(!empty($sort)) {
            Yii::app()->params['sortCol'] = $sort;
            Yii::app()->params['sortOrder'] = $order;
            if($sort == 'col') {
                if(Yii::app()->params['sortOrder'] == 'asc') $criteria->order = 'IFNULL(product.count, 1000000000) desc, product.name asc';
                else $criteria->order = 'IFNULL(product.count, 1000000000) asc, product.name asc';
            } else if($sort == 'name') {
                $criteria->order = 'product.name '.$order;
            } else $criteria->order = 'product.liquidity '.$order.', product.name asc';
        } else $criteria->order = 'IFNULL(product.count, 1000000000) desc, product.name asc';


        $productsInModel = ProductInModelLine::model()->findAll($criteria);
        $depend = new CDbCacheDependency('SELECT MAX(update_time) FROM product');
        foreach($productsInModel as $productInModel) {
            $product = Product::model()->cache(1000, $depend)->findByPk($productInModel['product_id']);
            if(!empty($product->product_group_id)){
                $group = ProductGroup::model()->findByPk($product->product_group_id);
                $ancestors = $group->ancestors()->findAll();

                if(!empty($ancestors)) {
                    $count = 1;
                    $groupParent = $group->parent()->find();

                    foreach($ancestors as $ancestor) {
                        $parent = $ancestor->parent()->find();
                        if(!empty($parent)) {
                            if(count($ancestors) == 2) {
                                $data['rootChildren'][$ancestor->id]['name'] = $ancestor->name;
                                $data['rootChildren'][$ancestor->id]['children'][$group->id]['name'] = $group->name;
                                $data['rootChildren'][$ancestor->id]['children'][$group->id]['products'][] = $product->id;
                            } else if(count($ancestors) == 3 && $parent->level > 1) {
                                /* show 3 levels */
                                /*
                                $data['rootChildren'][$parent->id]['name'] = $parent->name;
                                $data['rootChildren'][$parent->id]['children'][$ancestor->id]['name'] = $ancestor->name;
                                $data['rootChildren'][$parent->id]['children'][$ancestor->id]['children'][$group->id]['name'] = $group->name;
                                $data['rootChildren'][$parent->id]['children'][$ancestor->id]['children'][$group->id]['products'][] = $product->id;
                                */
                                ///////////////////////////
                                /* show 2 levels */
                                $data['rootChildren'][$parent->id]['name'] = $parent->name;
                                $data['rootChildren'][$parent->id]['children'][$ancestor->id]['name'] = $ancestor->name;
                                $data['rootChildren'][$parent->id]['children'][$ancestor->id]['products'][] = $product->id;
                            } else if(count($ancestors) == 4 && $parent->level > 2) {
                                /* show 2 levels */
                                $parent2 = $parent->parent()->find();
                                if($parent2->level > 1){
                                    $data['rootChildren'][$parent2->id]['name'] = $parent2->name;
                                    $data['rootChildren'][$parent2->id]['children'][$parent->id]['name'] = $parent->name;
                                    $data['rootChildren'][$parent2->id]['children'][$parent->id]['products'][] = $product->id;
                                }
                            }
                        }
                        $count++;
                    }
                }
            }
        }

        // random products for hit products            
        $hitProducts = $this->setHitProducts($id);

        // bradcrumbs
        Yii::app()->params['meta_title'] = $title;
        $category = Category::model()->findByPk($model->category_id);
        $categoryParent = $category->parent()->find();
        preg_match('/\d{2,}\./i', $categoryParent->name, $result);
        $label = trim(substr($categoryParent->name, strlen($result[0])));
        $breadcrumbs[$label] = '/catalog'.$categoryParent->path.'/';
        $breadcrumbs[$category->name] = '/catalog'.$category->path.'/';

        $parent = $model->parent()->find();
        $brand = EquipmentMaker::model()->findByPk($model->maker_id)->path;
        $breadcrumbs[$parent->name] = '/catalog'.$category->path.$brand.$parent->path.'/';
        $breadcrumbs[] = $title;
        Yii::app()->params['breadcrumbs'] = $breadcrumbs;  

        if(!empty($data)) $output = $this->showInnerGroups($data);
        $this->render('model', array('model' => $model, 'data' => $data, 'title' => $title, 'result'=>$output, 'hitProducts'=>$hitProducts));

    }
    
    private function setHitProducts($id)
    {
        $sql = '';
        if(!empty(Yii::app()->params['currentMaker'])) {
            $sql = ' and m.maker_id = '.Yii::app()->params['currentMaker'];
        }
        $depend = new CDbCacheDependency('SELECT MAX(update_time) FROM product');
        $elements = Yii::app()->db->createCommand()
            ->selectDistinct('p.id')
            ->from('model_line m')
            ->join('product_in_model_line pm', 'm.id=pm.model_line_id')
            ->join('product p', 'p.id=pm.product_id')
            ->where(
               array('and', 
                    'p.liquidity = "A" and p.image not NULL'.$sql,
                    'pm.model_line_id=:id'
               ), array(':id'=>$id)
            )
            ->queryColumn()
        ;

        $max = count($elements);
        $temp = array();
        $count = 4;

        if($max > $count) {
            if(Yii::app()->params['randomImages']) {
                for($i = 0; $i < $count; ) {
                    $offset = mt_rand(0, $max);                
                    $saleProduct = Product::model()->findByAttributes(
                        array(
                            'id'=>$elements,
                        ), 
                        array(
                            'offset' => $offset,
                            'limit' => 1,
                        )
                    );

                    if(!in_array($saleProduct[id], $temp) && !empty($saleProduct[id])) {
                       $temp[] = $saleProduct[id];
                       $i++;
                    }
                }
                $hitProducts = Product::model()->cache(1000, $depend)->findAllByAttributes(array('id'=>$temp));
            } else {
                $offset = mt_rand(0, $max);
                $hitProducts = Product::model()->cache(1000, $depend)->findAllByAttributes(
                    array(
                        'id' => $elements,
                    ), 
                    array(
                        'offset' => $offset,
                        'limit' => $count,
                    )
                );
            }
        } else {
            foreach($elements as $element) {
                $temp[] = $element;
            }
            $hitProducts = Product::model()->cache(1000, $depend)->findAllByAttributes(array('id'=>$temp));
        }
        
        return $hitProducts;
    }
    
    public function showInnerGroups($data)
    {
        usort($data['rootChildren'], array($this, 'sortByName'));
        $result = '<div class="left-menu-wrapper grey" style="display: none"><ul class="accordion" id="accordion-sparepart">';
        foreach($data['rootChildren'] as $child) {
            $result .= '<li><a href="#">'.$child['name'].'</a><ul>';
            
            if(!empty($child['children'])) {
                usort($child['children'], array($this, 'sortByName'));
                foreach($child['children'] as $subChild) {
                    $result .= '<li><a href="#">'.$subChild['name'].'</a><ul>';
                    if(!empty($subChild['products'])) {
                        foreach($subChild['products'] as $productId) {
                            $result .= $this->showProducts($productId, $flag);
                        }
                    } else if(!empty($subChild['children'])) {
                        usort($subChild['children'], array($this, 'sortByName'));
                        foreach($subChild['children'] as $subSubChild) {
                            $result .= '<li><a href="#">'.$subSubChild['name'].'</a><ul>';
                            if(!empty($subSubChild['products'])) {
                                foreach($subSubChild['products'] as $productId) {
                                    $result .= $this->showProducts($productId, $flag);
                                }
                            }
                            $result .= '</ul></li>';
                        }
                    }
                    $result .= '</ul></li>';
                }
            }
            if(!empty($child['products'])) {
                foreach($child['products'] as $productId) {
                    $result .= $this->showProducts($productId, $flag);
                }
            }
            $result .= '</ul></li>';
        }
        $result .= '</ul></div>';
        
        return $result;
    }
    
    /*public function getPrice($id)
    {
        $priceLabel = '';
        
        // logged user
        if(!Yii::app()->user->isGuest && !empty(Yii::app()->user->isShop) && Yii::app()->params['showPrices']) {
            $user = User::model()->findByPk(Yii::app()->user->_id);   
            $price = PriceInFilial::model()->findByAttributes(array('product_id'=>$id, 'filial_id'=>$user->filial));
            
            if(!empty($price)) {
               $currency = Currency::model()->findByPk($price->currency_code);
               if($currency->exchange_rate) {
                  $priceLabel = ($price->price*$currency->exchange_rate).' руб.';
               }
            }   
        }
        
        return $priceLabel;
    }*/
    
    public function showProducts($id, $flag)
    {   
        $depend = new CDbCacheDependency('SELECT MAX(update_time) FROM product');
        $model = Product::model()->cache(1000, $depend)->findByPk($id);
        $draftLabel = $price = '';
        if(!Yii::app()->user->isGuest && !empty(Yii::app()->params['showPrices'])) $price = Price::model()->getPrice($id);//$price = $this->getPrice($id);

        if(Yii::app()->params['showDrafts']){
            $allDrafts = ProductInDraft::model()->findAllByAttributes(array('product_id'=>$id));

            if(!empty($allDrafts)){
                foreach($allDrafts as $one) {
                    $draft = Draft::model()->findByPk($one['draft_id']);
                    $draftLabel .= '<a target="_blank" href="/draft/index/id/'.$draft->id.'">Чертеж "'.$draft->name.'"</a>';
                }
            }
        }

        $image = '/images/no-photo.png';
        if(!empty($model->image)) $image = 'http://api.lbr.ru/images/shop/spareparts/'.$model->image;
        $result = '
            <li><div class="spareparts-wrapper">
                 <div class="row">
                     <div class="cell width-20">
                         <a class="prodInfo" target="_blank" href="'.$model->path.'">'.$model->name.'</a>
                     </div>
                     <div class="cell cell-img">
                         <a href="'.$image.'" class="thumbnail" target="_blank">
                             <img src="'.$image.'" alt="'.$model->name.'"/>
                         </a>
                     </div>
                     <div class="cell draft width-35">'.$draftLabel.'</div>'
        ;
        
        if(!Yii::app()->user->isGuest) {
            $result .= '<div class="cell width-15">'.
                '<span>'.$price.'</span>'.
            '</div>';
        } else {
            $result .= '<div class="cell width-15 price-link">'.
                '<a href="/site/login/">Узнать цену</a>'.
            '</div>';
        }

        $result .= '<div class="cell width-20">
                       <div class="cart-form" elem="'.$model->id.'">'
        ;

        if($model->count > 0) {
            $result .= '<span class="stock in-stock">'.Product::IN_STOCK_SHORT.'</span>';
        } else {
            $result .= '<span class="stock">'.Product::NO_IN_STOCK.'</span>';
        }

        if(Yii::app()->user->isGuest || (!Yii::app()->user->isGuest && !empty(Yii::app()->user->isShop))) {
            if($price) {
                $result .= '<input type="number" value="1" min="1" pattern="[0-9]*" name="quantity" maxlength="4" size="7" autocomplete="off" product="1" class="cart-quantity">
                    <input type="button" title="Добавить в корзину" value="" class="small-cart-button">'
                ;
            }

            $result .= '<button class="wish-small" title="Добавить в блокнот">
                           <span class="wish-icon"></span>
                        </button>'
            ;
        }

        $result .= '</div></div>';
        $result .= '</div></div></li>';
        return $result;
    }
    
    private static function sortByName($a, $b)
    {
        return strcmp(strtolower($a["name"]), strtolower($b["name"]));
    }
        
    public function actionSort($id, $name)
    {
        /*if(Yii::app()->session['sort'] == $name) {
            if(Yii::app()->session['order'] == 'asc') Yii::app()->session['order'] = 'desc';
            else Yii::app()->session['order'] = 'asc';
        } else Yii::app()->session['order'] = 'asc';
        Yii::app()->session['sort'] = $name;
        */
        
        /*if(Yii::app()->params['sortCol'] == $name) {
            if(Yii::app()->params['sortOrder'] == 'asc') Yii::app()->params['sortOrder'] = 'desc';
            else Yii::app()->params['sortOrder'] = 'asc';
        } else Yii::app()->params['sortOrder'] = 'asc';
        Yii::app()->params['sortCol'] = $name;
        */
        $this->redirect(Yii::app()->createUrl('model/show', array('id'=>$id, 'sort'=>$name)));
    } 
}
