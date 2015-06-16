<?php

class ModellinesController extends Controller
{   
    public function actionIndex($id)
    {
        $category = Yii::app()->params['currentType'];
        $maker = Yii::app()->params['currentMaker'];
        $hitProducts = $modelIds = array();
        $count = 0;
        $response = '';
        $modelline = array();
        
        $categoryRoot = Category::model()->findByPk($id);
        if(!$categoryRoot)
            throw new CHttpException(404, 'Модельный ряд не найден');
        
        // bradcrumbs
        if(!empty(Yii::app()->params['searchFlag'])) {
            $url = '/search/show/';
            if(strpos(Yii::app()->request->urlReferrer, $url))
               $breadcrumbs['Поиск'] = Yii::app()->request->urlReferrer;
            else $breadcrumbs['Поиск'] = $url; //Yii::app()->request->urlReferrer;
        }
        Yii::app()->params['meta_title'] = 'Модельные ряды';
        $categoryParent = $categoryRoot->parent()->find();
        preg_match('/\d{2,}\./i', $categoryParent->name, $result);
        $title = trim(substr($categoryParent->name, strlen($result[0])));
        $currentBrand = '';
        $breadcrumbs[$title] = '/catalog'.$categoryParent->path.$currentBrand.'/';
        $breadcrumbs[] = $categoryRoot->name;
        Yii::app()->params['breadcrumbs'] = $breadcrumbs;
        // end breadcrumbs
        
        // сортировать по типу техники
        /*if(empty(Yii::app()->params['currentMaker'])){
            //найти все бренды
            $parentId = Category::model()->findByPk($category->id)->parent()->id;
            $all = Category::model()->findByAttributes('', array());
        }*/
        $result = $this->setMakerFilter($maker, $count, $id, $categoryRoot->name);
        $dependency = new CDbCacheDependency('SELECT MAX(update_time) FROM model_line');
        foreach($result as $res){
            $modelline = $res[0]; 
            $response .= $this->setModelline($modelline, $dependency, $categoryRoot);
        }
        
        /*echo '<pre>';
        var_dump($result);
        exit;
        
        if(!empty($result[0])) {
            $modelline = $result[0];     
            //ksort($modelline);
        }
        $response .= $this->setModelline($modelline, $dependency);
        */
        // random products for hit products
        $hitProducts = $this->setHitProducts($id);
        
        $this->render('modellines', array('response' => $response, 'hitProducts'=>$hitProducts));
    }
    
    private function setModelline($modelline, $dependency, $categoryRoot)
    {
        $response = '';
        /*echo '<pre>';
        var_dump($modelline);
        exit;*/
        if(!empty($modelline) && empty(Yii::app()->params['currentMaker'])) {
            foreach($modelline as $categoryName=>$models) {
                $response .= '<h2>'.$categoryName.'</h2>';
                //$response .= '<h2>бренд</h2>';
                $response .= '<table cellspacing="0" cellpadding="0" border="0"><tbody>';
                $count = 0;
                $dividend = 3;
                
                usort($models, array($this, 'sortByName'));
                
                foreach($models as $modelline) {
                    $category = ModelLine::model()->cache(1000, $dependency)->findByPk($modelline['id']);
                    $children = $category->children()->findAll();

                    $count++;
                    if($count == 1) $response .= '<tr>';
                    
                    $response .= '<td valign="top">'.
                          '<div class="grey">'.
                            '<ul class="accordion modelline">'.
                              '<li>'.
                                 '<a href="#">'.$modelline['name'].'</a>'.
                                 '<ul>'
                    ;

                    foreach($children as $child) {
                       $brand = EquipmentMaker::model()->findByPk($child->maker_id)->path;
                       $response .= '<li><a href="/catalog'.$categoryRoot->path.$brand.$child->path.'/">'.$child->name.'</a></li>';
                    }

                    $response .= '</ul>'.
                              '</li>'.
                            '</ul>'.
                          '</div>'.
                    '</td>'
                  ;
                  if($count == $dividend) {
                      $count = 0;
                      $response .= '</tr>';
                  }
                }

                $response .= '</tbody></table>';
            }
        } else if(!empty($modelline)){
            foreach($modelline as $categoryName=>$models) {
                $response .= '<h2>'.$categoryName.'</h2>
                   <table cellspacing="0" cellpadding="0" border="0"><tbody>'
                ;
                $count = 0;
                $dividend = 3;
                
                usort($models, array($this, 'sortByName'));
                
                foreach($models as $modelline) {
                    $category = ModelLine::model()->cache(1000, $dependency)->findByPk($modelline['id']);
                    $children = $category->children()->findAll();

                    $count++;
                    if($count == 1) $response .= '<tr>';
                    
                    $response .= '<td valign="top">'.
                          '<div class="grey">'.
                            '<ul class="accordion modelline">'.
                              '<li>'.
                                 '<a href="#">'.$modelline['name'].'</a>'.
                                 '<ul>'
                    ;

                    foreach($children as $child) {
                       $brand = EquipmentMaker::model()->findByPk($child->maker_id)->path;
                       $response .= '<li><a href="/catalog'.$categoryRoot->path.$brand.$child->path.'/">'.$child->name.'</a></li>';
                    }

                    $response .= '</ul>'.
                              '</li>'.
                            '</ul>'.
                          '</div>'.
                    '</td>'
                  ;
                  if($count == $dividend) {
                      $count = 0;
                      $response .= '</tr>';
                  }
                }

                $response .= '</tbody></table>';
            }
        }
        return $response;
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
                    'p.liquidity = "A" and p.image IS NOT NULL'.$sql,
                    'm.category_id=:category_id'
               ), array(':category_id'=>$id)
            )
            ->queryColumn()
        ;
        
        set_time_limit(200);
        $max = count($elements);
        $temp = array();
        
        if($max > 8) {
            for($i = 0; $i < 8; ) {
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
        } else {
            foreach($elements as $element) {
                $temp[] = $element;
            }
        }
        $hitProducts = Product::model()->cache(1000, $depend)->findAllByAttributes(array('id'=>$temp));
        return $hitProducts;
    }
    
    private function setMakerFilter($maker = null, $count = 0, $categoryId = null, $title = null)
    {
        $models = $temp = $result = array();
        $dependency = new CDbCacheDependency('SELECT MAX(update_time) FROM model_line');
        if(!empty($categoryId) || !empty($maker)) {
            $criteria = new CDbCriteria();
            if(!empty($categoryId)) {
                $criteria->addCondition('category_id = :category_id');
                $rootCategory = ModelLine::model()->cache(1000, $dependency)->findByPk($categoryId);
            }
            
            if(!empty($maker)) {
                $criteria->addCondition('maker_id = :maker_id');
            }
            if(!empty($maker) && !empty($categoryId)) {
                $criteria->params = array(':maker_id' => $maker, ':category_id' => $categoryId);
            } else {
                if(!empty($maker)) 
                    $criteria->params = array(':maker_id' => $maker);
                else 
                    $criteria->params = array(':category_id' => $categoryId);
            }
            
            if(empty(Yii::app()->params['currentMaker'])){
                $criteriaForBrand = $criteria;
                $criteriaForBrand->distinct = true;
                $criteriaForBrand->select = 'maker_id';
                
                $allBrands = ModelLine::model()->cache(1000, $dependency)->findAll($criteriaForBrand);
                
                foreach($allBrands as $brand) {
                   $criteria->distinct = false;
                   $criteria->select = '*';
                   $criteria->condition = 'maker_id = :maker_id';
                   $criteria->addCondition('category_id = :category_id');
                   $criteria->params = array(':maker_id' => $brand->maker_id, ':category_id' => $categoryId);
                   $name = EquipmentMaker::model()->findByPk($brand->maker_id)->name;
                   
                   $models = ModelLine::model()->cache(1000, $dependency)->findAll($criteria);
                   $result[$brand->maker_id] = $this->fillArray($models, $count, $name);
                }
            } else {
                $models = ModelLine::model()->cache(1000, $dependency)->findAll($criteria);
                $name = EquipmentMaker::model()->findByPk(Yii::app()->params['currentMaker'])->name;
                if(!empty($name))
                    $result[Yii::app()->params['currentMaker']] = $this->fillArray($models, $count, $name);
                    //$result[Yii::app()->params['currentMaker']] = $this->fillArray($models, $count, $title);
                else $result[Yii::app()->params['currentMaker']] = $this->fillArray($models, $count);
            }
        }
        
        return $result;
    }
    
    private function fillArray($models, $count, $title = null)
    {   
        $modelline = array();
        $label = $title;
        $dependency = new CDbCacheDependency('SELECT MAX(update_time) FROM model_line');
        foreach($models as $model) {
           $currentModel = ModelLine::model()->cache(1000, $dependency)->findByPk($model->id);
           if(!$currentModel->isLeaf()){
                if(empty($title)) {
                   $category = Category::model()->findByPk($currentModel->category_id);
                   $parent = $category->parent()->find();
                   $label = $parent->name;
                }
                
                preg_match('/\d{2,}\./i', $label, $result);
                $label = trim(substr($label, strlen($result[0])));
                
                $modelline[$label][$count]['name'] = $currentModel->name;
                $modelline[$label][$count]['id'] = $currentModel->id;
                $count++;
           }
        }
        
        return array($modelline, $count);
    }
    
    private static function sortByName($a, $b)
    {
        return strcmp(strtolower($a["name"]), strtolower($b["name"]));
    }
}