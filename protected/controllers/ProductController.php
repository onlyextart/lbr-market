<?php
class ProductController extends Controller
{
    public function actionIndex($id)
    {
        $data = Product::model()->findByPk($id);
        if(!$data || !$data->published)
            throw new CHttpException(404, 'Товар не найден');

        $image = Product::model()->getImage($data->image);
        $maker = ProductMaker::model()->findByPk($data->product_maker_id);
              
        Yii::app()->params['meta_title'] = $data->name;
        
        // bradcrumbs
        if(!empty(Yii::app()->request->urlReferrer) && !empty(Yii::app()->session['model']) && $data->liquidity != 'D') {
            //preg_match_all('~(.*)/model/show/id/(\d*)~i', Yii::app()->request->urlReferrer, $result);
            //$modellineId = (int)$result[2][0];
            
            $modelline = ModelLine::model()->findByPk(Yii::app()->session['model']);
            $brand = EquipmentMaker::model()->findByPk($modelline->maker_id);
            Yii::app()->session['model'] = null;
            $category = Category::model()->findByPk($modelline->category_id);
            
            $categoryParent = $category->parent()->find();
            Yii::app()->params['currentType'] = $categoryParent->id;
            
            //preg_match('/\d{2,}\./i', $categoryParent->name, $result);
            //$label = trim(substr($categoryParent->name, strlen($result[0])));
            
            $label = $categoryParent->name;
            //$breadcrumbs[$label] = '/subcategory/index/id/'.$categoryParent->id;
            $breadcrumbs[$label] = '/catalog'.$categoryParent->path.'/';
            $breadcrumbs[$category->name] = '/catalog'.$category->path.'/';
            $parent = $modelline->parent()->find();
            //$breadcrumbs[$parent->name] = '/modelline/index/id/'.$parent->id;
            
            $breadcrumbs[$brand->name] = '/catalog'.$category->path.$brand->path.'/';
            $breadcrumbs[$parent->name] = '/catalog'.$category->path.$brand->path.$parent->path.'/';
        
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
            'image' => $image,
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
        $criteria->addCondition('published = 1');
        $criteria->addInCondition('id', $temp);
        $criteria->addCondition('image IS NOT NULL');
        $relatedProducts = Product::model()->findAll($criteria);
        
        return $relatedProducts;
    }
    
    public function getMainProductInfo($productId)
    {
        $priceLabel = $updateTime = $filial = '';
        // logged user
        if(!Yii::app()->user->isGuest && !empty(Yii::app()->user->isShop)) {
            $filialId = User::model()->findByPk(Yii::app()->user->_id)->filial;
        } else if(!empty(Yii::app()->request->cookies['lbrfilial']->value)) {
            $filialId = Yii::app()->request->cookies['lbrfilial']->value; 
        }
        
        if(!empty($filialId)) {
            $priceInfo = Price::model()->getPriceFilalAndUpdateTime($productId, $filialId);
            $priceLabel = $priceInfo[0];
            $updateTime = $priceInfo[1];
            $filial = $priceInfo[2];
        }
        
        return array($priceLabel, $updateTime, $filial);
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
        $criteria->addCondition('t.published = 1');
        $criteria->addInCondition('t.id', $temp);
        $criteria->order = 't.count desc, t.name';
        //if (!empty($filial)) $criteria->addInCondition('priceInFilial.filial_id', array($filial));
        $products = Product::model()->with('priceInFilial')->findAll($criteria);
        
        foreach ($products as $analog) {
            $countLabel = '<div class="stock">'.Product::NO_IN_STOCK.'</div>';
            if($analog->count > 0) {
                $countLabel = '<div class="stock in-stock">'.Product::IN_STOCK_SHORT.'</div>';
            }
            
            $analogProducts .= '<li>'.
                                    '<div class="spareparts-wrapper">'.
                                        '<div class="row">'
            ;
            
            $analogProducts .= '<div class="cell width-20">'.
                //'<a target="_blank" class="prodInfo" href="'.$analog->path.'">'.$analog->external_id.'</a>'.
                $analog->external_id.
            '</div>';
            
            $analogProducts .= '<div class="cell cell-img">';
            $largeImg = Product::model()->getImage($analog->image);
            $smallImg = Product::model()->getImage($analog->image, 's');

            $analogProducts .= '<a href="'.$largeImg.'" class="thumbnail" target="_blank">'.
            //$analogProducts .= '<a href="'.$analog->path.'" class="small-img" target="_blank">'.
                                  '<img src="'.$smallImg.'" alt="'.$analog->name.'"/>'.
                               '</a>'
            ;
            
            $drafts = $this->getDraftsLabel($analog->id);
            
//            $productMaker = '';
//            if(!empty($analog->product_maker_id)) { 
//                $productMaker = '<div>'.ProductMaker::model()->findByPk($analog->product_maker_id)->name.'</div>';
//            }
//            $analogProducts .= '</div>'.
//                            '<div class="cell draft width-35">'.
//                               $productMaker.
//                               $drafts.
//                            '</div>'
//            ;
            $productCountry = '';
            if(!empty($analog->product_maker_id)) { 
                $productCountry = '<div>'.ProductMaker::model()->findByPk($analog->product_maker_id)->country.'</div>';
            }
            $analogProducts .= '</div>'.
                            '<div class="cell draft width-35">'.
                               $productCountry.
                               $drafts.
                            '</div>'
            ;
            
            if(empty($analog->date_sale_off)) { 
                if(!Yii::app()->user->isGuest || ($analog->liquidity == 'D' && $analog->count > 0)) {
                   $price = '';
                   if(Yii::app()->params['showPrices'] || (!Yii::app()->user->isGuest && empty(Yii::app()->user->isShop) && Yii::app()->params['showPricesForAdmin'])) {
                       $price = Price::model()->getPrice($analog->id);
                       if(empty($price)) $price = '<span class="no-price-label">'.Yii::app()->params['textNoPrice'].'</span>';
                   } else $price = Yii::app()->params['textHidePrice'];

                   $analogProducts .= '<div class="cell width-15">'.$price.$countLabel.'</div>';
                } else {
                   $analogProducts .= '<div class="cell width-15">'.
                      '<a href="/site/login/" class="price_link">'.Yii::app()->params['textNoPrice'].'</a>'.$countLabel.
                   '</div>';
                }
            } else if(!Yii::app()->user->isGuest){
                $countAnalogs = Analog::model()->count("product_id=:id", array("id"=>$analog->id));
                if($countAnalogs) {
                    $analogProducts .= '<div class="cell width-15">'.
                        '<a class="prodInfo" target="_blank" href="'.$analog->path.'">аналоги</a>'.
                    '</div>';
                }
            } else {
                $analogProducts .= '<div class="cell width-15"></div>';
            }
             
            $analogProducts .= '<div class="cell width-20">';
             
            if(!Yii::app()->user->isGuest) {
                $analogProducts .= '<div class="cart-form" elem="'.$analog->id.'">';
                if(empty($analog->date_sale_off)) {
                    $intent = "\"yaCounter30254519.reachGoal('addtocard'); ga('send','event','action','addtocard'); return true;\" ";                               
                    if(Yii::app()->user->isGuest || (!Yii::app()->user->isGuest && !empty(Yii::app()->user->isShop))){
                       $analogProducts .= '<input type="number" min="1" pattern="[0-9]*" name="quantity" value="1" maxlength="4" size="7" autocomplete="off" product="1" class="cart-quantity">'.
                           '<input onclick='.$intent.' type="submit" title="Добавить в корзину" value="" class="small-cart-button">'
                           //'<button class="wish-small" title="Добавить в блокнот"><span class="wish-icon"></span></button>'
                       ;
                    }
                } else {
                    $analogProducts .= '<span>'.Yii::app()->params['textSaleOff'].'</span>'; 
                }
                $analogProducts .= '</div>';
            } else {
                //$analogProducts .= '<button class="login-button" title="Авторизоваться на сайте">Авторизоваться</button>';
                $analogProducts .= '<a class="login-button" href="/site/login/">Авторизоваться</a>';
            }
             
            $analogProducts .=     '</div>'.
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
