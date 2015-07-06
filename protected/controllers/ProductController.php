<?php
class ProductController extends Controller
{
    public function actionIndex($id)
    {
        $data = Product::model()->findByPk($id);
        if(!$data)
            throw new CHttpException(404, 'Товар не найден');
        
        $maker = ProductMaker::model()->findByPk($data->product_maker_id);
              
        Yii::app()->params['meta_title'] = $data->name;
        
        // bradcrumbs
        if(!empty(Yii::app()->request->urlReferrer) && !empty(Yii::app()->session['model']) && $data->liquidity != 'D') {
            //preg_match_all('~(.*)/model/show/id/(\d*)~i', Yii::app()->request->urlReferrer, $result);
            //$modellineId = (int)$result[2][0];
            
            $modelline = ModelLine::model()->findByPk(Yii::app()->session['model']);
        
            $category = Category::model()->findByPk($modelline->category_id);
            
            $categoryParent = $category->parent()->find();
            preg_match('/\d{2,}\./i', $categoryParent->name, $result);
            Yii::app()->params['currentType'] = $categoryParent->id;
            $label = trim(substr($categoryParent->name, strlen($result[0])));
            //$breadcrumbs[$label] = '/subcategory/index/id/'.$categoryParent->id;
            $breadcrumbs[$label] = '/catalog'.$categoryParent->path.'/';
            $breadcrumbs[$category->name] = '/catalog'.$category->path;
            $parent = $modelline->parent()->find();
            $breadcrumbs[$parent->name] = '/modelline/index/id/'.$parent->id;
        
            $breadcrumbs[" $modelline->name"] = Yii::app()->request->urlReferrer;            
        } else if(!empty(Yii::app()->params['searchFlag'])) {
            $url = '/search/show/';
            if(strpos(Yii::app()->request->urlReferrer, $url))
               $breadcrumbs['Поиск'] = Yii::app()->request->urlReferrer;
            else $breadcrumbs['Поиск'] = $url;
        }
        
        $breadcrumbs[] = $data->name;
        Yii::app()->params['breadcrumbs'] = $breadcrumbs;
        
        $relatedProducts = $this->showRelatedProducts($id);
        $mainProduct = $this->getMainProductInfo($id);
        $analogProducts = $this->getAnalogProducts($id);
        $drafts = $this->getDrafts($id);

        $this->render('index', array(
            'data' => $data, 
            'price' => $mainProduct[0], 
            'update' => $mainProduct[1], 
            'filial' => $mainProduct[2], 
            'maker' => $maker, 
            'relatedProducts' => $relatedProducts, 
            'analogProducts' => $analogProducts, 
            'drafts' => $drafts
        ));
    }
    
    public function showRelatedProducts($id)
    {
        $temp = Yii::app()->db->createCommand()
            ->selectDistinct('related_product_id')
            ->from('related_product')
            ->where('product_id=:id', array(':id'=>$id))
            ->queryColumn()
        ;
        
        $criteria = new CDbCriteria;
        $criteria->addInCondition('id', $temp);
        $criteria->addCondition('image IS NOT NULL');
        $relatedProducts = Product::model()->findAll($criteria);
        
        return $relatedProducts;
    }
    
    public function getMainProductInfo($productId)
    {
        $priceLabel = $update = $filial = '';
        // logged user
        if(!Yii::app()->user->isGuest && !empty(Yii::app()->user->isShop)) {
            $user = User::model()->findByPk(Yii::app()->user->_id);
            $filial = Filial::model()->findByPk($user->filial)->name;
            
            if(!empty($user->filial)) {
                $price = PriceInFilial::model()->findByAttributes(array('product_id'=>$productId, 'filial_id'=>$user->filial));
                if(!empty($price)) {
                    $currency = Currency::model()->findByPk($price->currency_code);
                    if($currency->exchange_rate) {
                        $priceLabel = ($price->price*$currency->exchange_rate).' руб.';
                    
                        $update = date('d.m.Y H:i', strtotime($currency->update_time));
                        if(!empty($price->update_time) && (strtotime($currency->update_time) < strtotime($price->update_time))) $update = date('d.m.Y H:i', strtotime($price->update_time));
                    }
                } else $priceLabel = Yii::app()->params['textNoPrice'];
            }
        } else if(!Yii::app()->user->isGuest && !empty(Yii::app()->request->cookies['lbrfilial']->value)) {
            $filialId = Yii::app()->request->cookies['lbrfilial']->value;
            $filial = Filial::model()->findByPk($filialId)->name;
            
            if(!empty($filialId)) {
                $price = PriceInFilial::model()->findByAttributes(array('product_id'=>$productId, 'filial_id'=>$filialId));
                if(!empty($price)) {
                    $currency = Currency::model()->findByPk($price->currency_code);
                    if($currency->exchange_rate) {
                        $priceLabel = ($price->price*$currency->exchange_rate).' руб.';
                    
                        $update = date('d.m.Y H:i', strtotime($currency->update_time));
                        if(!empty($price->update_time) && (strtotime($currency->update_time) < strtotime($price->update_time))) $update = date('d.m.Y H:i', strtotime($price->update_time));
                    }
                } else $priceLabel = Yii::app()->params['textNoPrice'];
            }
        }
        
        return array($priceLabel, $update, $filial);
    }
        
    public function getAnalogProducts($id)
    {
        $analogProducts = $filial = '';
        if (!Yii::app()->user->isGuest && !empty(Yii::app()->user->isShop)) {
           $user = User::model()->findByPk(Yii::app()->user->_id);   
           $filial = $user->filial;
        } else if(!Yii::app()->user->isGuest){
           $filial = Yii::app()->request->cookies['lbrfilial']->value;
        }
        
        $temp = Yii::app()->db->createCommand()
            ->selectDistinct('analog_product_id')
            ->from('analog')
            ->where('product_id=:id', array(':id'=>$id))
            ->queryColumn()
        ;
        
        $criteria = new CDbCriteria;
        $criteria->addInCondition('t.id', $temp);
        //if (!empty($filial)) $criteria->addInCondition('priceInFilial.filial_id', array($filial));
        $products = Product::model()->with('priceInFilial')->findAll($criteria);
        
        foreach ($products as $analog) {
            $countLabel = '<span class="stock">'.Product::NO_IN_STOCK.'</span>';
            if($analog->count > 0) {
                $countLabel = '<span class="stock in-stock">'.Product::IN_STOCK_SHORT.'</span>';
            }
        
            $image = '/images/no-photo.png';
            if(!empty($analog->image)) $image = 'http://api.lbr.ru/images/shop/spareparts/'.$analog->image;
            
            $drafts = $this->getDraftsLabel($analog->id);

            $analogProducts .= '<li>'.
                                    '<div class="spareparts-wrapper">'.
                                        '<div class="row">'.
                                             '<div class="cell width-20">'.
                                                 '<a target="_blank" class="prodInfo" href="'.$analog->path.'">'.$analog->name.'</a>'.
                                             '</div>'.
                                             '<div class="cell cell-img">'.
                                                 '<a href="'.$image.'" class="thumbnail" target="_blank">'.
                                                     '<img src="'.$image.'" alt="'.$analog->name.'"/>'.
                                                 '</a>'.
                                             '</div>'.
                                             '<div class="cell draft width-35">'.
                                                $drafts.
                                             '</div>'
             ;
             
             if(!Yii::app()->user->isGuest) {
                $price = '';
                if(Yii::app()->params['showPrices'] || (empty(Yii::app()->user->isShop) && Yii::app()->params['showPricesForAdmin'])) {
                    $price = Price::model()->getPrice($analog->id);
                    if(empty($price)) $price = Yii::app()->params['textNoPrice'];
                } else $price = Yii::app()->params['textHidePrice'];
                
                $analogProducts .= '<div class="cell width-15">'.$price.'</div>';
             } else {
                $analogProducts .= '<div class="cell width-15 price_link">'.
                   '<a href="/site/login/">Узнать цену</a>'.
                '</div>';
             }
             
             $analogProducts .=      '<div class="cell width-20">'.
                                         '<div class="cart-form" elem="<?php echo $analog->id ?>">'.
                                            $countLabel;
                                            
             if(Yii::app()->user->isGuest || (!Yii::app()->user->isGuest && !empty(Yii::app()->user->isShop))){
                $analogProducts .= '<input type="number" min="1" pattern="[0-9]*" name="quantity" value="1" maxlength="4" size="7" autocomplete="off" product="1" class="cart-quantity">'.
                    '<input type="button" title="Добавить в корзину" value="" class="small-cart-button">'.
                    '<button class="wish-small" title="Добавить в блокнот">'.
                    '<span class="wish-icon"></span>'.
                    '</button>'
                ;
             }

             $analogProducts .=           '</div>'.
                                     '</div>'.
                                 '</div>'.
                             '</div>'.
                        '</li>';
        }
        return $analogProducts;
    }
    
    public function getDraftsLabel($id)
    {
        $draftLabel = '';
        
        if(Yii::app()->params['showDrafts']) {
            $allDrafts = ProductInDraft::model()->findAllByAttributes(array('product_id'=>$id));
            if(!empty($allDrafts)) {
                foreach($allDrafts as $one) {
                    $draft = Draft::model()->findByPk($one['draft_id']);
                    $draftLabel .= '<a target="_blank" href="/draft/index/id/'.$draft->id.'/">Чертеж "'.$draft->name.'"</a>';
                }
            }
        }
        
        return $draftLabel;
    }
    
    public function getDrafts($id)
    {
        $drafts = array();
        
        if(Yii::app()->params['showDrafts']) {
            $allDrafts = ProductInDraft::model()->findAllByAttributes(array('product_id'=>$id));
            $allDrafts = ProductInDraft::model()->findAllByAttributes(array('product_id'=>$id));
            if(!empty($allDrafts)) {
                foreach($allDrafts as $one) {
                    $draft = Draft::model()->findByPk($one['draft_id']);
                    $drafts[$draft->id]['id'] = $draft->id;
                    $drafts[$draft->id]['name'] = $draft->name;
                    $drafts[$draft->id]['image'] = $draft->image;
                }
            }
        }
        
        return $drafts;
    }
}
