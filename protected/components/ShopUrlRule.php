<?php
class ShopUrlRule extends CBaseUrlRule
{
    public $connectionID = 'db';
 
    public function createUrl($manager,$route,$params,$ampersand)
    {
        return false;  // this rule does not apply
    }
 
    public function parseUrl($manager,$request,$pathInfo,$rawPathInfo)
    {
        $this->setCookie($_GET);
        $page = false;
        
        /* 
         * search for "/catalog/01-traktory/" -> subcategory controller
         * or "/manufacturer/case/"           -> subcategory controller
         * or "/sparepart/15-bolt/"           -> product controller
         * or "/products/amortizatory-kabiny/" -> groupfilter controller
         */        
        if(preg_match('/^[\w,-]+(\/[\w,-]+)$/', $pathInfo, $matches)) {
            $page = $matches[1];
            
            if(strpos($matches[0], 'catalog/') !== false) {
                $category = Category::model()->find(
                    'path=:path',
                    array(':path'=>$page)
                );
                
                if(!empty($category)) {
                   Yii::app()->params['analiticsMark'] = 'category='.$category->external_id;
                   Yii::app()->params['currentType'] = Yii::app()->params['currentSale'] = $category->id;
                   return 'subcategory/index/type/'.$category->id;
                }
            } else if(strpos($matches[0], 'manufacturer/') !== false) {
                $maker = EquipmentMaker::model()->find(
                    'path=:path',
                    array(':path'=>$page)
                );
                //echo $maker->id; exit;
                if(!empty($maker)) {
                   Yii::app()->params['analiticsMark'] = 'maker='.$maker->external_id;
                   Yii::app()->params['currentMaker'] = $maker->id;
                   return 'subcategory/index/maker/'.$maker->id;
                }
            } else if(strpos($matches[0], 'sparepart/') !== false) {
                $product = Product::model()->find(
                    'path=:path',
                    array(':path'=>'/'.$matches[0].'/')
                );
                
                if(!empty($product)) {
                   Yii::app()->params['analiticsMark'] = 'product='.$product->external_id;
                   return 'product/index/id/'.$product->id;
                }
            } else if(strpos($matches[0], 'products/') !== false) {
                $group = ProductGroupFilter::model()->find(
                    'path=:path',
                    array(':path'=>'/'.$matches[0])
                );
                if(!empty($group)) {
                   return 'groupfilter/index/id/'.$group->id;
                }
            }
        } 
        /*
         * search for "/products/filtry-vozdushnye/zagotovka-kormov/" -> groupfilter controller
         */
        else if(preg_match('/^(products\/[\w,-]+)(\/[\w,-]+)$/', $pathInfo, $matches)) {
            $category = Category::model()->find(
                'path=:path',
                array(':path'=>$matches[2])
            );
            
            $group = ProductGroupFilter::model()->find(
                'path=:path',
                array(':path'=>'/'.$matches[1])
            );

            if(!empty($category) && !empty($group)) {
                return 'groupfilter/category/categoryId/'.$category->id.'/filterId/'.$group->group_id;
            }
        }
        /*
         * search for "/catalog/traktornaya-tehnika/traktory/"     -> modellines controller
         * or "/catalog/traktornaya-tehnika/buhler-versatile-inc/" -> subcategory controller
         */
        else if(preg_match('/^[\w,-]+(\/[\w,-]+)(\/[\w,-]+)$/', $pathInfo, $matches)) {
            $type = Category::model()->find(
                'path=:path',
                array(':path'=>$matches[1].$matches[2])
            );
            if(!empty($type)) {
                Yii::app()->params['analiticsMark'] = 'category='.$type->external_id;
                Yii::app()->params['currentSale'] = $type->id;
                Yii::app()->params['currentType'] = $type->parent()->find()->id;
                return 'modellines/index/id/'.$type->id;
            } else {
                $type = $matches[1];
                $maker = $matches[2];
                $makerId = '';
                
                $type = Category::model()->find(
                    'path=:path',
                    array(':path'=>$type)
                );
                
                if(!empty($type)){
                    $maker = EquipmentMaker::model()->find(
                        'path=:path',
                        array(':path'=>$maker)
                    );
                    
                    if(!empty($maker)) {
                        Yii::app()->params['analiticsMark'] = 'category='.$type->external_id.';'.'maker='.$maker->external_id;
                        Yii::app()->params['currentType'] = Yii::app()->params['currentSale'] = $type->id;
                        Yii::app()->params['currentMaker'] = $maker->id;
                        return 'subcategory/index/type/'.$type->id.'/maker/'.$maker->id;
                    }
                }
            }
        }
        /* 
         * search for "/products/amortizatory-kabiny/zagotovka-kormov/kosilki/" -> groupfilter controller
         */
        else if(preg_match('/^(products\/[\w,-]+)((\/[\w,-]+){2})$/', $pathInfo, $matches)){
            $category = Category::model()->find(
                'path=:path',
                array(':path'=>$matches[2])
            );
            
            $group = ProductGroupFilter::model()->find(
                'path=:path',
                array(':path'=>'/'.$matches[1])
            );

            if(!empty($category) && !empty($group)) {
                return 'groupfilter/modellines/categoryId/'.$category->id.'/groupId/'.$group->group_id;
            }
        }
        /* 
         * search for "/catalog/traktornaya-tehnika/traktory/case/" -> modellines controller
         */
        else if(preg_match('/^[\w,-]+((\/[\w,-]+){2})(\/[\w,-]+)$/', $pathInfo, $matches)) {            
            $type = Category::model()->find(
                'path=:path',
                array(':path'=>$matches[1])
            );
            
            if(!empty($type)) {
                Yii::app()->params['currentSale'] = $type->id;
                $maker = EquipmentMaker::model()->find(
                    'path=:path',
                    array(':path'=>$matches[3])
                );
                
                if(!empty($maker)) {
                    Yii::app()->params['analiticsMark'] = 'category='.$type->external_id.';'.'maker='.$maker->external_id;
                    Yii::app()->params['currentType'] = $type->parent()->find()->id;
                    Yii::app()->params['currentMaker'] = $maker->id;
                
                    return 'modellines/index/id/'.$type->id;
                }
            }
        }
        /* 
         * search for "/products/filtry-vozdushnye/zernouborochnye-kombayny-i-zhatki/kombayny/case/" -> groupfilter controller
         */
        else if(preg_match('/^(products\/[\w,-]+)((\/[\w,-]+){2})(\/[\w,-]+)$/', $pathInfo, $matches)) {
//            echo '<pre>';
//            var_dump($matches);exit;
            //////////////////////
            $category = Category::model()->find(
                'path=:path',
                array(':path'=>$matches[2])
            );
            
            $group = ProductGroupFilter::model()->find(
                'path=:path',
                array(':path'=>'/'.$matches[1])
            );

            if(!empty($category) && !empty($group)) {
                $brand = EquipmentMaker::model()->find(
                    'path=:path',
                    array(':path'=>$matches[4])
                );
                
                //////////////
                
                if(!empty($brand)) {
                    return 'groupfilter/brand/categoryId/'.$category->id.'/groupId/'.$group->group_id.'/brandId/'.$brand->id;
                }
            }
        }
        /*
         *  search for "catalog/traktornaya-tehnika/traktory/case/case-c50-c60-c70-c90/" -> modelline controller
         */
        else if(preg_match('/^[\w,-]+((\/[\w,-]+){2})(\/[\w,-]+)(\/[\w,-]+)$/', $pathInfo, $matches)) {
            $modelLine = ModelLine::model()->find(
                'path=:path',
                array(':path'=>$matches[4])
            );

            if(!empty($modelLine)) {
                $maker = EquipmentMaker::model()->find(
                    'path=:path',
                    array(':path'=>$matches[3])
                );
                
                if(!empty($maker) && $maker->id == $modelLine->maker_id) {
                    $type = Category::model()->find(
                        'path=:path',
                        array(':path'=>$matches[1])
                    );

                    if(!empty($type) && $type->id == $modelLine->category_id) {
                        Yii::app()->params['currentSale'] = $type->id;
                        Yii::app()->params['currentType'] = $type->parent()->find()->id;
                        Yii::app()->params['currentMaker'] = $maker->id;
                        Yii::app()->params['analiticsMark'] = 'modelline='.$modelLine->external_id;
                        return '/modelline/index/id/'.$modelLine->id;
                    }
                }
            }
        }
        /*
         *  search for "/catalog/traktornaya-tehnika/traktory/case/case-c50-c60-c70-c90/c50/" -> model controller
         */
        else if(preg_match('/^[\w,-]+((\/[\w,-]+){2})(\/[\w,-]+)((\/[\w,-]+){2})$/', $pathInfo, $matches)) {
            $modelLine = ModelLine::model()->find(
                'path=:path',
                array(':path'=>$matches[4])
            );

            if(!empty($modelLine)) {
                $maker = EquipmentMaker::model()->find(
                    'path=:path',
                    array(':path'=>$matches[3])
                );
                
                if(!empty($maker) && $maker->id == $modelLine->maker_id) {
                    $type = Category::model()->find(
                        'path=:path',
                        array(':path'=>$matches[1])
                    );

                    if(!empty($type) && $type->id == $modelLine->category_id) {
                        Yii::app()->params['currentSale'] = $type->id;
                        Yii::app()->params['currentType'] = $type->parent()->find()->id;
                        Yii::app()->params['currentMaker'] = $maker->id;
                        
                        /*$model = ModelLine::model()->find(
                            'path=:path',
                            array(':path'=>$matches[5])
                        );*/
                        
                        Yii::app()->params['analiticsMark'] = 'modelline='.$modelLine->external_id;
                        Yii::app()->session['model'] = $modelLine->id;
                        return '/model/show/id/'.$modelLine->id;
                    }
                }
            }
        }   
        /* 
         * search for "/products/amortizatory-kabiny/zagotovka-kormov/kombayny-izmel-chiteli-kormouborochnye/claas-kgaa-mbh/jaguar-695-840/jaguar-840/" -> groupfilter controller
         */
        else if(preg_match('/^(products\/[\w,-]+)((\/[\w,-]+){2})(\/[\w,-]+)((\/[\w,-]+){2})$/', $pathInfo, $matches)) {
            $category = Category::model()->find(
                'path=:path',
                array(':path'=>$matches[2])
            );
            
            $group = ProductGroupFilter::model()->find(
                'path=:path',
                array(':path'=>'/'.$matches[1])
            );

            if(!empty($category) && !empty($group)) {
                $model = ModelLine::model()->find(
                    'path=:path',
                    array(':path'=>$matches[5])
                );
                
                $brand = EquipmentMaker::model()->find(
                    'path=:path',
                    array(':path'=>$matches[4])
                );
                
                if(!empty($model) && !empty($brand)) {
                    if($model->maker_id == $brand->id) {
                        return 'groupfilter/model/categoryId/'.$category->id.'/groupId/'.$group->group_id.'/modelId/'.$model->id.'/brandId/'.$brand->id;
                    }
                }
            }
        }
        /*
         *  search for "/catalog/traktornaya-tehnika/traktory/case/case-c50-c60-c70-c90/c50/sort/name/order/asc/" -> model controller
         */
        else if(preg_match('/^[\w,-]+((\/[\w,-]+){2})(\/[\w,-]+)((\/[\w,-]+){2})(\/(sort)\/(col|category|name)\/(order)\/(asc|desc))$/', $pathInfo, $matches)) {
            $modelLine = ModelLine::model()->find(
                'path=:path',
                array(':path'=>$matches[4])
            );
            
            if(!empty($modelLine)) {
                $maker = EquipmentMaker::model()->find(
                    'path=:path',
                    array(':path'=>$matches[3])
                );
                
                if(!empty($maker) && $maker->id == $modelLine->maker_id) {
                    $type = Category::model()->find(
                        'path=:path',
                        array(':path'=>$matches[1])
                    );

                    if(!empty($type) && $type->id == $modelLine->category_id) {
                        Yii::app()->params['currentSale'] = $type->id;
                        Yii::app()->params['currentType'] = $type->parent()->find()->id;
                        Yii::app()->params['currentMaker'] = $maker->id;
                        
                        /*$model = ModelLine::model()->find(
                            'path=:path',
                            array(':path'=>$matches[5])
                        );*/
                        
                        Yii::app()->params['analiticsMark'] = 'modelline='.$modelLine->external_id;
                        Yii::app()->session['model'] = $modelLine->id;
                        //return '/model/show/id/'.$modelLine->id.'/sort/'.$matches[8].'/order/'.$matches[10];
                        return '/model/show/id/'.$modelLine->id;
                    }
                }
            }
        } 
//        else {
//            echo 111; exit;
//            preg_match('/^products/', $pathInfo, $matches);
//            echo '<pre>';
//            var_dump($matches);exit;
//        }  
        
        return false;  // this rule does not apply
    }
    
    public function setCookie($array)
    {
        $cookies = Yii::app()->request->cookies;
        if(isset($array['ct']) && Yii::app()->request->cookies['ct']->value != $array['ct']) {
            $cookie = new CHttpCookie('ct', $array['ct']);
            $cookie->expire = time() + 31104000; // save for a year
            $cookies['ct'] = $cookie;
        }
        
        if(isset($array['sb']) && Yii::app()->request->cookies['sb']->value != $array['sb']) {
            $cookie = new CHttpCookie('sb', $array['sb']);
            $cookie->expire = time() + 31104000; // save for a year
            $cookies['sb'] = $cookie;
        }
        
        if(isset($array['lk']) && Yii::app()->request->cookies['lk']->value != $array['lk']) {
            $cookie = new CHttpCookie('lk', $array['lk']);
            $cookies['lk'] = $cookie;
        }
    }
}
