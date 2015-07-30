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
        //search for "/catalog/01-traktory/"
        //or "/manufacturer/case/"
        //or "/sparepart/15-bolt/"
        
        if(preg_match('/^[\w,-]+(\/[\w,-]+)$/', $pathInfo, $matches)) {
            $page = $matches[1];
            
            if(strpos($matches[0], 'catalog/') !== false) {
                $id = Category::model()->find(
                    'path=:path',
                    array(':path'=>$page)
                )->id;
                
                if(!empty($id)){
                   Yii::app()->params['currentType'] = Yii::app()->params['currentSale'] = $id;
                   return 'subcategory/index/type/'.$id;
                }
            } else if(strpos($matches[0], 'manufacturer/') !== false) {
                $id = EquipmentMaker::model()->find(
                    'path=:path',
                    array(':path'=>$page)
                )->id;
                
                if(!empty($id)){
                   Yii::app()->params['currentMaker'] = $id;
                   return 'subcategory/index/maker/'.$id;
                }
            } else if(strpos($matches[0], 'sparepart/') !== false) {
                $id = Product::model()->find(
                    'path=:path',
                    array(':path'=>'/'.$matches[0].'/')
                )->id;

                return 'product/index/id/'.$id;
            }
        } 
        // search for "/catalog/tractory/case/"
        // or "/catalog/samohodnye-kombayny/gomsel-mash/"
        else if(preg_match('/^[\w,-]+(\/[\w,-]+)(\/[\w,-]+)$/', $pathInfo, $matches)) { 
            $type = Category::model()->find(
                'path=:path',
                array(':path'=>$matches[1].$matches[2])
            );
            if(!empty($type)) {
                Yii::app()->params['currentSale'] = $type->id;
                Yii::app()->params['currentType'] = $type->parent()->find()->id;
                return 'modellines/index/id/'.$type->id;
            } else {
                $type = $matches[1];
                $maker = $matches[2];
                $makerId = '';
                
                $typeId = Category::model()->find(
                    'path=:path',
                    array(':path'=>$type)
                )->id;
                
                if(!empty($typeId)){
                    $makerId = EquipmentMaker::model()->find(
                        'path=:path',
                        array(':path'=>$maker)
                    )->id;
                }
                
                if(!empty($typeId) && !empty($makerId)) {
                    Yii::app()->params['currentType'] = Yii::app()->params['currentSale'] = $typeId;
                    Yii::app()->params['currentMaker'] = $makerId;
                    return 'subcategory/index/type/'.$typeId.'/maker/'.$makerId;
                }
            }
        }
        // search for "/catalog/samohodnye-kombayny/gomsel-mash/case/"
        else if(preg_match('/^[\w,-]+((\/[\w,-]+){2})(\/[\w,-]+)$/', $pathInfo, $matches)) {
            $type = Category::model()->find(
                'path=:path',
                array(':path'=>$matches[1])
            );
            
            if(!empty($type)) {
                Yii::app()->params['currentSale'] = $type->id;
                $makerId = EquipmentMaker::model()->find(
                    'path=:path',
                    array(':path'=>$matches[3])
                )->id;
                
                if(!empty($makerId)){
                    Yii::app()->params['currentType'] = $type->parent()->find()->id;
                    Yii::app()->params['currentMaker'] = $makerId;
                
                    return 'modellines/index/id/'.$type->id;
                }
            }
        }
        // search for "/catalog/samohodnye-kombayny/gomsel-mash/case/model-line"
        else if(preg_match('/^[\w,-]+((\/[\w,-]+){2})(\/[\w,-]+)(\/[\w,-]+)$/', $pathInfo, $matches)) {
            $modelLine = ModelLine::model()->find(
                'path=:path',
                array(':path'=>$matches[4])
            );

            if(!empty($modelLine)) {
                $makerId = EquipmentMaker::model()->find(
                    'path=:path',
                    array(':path'=>$matches[3])
                )->id;
                
                if(!empty($makerId) && $makerId == $modelLine->maker_id) {
                    $type = Category::model()->find(
                        'path=:path',
                        array(':path'=>$matches[1])
                    );

                    if(!empty($type) && $type->id == $modelLine->category_id) {
                        Yii::app()->params['currentSale'] = $type->id;
                        Yii::app()->params['currentType'] = $type->parent()->find()->id;
                        Yii::app()->params['currentMaker'] = $makerId;

                        return '/modelline/index/id/'.$modelLine->id;
                    }
                }
            }
        }
        // search for "/catalog/samohodnye-kombayny/gomsel-mash/case/model-line/model"
        else if(preg_match('/^[\w,-]+((\/[\w,-]+){2})(\/[\w,-]+)((\/[\w,-]+){2})$/', $pathInfo, $matches)){
            //echo '<pre>';
            //var_dump($matches);exit;

            $modelLine = ModelLine::model()->find(
                'path=:path',
                array(':path'=>$matches[4])
            );

            if(!empty($modelLine)) {
                $makerId = EquipmentMaker::model()->find(
                    'path=:path',
                    array(':path'=>$matches[3])
                )->id;
                
                if(!empty($makerId) && $makerId == $modelLine->maker_id) {
                    $type = Category::model()->find(
                        'path=:path',
                        array(':path'=>$matches[1])
                    );

                    if(!empty($type) && $type->id == $modelLine->category_id) {
                        Yii::app()->params['currentSale'] = $type->id;
                        Yii::app()->params['currentType'] = $type->parent()->find()->id;
                        Yii::app()->params['currentMaker'] = $makerId;
                        
                        $model = ModelLine::model()->find(
                            'path=:path',
                            array(':path'=>$matches[5])
                        );
                        
                        Yii::app()->session['model'] = $modelLine->id;
                        return '/model/show/id/'.$modelLine->id;
                    }
                }
            }
        }        
        
        return false;  // не применяем данное правило
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
