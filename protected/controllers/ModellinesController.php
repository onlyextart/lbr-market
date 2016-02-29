<?php

class ModellinesController extends Controller
{   
    public function actionIndex($id)
    {
        $category = Yii::app()->params['currentType'];
        $maker = Yii::app()->params['currentMaker'];
        $hitProducts = $modelIds = array();
        $response = $topText = $bottomText = $h1Title = '';
        $modelline = array();
        
        $category_dependency = new CDbCacheDependency('SELECT MAX(update_time) FROM category');
        $categoryRoot = Category::model()->cache(1000, $category_dependency)->findByPk($id);
        if(!$categoryRoot)
            throw new CHttpException(404, 'Модельный ряд не найден');
        
        // bradcrumbs
        if(!empty(Yii::app()->params['searchFlag'])) {
            $url = '/search/show/';
            if(strpos(Yii::app()->request->urlReferrer, $url))
               $breadcrumbs['Поиск'] = Yii::app()->request->urlReferrer;
            else $breadcrumbs['Поиск'] = $url; //Yii::app()->request->urlReferrer;
        }
        
        $categoryParent = $categoryRoot->parent()->find();
        $title = $categoryParent->name;
        
        $currentBrand = '';
        $breadcrumbs[$title] = '/catalog'.$categoryParent->path.$currentBrand.'/';
        if(!empty($maker)) {
           $breadcrumbs[$categoryRoot->name] = '/catalog'.$categoryRoot->path.'/';
           $equipmentMaker = EquipmentMaker::model()->findByPk($maker);
           $response .= '<h1>'.$equipmentMaker->name.'</h1>';
           $equipmentMakerName = $equipmentMaker->name;
           $breadcrumbs[] = $equipmentMakerName;
           Yii::app()->params['meta_title'] = Yii::app()->params['meta_description'] = $categoryRoot->name.' '.$equipmentMakerName;
           if(!empty($equipmentMaker->meta_title)) Yii::app()->params['meta_title'] = $equipmentMaker->meta_title;
           if(!empty($equipmentMaker->meta_description)) Yii::app()->params['meta_title'] = $equipmentMaker->meta_description;
        } else {
           $breadcrumbs[] = $categoryRoot->name;
           $h1Title = $categoryRoot->name;
           if(!empty($categoryRoot->h1)) $h1Title = $categoryRoot->h1;
           Yii::app()->params['meta_title'] = Yii::app()->params['meta_description'] = $categoryRoot->name;
           if(!empty($categoryRoot->meta_title)) Yii::app()->params['meta_title'] = $categoryRoot->meta_title;
           if(!empty($categoryRoot->meta_description)) Yii::app()->params['meta_title'] = $categoryRoot->meta_description;
        }
        Yii::app()->params['breadcrumbs'] = $breadcrumbs;
        // end breadcrumbs
        
        if(!empty($maker)) {
            $seoText = CategorySeo::model()->find('category_id=:category and equipment_id=:maker', array('category'=>$categoryRoot->id, 'maker'=>$maker));
            if(!empty($seoText)) {
                if(!empty($seoText->meta_title)) Yii::app()->params['meta_title'] = $seoText->meta_title;
                if(!empty($seoText->meta_description)) Yii::app()->params['meta_description'] = $seoText->meta_description;
                if(Yii::app()->params['showSeoTexts']) {
                    if(!empty($seoText->top_text)) $topText = $seoText->top_text;
                    if(!empty($seoText->bottom_text)) $bottomText = $seoText->bottom_text;
                }
            }
        } else {
            if(!empty($categoryRoot->meta_title)) Yii::app()->params['meta_title'] = $categoryRoot->meta_title;
            if(!empty($categoryRoot->meta_description)) Yii::app()->params['meta_description'] = $categoryRoot->meta_description;
            if(Yii::app()->params['showSeoTexts']) {
                if(!empty($categoryRoot->top_text)) $topText = $categoryRoot->top_text;
                if(!empty($categoryRoot->bottom_text)) $bottomText = $categoryRoot->bottom_text;
            }
        }

        $result_array = $this->setMakerFilter($id, $categoryRoot->name,true);
        $result=$result_array['result'];
        $result_top=$result_array['result_top'];
        $dependency = new CDbCacheDependency('SELECT MAX(update_time) FROM model_line');
        //echo '<pre>';
        //var_dump($result); exit;
        // show in two columns
        
        if(!empty($result)) {
            $count = count($result);
            $half = ceil($count/2);
        
            $response .= '<table cellspacing="0" cellpadding="4" border="0"><tbody>';
            for($index = 0; $index < $half; $index++) {
                $response .= '<tr>';
                $response .= '<td width="50%" valign="top">';
                $response .= $this->setModelline($result[$index], $dependency, $categoryRoot);
                $response .= '</td>';
                if(($index + $half) < $count){
                    $response .= '<td width="50%" valign="top">';
                    $response .= $this->setModelline($result[$index + $half], $dependency, $categoryRoot);
                    $response .= '</td>';
                }
                $response .= '</tr>';
            }
            $response .= '</tbody></table>';
        }
        
        // table for view only top makers
        $response_top='';
        if(!empty($result_top)) {
            $count = count($result_top);
        
            $response_top .= '<table cellspacing="0" cellpadding="4" border="0"><tbody>';
            for($index = 0; $index < $count; $index++) {
                $response_top .= '<tr>';
                $response_top .= '<td valign="top">';
                $response_top .= $this->setModelline($result_top[$index], $dependency, $categoryRoot);
                $response_top .= '</td>';
                $response_top .= '</tr>';
            }
            $response_top .= '<tr><td><span class="link-brands">Показать всех производителей...</span></td></tr>';
            $response_top .= '</tbody></table>';
        }
        
        $this->render('modellines', array('response' => $response, 'response_top' => $response_top, 'title' => $h1Title, 'topText'=>$topText, 'bottomText'=>$bottomText));
    }
    
    private function setModelline($modelline, $dependency, $categoryRoot)
    {
        $response = '';
        if(!empty($modelline) && empty(Yii::app()->params['currentMaker'])) {
            foreach($modelline as $categoryName=>$modelsIds) {
                $response .= '<ul class="accordion modelline">'.
                              '<li>'.
                                 '<a href="#" class="sub-title">'.$categoryName.'</a>'.
                                 '<ul>';
                
                $criteria = new CDbCriteria();
                $criteria->addInCondition('id', $modelsIds);
                $criteria->order = 'name';
                
                $models = ModelLine::model()->findAll($criteria);
                $criteria->addCondition('catalog_top=0');
                $models_non_top=ModelLine::model()->findAll($criteria);
                
                foreach($models as $model) {
                    $category = ModelLine::model()->cache(1000, $dependency)->findByPk($model['id']);
                    $children = $category->children()->findAll();
                    $sign_top=($category->catalog_top==1)?"top":"non_top";
                    $response .= '<li class="'.$sign_top.'"><a href="#" class="sub-child-title">'.$model['name'].'</a><ul>';                    

                    foreach($children as $child) {
                       $brand = EquipmentMaker::model()->findByPk($child->maker_id)->path;
                       $response .= '<li><a class="modelline-child" href="/catalog'.$categoryRoot->path.$brand.$child->path.'/">'.$child->name.'</a></li>';
                    }
                   
                    $response .= '</ul></li>';
                    
                }
                 //link View all
                    if (!empty($models_non_top)) {
                        $response .= '<li><span class="link-modellines">Показать все модельные ряды...</span></li>';
                    }
                $response .=     '</ul>'.
                              '</li>'.
                             '</ul>'
                ;
            }
        } else if(!empty($modelline)) {
            //echo '<pre>';
            //var_dump($modelline); exit;
//            foreach($modelline as $categoryName => $modelsIds) {
//                $count = 0;
//                $dividend = 2;
//                $title = $categoryName;
//                
//                $criteria = new CDbCriteria();
//                $criteria->addInCondition('id', $modelsIds);
//                $criteria->order = 'name';
//                $models = ModelLine::model()->findAll($criteria);
//                
//                $response .= '<h1>'.$title.'</h1>';
//                $response .= '<table cellspacing="0" cellpadding="0" border="0"><tbody>';
//                foreach($models as $model) {
//                    $category = ModelLine::model()->cache(1000, $dependency)->findByPk($model['id']);
//                    $children = $category->children()->findAll();
//
//                    $count++;
//                    if($count == 1) $response .= '<tr>';
//                    
//                    $response .= '<td valign="top">'.
//                          '<div class="grey">'.
//                            '<ul class="accordion modelline">'.
//                              '<li>'.
//                                 '<a href="#" class="sub-title">'.$model['name'].'</a>'.
//                                 '<ul>'
//                    ;
//
//                    foreach($children as $child) {
//                       $brand = EquipmentMaker::model()->findByPk($child->maker_id)->path;
//                       $response .= '<li><a href="/catalog'.$categoryRoot->path.$brand.$child->path.'/">'.$child->name.'</a></li>';
//                    }
//
//                    $response .= '</ul>'.
//                              '</li>'.
//                            '</ul>'.
//                          '</div>'.
//                        '</td>'
//                    ;
//                    if($count == $dividend) {
//                        $count = 0;
//                        $response .= '</tr>';
//                    }
//                }
//
//                $response .= '</tbody></table>';
//            }
            
            $model = ModelLine::model()->findByPk($modelline);

            $response .= '<ul class="accordion modelline">'.
                              '<li>'.
                                 '<a href="#" class="sub-title">'.$model->name.'</a>'.
                                 '<ul>'
            ;
            
            $children = $model->children()->findAll();
                           

            foreach($children as $child) {
               $brand = EquipmentMaker::model()->findByPk($child->maker_id)->path;
               $response .= '<li><a class="sub-child-title" href="/catalog'.$categoryRoot->path.$brand.$child->path.'/">'.$child->name.'</a></li>';
               
               //$response .= '<li><a href="#" class="sub-child-title">'.$model['name'].'</a><ul>';     
            }
            
            $response .=         '</ul>'.
                              '</li>'.
                          '</ul>'
            ;
        }
        
        return $response;
    }
    
    private function setMakerFilter($categoryId, $title = null, $top=null)
    {
        $maker = Yii::app()->params['currentMaker'];
        $models = $temp = $result = array();
        
        if(!empty($categoryId) || !empty($maker)) {
            $criteria = new CDbCriteria();
            if(!empty($categoryId)) {
                $criteria->addCondition('category_id = :category_id');
            }
            if(!empty($maker)) {
                $criteria->addCondition('maker_id = :maker_id');
            }
            
            if(!empty($maker) && !empty($categoryId)) {
                $criteria->params = array(':maker_id' => $maker, ':category_id' => $categoryId);
            } else if(!empty($maker)) 
                $criteria->params = array(':maker_id' => $maker);
            else if(!empty($categoryId)){
                $criteria->params = array(':category_id' => $categoryId);
                if(isset($top)){
                    $top_makers = Yii::app()->db->createCommand()
                            ->selectDistinct('maker_id')
                            ->from('category_makers_top')
                            ->where('category_id = :category_id', array(':category_id' => $categoryId))
                            ->queryColumn();
                }
            }
            
            if(empty($maker)) {
                $criteriaForBrand = $criteria;
                $criteriaForBrand->distinct = true;
                $criteriaForBrand->select = 'maker_id';
                
                $allBrands = ModelLine::model()->findAll($criteriaForBrand);
                foreach($allBrands as $brand) {
                   $name = EquipmentMaker::model()->findByPk($brand->maker_id)->name;
                   $modellines_of_brand= Yii::app()->db->createCommand()
                        ->selectDistinct('id')
                        ->from('model_line')
                        ->where('maker_id = :maker_id and category_id = :category_id and level = 2', array(':maker_id' => $brand->maker_id, ':category_id' => $categoryId))
                        ->queryColumn()
                   ;
                   $result[][$name] =$modellines_of_brand;
                   if(isset($top_makers)&&!empty($top_makers)){
                       if(in_array($brand->maker_id, $top_makers)){
                            $result_top[][$name]=$modellines_of_brand;
                       }
                  }   
                }
            } else {
                $name = EquipmentMaker::model()->findByPk(Yii::app()->params['currentMaker'])->name;
                $nameForCategoryInBrand = CategorySeo::model()->find('category_id=:category and equipment_id=:equipment', array('category'=>$categoryId, 'equipment'=>Yii::app()->params['currentMaker']))->h1;
                if(!empty($nameForCategoryInBrand)) $name = $nameForCategoryInBrand;
                
                $criteria->addCondition('level = 2');
                $models = ModelLine::model()->findAll($criteria);
                foreach($models as $model){
                    $result[] = $model->id;
                }
            }
        }
        
        return array('result'=>$result,
                     'result_top'=>$result_top);
    }
    
    /*private function setHitProducts($id)
    {   
        $sql = '';
        $hitProducts='';
        if(!empty(Yii::app()->params['currentMaker'])) {
            $sql = ' and m.maker_id = '.Yii::app()->params['currentMaker'];
        }
        
        $elements = Yii::app()->db->cache(1000)->createCommand()
            ->selectDistinct('p.id')
            ->from('model_line m')
            ->join('product_in_model_line pm', 'm.id=pm.model_line_id')
            ->join('product p', 'p.id=pm.product_id')
            ->where(
               array('and', 
                    'p.liquidity = "A" and p.image IS NOT NULL and p.published=:flag'.$sql,
                    'm.category_id=:category_id'
               ), array(':category_id'=>$id,':flag'=>true)
            )
            ->queryColumn()
        ;
        
        set_time_limit(200);
        $max = count($elements);
        $count = 8;
        if ($max > 0) {
            if ($max >= $count) {
                $random_elem = array_rand($elements, $count);
            } else {
                $random_elem = array_rand($elements, $max);
            }
            $random_count = count($random_elem);
            $query = "SELECT * from product where id in (";
            for ($i = 0; $i < $random_count; $i++) {
                if ($i != 0) {
                    $query.=',';
                }
                $query.=$elements[$random_elem[$i]];
            }
            $query.=");";
            $hitProducts = Product::model()->findAllBySql($query);
        }
        
        return $hitProducts;
    }*/
}