<?php
class SubcategoryController extends Controller
{        
    public function actionIndex($type = null, $maker = null)
    {
        $ids = array();
        $category = $type;
        $maker = $maker;
        $title = '';
        $response = '';
        $modelline = array();
        
        if(!empty(Yii::app()->params['searchFlag'])) {
            $url = '/search/show/';
            if(strpos(Yii::app()->request->urlReferrer, $url))
               $breadcrumbs['Поиск'] = Yii::app()->request->urlReferrer;
            else $breadcrumbs['Поиск'] = $url; //Yii::app()->request->urlReferrer;
        }
        
        if(!empty($category)) {
            $dependency = new CDbCacheDependency('SELECT MAX(update_time) FROM category');
            $categoryRoot = Category::model()->cache(1000, $dependency)->findByPk($category);
            if(!empty($categoryRoot)) {
                if(empty($maker))
                    $subcategories = $categoryRoot->children()->findAll(array('order'=>'name', 'condition'=>'published=:published', 'params'=>array(':published' => true)));
                else {
                    $criteria = new CDbCriteria;
                    $criteria->select = 'category_id';
                    $criteria->distinct = true;
                    $criteria->condition = 'maker_id=:maker';
                    $criteria->params = array(':maker'=>$maker);
                    $depend = new CDbCacheDependency('SELECT MAX(update_time) FROM model_line');
                    $models = ModelLine::model()->cache(1000, $depend)->findAll($criteria);
                    $temp = array();
                    
                    foreach($models as $model){
                        $temp[] = $model['category_id'];
                    }
                    
                    $crit = new CDbCriteria();
                    $crit->condition = 'published=:published';
                    $crit->params = array(':published'=>true);
                    $crit->addInCondition('id', $temp);
                    $crit->order = 'name';
                    $subcategories = $categoryRoot->children()->findAll($crit);
                }
            }
            
            if(!empty($subcategories)) {
                $response .= '<table cellspacing="0" cellpadding="0" border="0"><tbody>';
                $count = 0;
                $dividend = 3;
                foreach($subcategories as $subcategory) {
                    $brand = '';
                    if(!empty(Yii::app()->params['currentMaker'])) $brand = EquipmentMaker::model()->findByPk(Yii::app()->params['currentMaker'])->path;
                    
                    $ids[] = $subcategory['id'];
                    $count++;
                    if($count == 1) $response .= '<tr>';
                    $response .= '<td valign="top">'.
                          '<div class="grey">'.
                            '<ul class="accordion subcategory">'.
                              '<li>'.
                                 '<a href="/catalog'.$subcategory['path'].$brand.'/">'.$subcategory['name'].'</a>'.
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

            // bradcrumbs
            //preg_match('/\d{2,}\./i', $categoryRoot->name, $result);
            //$title = trim(substr($categoryRoot->name, strlen($result[0])));
            $title = $categoryRoot->name;
            $breadcrumbs[] = $title;
            
            Yii::app()->params['meta_title'] = $title;
            if(!empty($categoryRoot->meta_title)) Yii::app()->params['meta_title'] = $categoryRoot->meta_title;            
            if(!empty($categoryRoot->meta_description)) Yii::app()->params['meta_description'] = $categoryRoot->meta_description;
            
        } else if(!empty($maker)) {
            /*$result = $this->setMakerFilter($maker);
            if(!empty($result[0])) {
                $modelline = $result[0];
                $count = $result[1];
            }*/
            //echo 111; exit;
            $criteria = new CDbCriteria;
            $criteria->select = 'category_id';
            $criteria->distinct = true;
            $criteria->condition = 'maker_id=:maker';
            $criteria->params = array(':maker'=>$maker);
            $depend = new CDbCacheDependency('SELECT MAX(update_time) FROM model_line');
            $models = ModelLine::model()->cache(1000, $depend)->findAll($criteria);
            $temp = array();

            foreach($models as $model){
                $temp[] = $model['category_id'];
            }
            
            $crit = new CDbCriteria();
            $crit->condition = 'published=:published';
            $crit->params = array(':published'=>true);
            $crit->addInCondition('id', $temp);
            $crit->order = 'name';
            $dependency = new CDbCacheDependency('SELECT MAX(update_time) FROM category');
            $subcategories = Category::model()->cache(1000, $dependency)->findAll($crit);
            
            $count = 0;
            foreach($subcategories as $subcategory) {
                $label = $subcategory->parent()->find()->name;
                preg_match('/\d{2,}\./i', $label, $result);
                $label = trim(substr($label, strlen($result[0])));
            
                $modelline[$label][$count]['name'] = $subcategory->name;
                $modelline[$label][$count]['id'] = $subcategory->id;
                $modelline[$label][$count]['path'] = $subcategory->path;
                $count++;
            }
            
            // сортировать по типу техники
            ksort($modelline);
            
            if(!empty($modelline)) {
                foreach($modelline as $categoryName=>$models) {
                    $response .= '<h1>'.$categoryName.'</h1>
                       <table cellspacing="0" cellpadding="0" border="0"><tbody>'
                    ;
                    $count = 0;
                    $dividend = 3;

                    usort($models, array($this, 'sortByName'));

                    foreach($models as $modelline) {
                        $brand = '';
                        if(!empty(Yii::app()->params['currentMaker'])) $brand = EquipmentMaker::model()->findByPk(Yii::app()->params['currentMaker'])->path;
                        
                        $ids[] = $modelline['id'];
                        $category = ModelLine::model()->cache(1000, $depend)->findByPk($modelline['id']);
                        $children = $category->children()->findAll();

                        $count++;
                        if($count == 1) $response .= '<tr>';

                        $response .= '<td valign="top">'.
                              '<div class="grey">'.
                                '<ul class="accordion subcategory">'.
                                  '<li>'.
                                     '<a href="/catalog'.$modelline['path'].$brand.'/">'.$modelline['name'].'</a>'.
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
            // bradcrumbs
            $equipmentMaker = EquipmentMaker::model()->findByPk($maker);
            $name = $equipmentMaker->name;
            $breadcrumbs[] = $name;
            
            Yii::app()->params['meta_title'] = $name;
            if(!empty($equipmentMaker->meta_title)) Yii::app()->params['meta_title'] = $equipmentMaker->meta_title;
            if(!empty($equipmentMaker->meta_description)) Yii::app()->params['meta_description'] = $equipmentMaker->meta_description;
        }
        
        // random products for hit products
        //echo '='.Yii::app()->session['maker'];exit;
        $hitProducts = $this->setHitProducts($ids);
        
        // bradcrumbs
        
        Yii::app()->params['breadcrumbs'] = $breadcrumbs;
        
        $this->render('subcategory', array('response' => $response, 'hitProducts'=>$hitProducts, 'title'=>$title));
    }
    
    private function setHitProducts($ids)
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
                     array('in', 'm.category_id', $ids)
               )
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
    
    private function setMakerFilter($maker, $count = 0, $categoryId = null, $title = null)
    {
        $models = $temp = array();
        if(!empty($categoryId) || !empty($maker)) {
            $criteria = new CDbCriteria();
            $dependency = new CDbCacheDependency('SELECT MAX(update_time) FROM model_line');
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
            
            $models = ModelLine::model()->cache(1000, $dependency)->findAll($criteria);
        }
        
        if(!empty($title))
            $result = $this->fillArray($models, $count, $title);
        else $result = $this->fillArray($models, $count);
        
        return $result;
    }
    
    private function fillArray($models, $count, $title = null)
    {   
        $modelline = array();
        $label = $title;
        foreach($models as $model) {
           $dependency = new CDbCacheDependency('SELECT MAX(update_time) FROM model_line');
           $currentModel = ModelLine::model()->cache(1000, $dependency)->findByPk($model->id);
           if(!$currentModel->isLeaf()){
                if(empty($title)) {
                   $dep = new CDbCacheDependency('SELECT MAX(update_time) FROM category');
                   $category = Category::model()->cache(1000, $dep)->findByPk($currentModel->category_id);
                   $parent = $category->parent()->find();
                   $label = $parent->name;
                }
                
                preg_match('/\d{2,}\./i', $label, $result);
                $label = trim(substr($label, strlen($result[0])));
                
                /*$parent = $model->parent()->find()->name;
                $modelline[$label][$parent][$count]['name'] = $model->name;               
                $modelline[$label][$parent][$count]['id'] = $model->id;               
                $modelline[$label][$parent][$count]['path'] = $model->path;*/
                
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
