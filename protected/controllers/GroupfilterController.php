<?php
class GroupfilterController extends Controller
{
    /* Show page with categories and subcategories */
    
    public function actionIndex($id)
    {
        $filter = ProductGroupFilter::model()->findByPk($id);
        if(!$filter)
            throw new CHttpException(404, 'Товар не найден');
        $title = $filter->name;
        
        $response = $this->getCategories($filter);
        
        $breadcrumbs[] = $title;
        Yii::app()->params['breadcrumbs'] = $breadcrumbs;
        
        Yii::app()->params['meta_title'] = Yii::app()->params['meta_description'] = $title;
        
        $this->render('index', array(
            'response' => $response,
            'title' => $title
        ));
    }
    
    /* Show page with subcategory */
    
    public function actionCategory($categoryId, $filterId)
    {
        // Breadcrumbs
        $filter = ProductGroupFilter::model()->findByAttributes(array('group_id' => $filterId));
        $breadcrumbs[$filter->name] = $filter->path.'/';
        
        $category = Category::model()->findByPk($categoryId);   
        $title = $category->name;
               
        $breadcrumbs[] = $title;
        Yii::app()->params['breadcrumbs'] = $breadcrumbs;
        // end Breadcrubs
        
        $temp = $this->getSubcategories($filter, $categoryId);
        $arrayKeys = array_keys($temp);
        $temp = $temp[$arrayKeys[0]];
        
        $count = count($temp);
        $half = ceil($count/2);
        
        ksort($temp);
        $allKeys = array_keys($temp);
        
        $response = '<table cellspacing="0" cellpadding="0" border="0"><tbody>';
        for($index = 0; $index < $half; $index++) {                
            $response .= '<tr>';
            $response .= '<td width="50%" valign="top">';
            
            $element = $temp[$allKeys[$index]];
            $response .= '<a href="'.$filter->path.$element['path'].'/">'.$element['name'].'</a>';
            
            $response .= '</td>';
            if(($index + $half) < $count) {
                $response .= '<td width="50%" valign="top">';
                
                $element = $temp[$allKeys[$index + $half]];
                $response .= '<a href="'.$filter->path.$element['path'].'/">'.$element['name'].'</a>';
                
                $response .= '</td>';
            }
            $response .= '</tr>';
        }
        $response .= '</tbody></table>';
        
        Yii::app()->params['meta_title'] = Yii::app()->params['meta_description'] = $title;
        
        $this->render('category', array(
            'response' => $response,
            'title' => $title
        ));
    }
    
    /* Show page with brands and models */
        
    public function actionModellines($categoryId, $filterId)
    {
        // Breadcrumbs
        $filter = ProductGroupFilter::model()->findByPk($filterId);
        $breadcrumbs[$filter->name] = $filter->path.'/';
        
        $category = Category::model()->findByPk($categoryId);
        $title = $category->name;
        
        $categoryParent = $category->parent()->find();
        $breadcrumbs[$categoryParent->name] = $filter->path.$categoryParent->path.'/';
        
        $breadcrumbs[] = $title;
        Yii::app()->params['breadcrumbs'] = $breadcrumbs;
        // end Breadcrubs
        
        Yii::app()->params['meta_title'] = Yii::app()->params['meta_description'] = $title;
        
        $response = '';
        $temp = array();
        
        $group = ProductGroupFilter::model()->findByPk($filterId);
        $groups = array($group->group_id);
        if(!$group->isLeaf()) {
            $children = $group->children()->findAll();
            foreach($children as $child) {
                if(!$child->isLeaf()) {
                    $subChildren = $child->children()->findAll();
                    foreach($subChildren as $subChild) {
                        $groups[] = $subChild->group_id;
                    }
                } else {
                    $groups[] = $child->group_id;
                }
            }
        }
        
        $modellineIds = Yii::app()->db->createCommand()
            ->selectDistinct('model_line_id')
            ->from('product_in_model_line m')
            ->join('product p', 'p.id = m.product_id')
            ->where(array('and', 'p.published = 1', array('in', 'p.product_group_id', $groups)))
            ->queryColumn()
        ;
        
        $parts = array_chunk($modellineIds, 900);
        foreach($parts as $part) {
            $criteria = new CDbCriteria;
            $criteria->compare('category_id', $categoryId);
            $criteria->addInCondition('id', $part);
            $modellines = ModelLine::model()->findAll($criteria);

            $top_makers = Yii::app()->db->createCommand()
                            ->selectDistinct('maker_id')
                            ->from('category_makers_top')
                            ->where('category_id = :category_id', array(':category_id' => $categoryId))
                            ->queryColumn();

            foreach($modellines as $model) {
                $parent = $model->parent()->find();
                $brand = EquipmentMaker::model()->findByPk($model->maker_id);
                $temp[$brand->name][$parent->name]['name'] = $parent->name;
                $temp[$brand->name][$parent->name]['path'] = $filter->path.$category->path.$brand->path.$parent->path.'/';
                $temp[$brand->name][$parent->name]['catalog_top']=$parent->catalog_top;
                if(isset($top_makers)&&!empty($top_makers)&&in_array($brand->id, $top_makers)){
                            $temp_top[$brand->name][$parent->name]['name'] = $parent->name;
                            $temp_top[$brand->name][$parent->name]['path'] = $filter->path.$category->path.$brand->path.$parent->path.'/';
                            $temp_top[$brand->name][$parent->name]['catalog_top']=$parent->catalog_top;
                  }   
            }
        }
        $response_all=$this->printHierarchy($temp, false);
        //screening single quotes in modelline name
        $response_all=str_replace("'", "&prime;", $response_all);
        if(isset($temp_top)){
            $response_top=$this->printHierarchy($temp_top, true);
            $response_top=str_replace("'", "&prime;", $response_top);
            $this->render('modelline', array(
                'response_all' => $response_all,
                'response_top'=>$response_top,
                'title' => $title
            ));
        }
        else{
            $this->render('modelline', array(
            'response_all' => $response_all,
            'title' => $title
            ));
        }
        
        
    }
    
    /*Print tree for modellines*/
    private function printHierarchy($result_array, $makers_top){
        $dependency = new CDbCacheDependency('SELECT MAX(update_time) FROM model_line');
        if(!empty($result_array)) {
            ksort($result_array);
            $allKeys = array_keys($result_array);
            
            $count = count($result_array);
            $half = ceil($count/2);

            $response = '<table cellspacing="0" cellpadding="0" border="0"><tbody>';
            for($index = 0; $index < $half; $index++) {
                $response .= '<tr>';
                $response .= '<td width="50%" valign="top">';

                $elementName = $allKeys[$index];
                $response .= $this->setModelline($elementName, $result_array[$elementName]);

                $response .= '</td>';
                if(($index + $half) < $count) {
                    $response .= '<td width="50%" valign="top">';

                    $elementName = $allKeys[$index + $half];
                    $response .= $this->setModelline($elementName, $result_array[$elementName]);

                    $response .= '</td>';
                }
                $response .= '</tr>';
            }
            if($makers_top==true){$response .= '<tr><td colspan="2"><span class="link-brands">Показать всех производителей...</span></td></tr>';}
            $response .= '</tbody></table>';
            return $response;
       }
    }
    
    /* Show page with brands and models */
    
    public function actionBrand($categoryId, $groupId, $brandId)
    {
        $response = '';
        
        // Breadcrumbs
        $filter = ProductGroupFilter::model()->findByPk($groupId);
        $breadcrumbs[$filter->name] = $filter->path.'/';
        
        $category = Category::model()->findByPk($categoryId);
        $categoryParent = $category->parent()->find();
        $breadcrumbs[$categoryParent->name] = $filter->path.$categoryParent->path.'/';
        $breadcrumbs[$category->name] = $filter->path.$category->path.'/';
        
        $brand = EquipmentMaker::model()->findByPk($brandId);
        $title = $brand->name;
        
        $breadcrumbs[] = $title;
        Yii::app()->params['breadcrumbs'] = $breadcrumbs;
        // end Breadcrubs       
        
        $groups = array($filter->group_id);
        if(!$filter->isLeaf()) {
            $children = $filter->children()->findAll();
            foreach($children as $child) {
                $groups[] = $child->group_id;
                $subchildren = $child->children()->findAll();
                foreach($subchildren as $subchild){
                    $groups[] = $subchild->group_id;
                }
            }
        }
        
        $modellineIds = Yii::app()->db->createCommand()
            ->selectDistinct('model_line_id')
            ->from('product_in_model_line m')
            ->join('product p', 'p.id = m.product_id')
            ->where(array('and', 'p.published = 1', array('in', 'p.product_group_id', $groups)))
            ->queryColumn()
        ;
        
        $parts = array_chunk($modellineIds, 900);
        $temp = array();
        
        foreach($parts as $part) {
            $criteria = new CDbCriteria;
            $criteria->compare('category_id', $categoryId);
            $criteria->addCondition('maker_id = '.$brandId);
            $criteria->addInCondition('id', $part);
            $modellines = ModelLine::model()->findAll($criteria);

            foreach($modellines as $model) {
                $parent = $model->parent()->find();
                $temp[$parent->name] = $filter->path.$category->path.$brand->path.$parent->path.'/';
            }
        }
        
        if(!empty($temp)) {
            ksort($temp);
            $allKeys = array_keys($temp);
            
            $count = count($temp);
            $half = ceil($count/2);

            $response = '<table cellspacing="0" cellpadding="0" border="0"><tbody>';
            for($index = 0; $index < $half; $index++) {
                $response .= '<tr>';
                $response .= '<td width="50%" valign="top">';

                $elementName = $allKeys[$index];
                $response .= $this->setModel($elementName, $temp[$elementName]);

                $response .= '</td>';
                if(($index + $half) < $count) {
                    $response .= '<td width="50%" valign="top">';

                    $elementName = $allKeys[$index + $half];
                    $response .= $this->setModel($elementName, $temp[$elementName]);

                    $response .= '</td>';
                }
                $response .= '</tr>';
            }
            $response .= '</tbody></table>';   
        }
        
        Yii::app()->params['meta_title'] = Yii::app()->params['meta_description'] = $title;
        
        $this->render('brand', array(
            'response' => $response,
            'title' => $title
        ));
    }
    // show all products in modelline (in all models together)
    public function actionModelline($categoryId, $groupId, $brandId, $modellineId)
    {
        set_time_limit(0);
        // Breadcrumbs
        $filter = ProductGroupFilter::model()->findByPk($groupId);
        $breadcrumbs[$filter->name] = $filter->path.'/';
        
        $category = Category::model()->findByPk($categoryId);
        $categoryParent = $category->parent()->find();
        $breadcrumbs[$categoryParent->name] = $filter->path.$categoryParent->path.'/';
        $breadcrumbs[$category->name] = $filter->path.$category->path.'/';
        
        $brand = EquipmentMaker::model()->findByPk($brandId);
        $breadcrumbs[$brand->name] = $filter->path.$category->path.$brand->path.'/';
        
        $modelline = ModelLine::model()->findByPk($modellineId);
        $title = $modelline->name;
        
        $breadcrumbs[] = $title;
        Yii::app()->params['breadcrumbs'] = $breadcrumbs;
        // end Breadcrubs
        
        $products = new Product;
        $products->unsetAttributes();
        
        if (isset($_GET['Product']))
            $products->attributes = $_GET['Product'];
        
        $products->modelLineId = $modellineId;
        $products->product_group_id = $filter->group_id;
        
        $result = $products->searchGroupfilter();
        $result2 = $this->getAllBrands($result['brandCriteria']);
        $brandFilter = $result2['makers'];
        $groupsFilter = $result2['groups'];
        //$groupsFilter = $this->getAllGroups($result['groups']);
        
        $dataProvider = $result['dataProvider'];
        $dataProvider->sort = array(
            'defaultOrder' => 'count desc, name'
        );
        $dataProvider->pagination = array(
            'pageVar' => 'page',
            'pageSize' => 10,
        );
        
        $titleH1 = 'Запасные части для '.$title;
        Yii::app()->params['meta_title'] = Yii::app()->params['meta_description'] = $titleH1;
        
        if (!isset($_GET['ajax'])) {
            $this->render('model', array(
                'products' => $products,
                'brand' => $brandFilter,
                'groups' => $groupsFilter,
                'dataProvider' => $dataProvider,
                'titleH1' => $titleH1,
                'titleH2' => $title
            )); 
        } else {
            $this->renderPartial('model', array(
                'products' => $products,
                'brand' => $brandFilter,
                'groups' => $groupsFilter,
                'dataProvider' => $dataProvider,
                'titleH1' => $titleH1,
                'titleH2' => $title
            )); 
        }
    }
    
    private function getAllBrands($criteria)
    {
        $data = $dataGroups = $temp = $tempGroups = array();
        
        $products = Product::model()->findAll($criteria);
        foreach($products as $product){
            $temp[] = $product->product_maker_id;
            $tempGroups[] = $product->product_group_id;
        }
        
        $crit = new CDbCriteria();
        $crit->distinct = true;
        $crit->select = '*';
        $crit->condition = 'external_id IS NOT NULL';
        $crit->order = 'name';
        $crit->addInCondition('id', $temp);
        $makers = ProductMaker::model()->findAll($crit);

        foreach($makers as $maker) {
            $data[$maker->id] = $maker->name;
        }
        
        $criteria = new CDbCriteria();
        $criteria->distinct = true;
        $criteria->select = '*';
        $criteria->condition = 'external_id IS NOT NULL';
        $criteria->order = 'name';
        $criteria->addInCondition('id', $tempGroups);
        $groups = ProductGroup::model()->findAll($criteria);

        foreach($groups as $group) {
            $dataGroups[$group->id] = $group->name;
        }

        return array(
            'makers' => $data,
            'groups' => $dataGroups
        );
    }
    
    private function getAllGroups($allGroups)
    {
        $data = $temp = array();
        //echo 'where groups in $allGroups';
        //exit;
        $criteria = new CDbCriteria();
        $criteria->addInCondition('id', $allGroups);
        $criteria->order = 'name';
        $groups = ProductGroup::model()->findAll($criteria);
        
        foreach($groups as $group) {
            if($group->isLeaf()){
                $data[$group->id] = $group->name;
            }
            //echo $group->id.'; '.$group->name.'; '.$group->level.'<br>';
        }
        
        //exit;
        
//        foreach($groups as $group) {
//            $ancestors = $group->ancestors()->findAll();
//            if(!empty($ancestors)) {
//                $count = 1;
//                $groupParent = $group->parent()->find();
//                if($groupParent->level == 1) {
//                    $data[$group->name][$group->id] = $group->name.' - '.$group->id;
//                } else {
//                    foreach($ancestors as $ancestor) {
//                        $parent = $ancestor->parent()->find();
//                        if(!empty($parent)) {
//                            if(count($ancestors) == 2) {
//                                $data[$ancestor->name][$group->id] = $group->name.' - '.$group->id;
//                            } else if(count($ancestors) == 3 && $parent->level > 1) {
//                                $data[$parent->name][$ancestor->id] = $ancestor->name.' - '.$ancestor->id;
//                            } else if(count($ancestors) == 4 && $parent->level > 2) {
//                                $parent2 = $parent->parent()->find();
//                                if($parent2->level > 1){
//                                    $data[$parent2->name][$parent->id] = $parent->name.' - '.$parent->id;
//                                }
//                            }
//                        }
//                        $count++;
//                    }
//                }
//            }
//        }

        return $data;
    }
    
    public function getCategories($filter)
    {
        $response = '';
        
        $temp = $this->getSubcategories($filter);
        
        ksort($temp);
        $allKeys = array_keys($temp);
        
        $count = count($temp);
        $half = ceil($count/2);
        
        if(!empty($temp)) {
            $response = '<table cellspacing="0" cellpadding="0" border="0"><tbody>';
            for($index = 0; $index < $half; $index++) {                    
                $response .= '<tr>';
                $response .= '<td width="50%" valign="top">';

                $elementName = $allKeys[$index];
                $response .= $this->setChildren($elementName, $temp[$elementName], $filter->path);

                $response .= '</td>';
                if(($index + $half) < $count) {
                    $response .= '<td width="50%" valign="top">';

                    $elementName = $allKeys[$index + $half];
                    $response .= $this->setChildren($elementName, $temp[$elementName], $filter->path);

                    $response .= '</td>';
                }
                $response .= '</tr>';
            }
            $response .= '</tbody></table>';
        }
        
        return $response;
    }
    
    public function getSubcategories($filter, $subcategoryId = null)
    {
        $temp = array();
        
        $groups = array($filter->group_id);
        if(!$filter->isLeaf()) {
            $children = $filter->children()->findAll();
            foreach($children as $child) {
                $groups[] = $child->group_id;
            }
        }

        $modellines = Yii::app()->db->createCommand()
            ->selectDistinct('model_line_id')
            ->from('product_in_model_line m')
            ->join('product p', 'p.id = m.product_id')
            ->where(array('and', 'p.published = 1', array('in', 'p.product_group_id', $groups)))
            ->queryColumn()
        ;

        $subcategoryIds = Yii::app()->db->createCommand()
            ->selectDistinct('category_id')
            ->from('model_line')
            ->where(array('in', 'id', $modellines))
            ->queryColumn()
        ;
        
        foreach($subcategoryIds as $id) {
            $subcategory = Category::model()->findByPk($id);
            $parent = $subcategory->ancestors()->find('level = 2');
            if(empty($subcategoryId) || $parent->id == $subcategoryId) {    
                $temp[$parent->name][$subcategory->name]['name'] = $subcategory->name;
                $temp[$parent->name][$subcategory->name]['path'] = $subcategory->path;
            }
        }
        
        return $temp;
    }
    
    public function setChildren($categoryName, $elements, $path)
    {
        ksort($elements);
        $response = '<ul class="accordion modelline">'.
                     '<li>'.
                       '<a href="#" class="sub-title">'.$categoryName.'</a>'.
                       '<ul>'
        ;
        
        foreach($elements as $key=>$element) {
           $response .= '<li><a href="'.$path.$element['path'].'/" class="sub-child-title">'.$element['name'].'</a></li>';
        }

        $response .= '</ul>'.
                  '</li>'.
                '</ul>'
        ;
        return $response;
    }
    
    public function setModelline($categoryName, $modellines)
    {
        $response = '<ul class="accordion modelline">'.
                     '<li>'.
                       '<a href="#" class="sub-title">'.$categoryName.'</a>'.
                       '<ul>'
        ;
        $models_non_top=false;
        foreach($modellines as $key=>$modelline) {
            $sign_top=($modelline['catalog_top']==1)?"top":"non_top";
            if ($sign_top=="non_top"){
               $models_non_top=true; 
            }
            $response .= '<li class="'.$sign_top.'"><a href="'.$modelline['path'].'" class="sub-child-title">'.$modelline['name'].'</a></li>';
            //$response .= '<li><a href="#" class="sub-child-title">'.$modelline['name'].'</a></li>';
        }
        //link View all
        if ($models_non_top) {
            $response .= '<li><span class="link-modellines">Показать все модельные ряды...</span></li>';
        }

        $response .= '</ul>'.
                  '</li>'.
                '</ul>'
        ;
        return $response;
    }
    
    public function setModel($categoryName, $path)
    {
        $response = '<ul class="accordion modelline">'.
                     '<li>'.
                       '<a href="'.$path.'" class="sub-title">'.$categoryName.'</a>'.
                     '</li>'.
                '</ul>'
        ;
        return $response;
    }
}