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
        
//        $productIds = Yii::app()->db->createCommand()
//            ->selectDistinct('id')
//            ->from('product')
//            ->where(array('and', 'published = 1', array('in', 'product_group_id', $groups)))
//            ->queryColumn()
//        ;
//        
//        $modellineIds = Yii::app()->db->createCommand()
//            ->selectDistinct('model_line_id')
//            ->from('product_in_model_line')
//            ->where(array('in', 'product_id', $productIds))
//            //->limit(800)
//            ->queryColumn()
//        ;
        
        ///////////////////////////////////////////////////

        
        $modellineIds = Yii::app()->db->createCommand()
            ->selectDistinct('model_line_id')
            ->from('product_in_model_line m')
            ->join('product p', 'p.id = m.product_id')
            ->where(array('and', 'p.published = 1', array('in', 'p.product_group_id', $groups)))
            ->queryColumn()
        ;
        
        
//        
//        echo '<pre>';
//        var_dump($groups);
//        exit;
        
        $parts = array_chunk($modellineIds, 900);
        foreach($parts as $part) {
//            echo '<pre>';
//            var_dump($modellineIds); exit;

            $criteria = new CDbCriteria;
            $criteria->compare('category_id', $categoryId);
            //$criteria->addInCondition('id', $modellineIds);
            $criteria->addInCondition('id', $part);
            $modellines = ModelLine::model()->findAll($criteria);


            foreach($modellines as $model) {
                $parent = $model->parent()->find();
                $brand = EquipmentMaker::model()->findByPk($model->maker_id);
                $temp[$brand->name][$parent->name]['name'] = $parent->name;
                $temp[$brand->name][$parent->name]['path'] = $filter->path.$category->path.$brand->path.$parent->path.'/';
//                $temp[$brand->name][$parent->name][$model->id]['name'] = $model->name;
//                $temp[$brand->name][$parent->name][$model->id]['path'] = $filter->path.$category->path.$brand->path.$model->path.'/';
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
                $response .= $this->setModelline($elementName, $temp[$elementName]);

                $response .= '</td>';
                if(($index + $half) < $count) {
                    $response .= '<td width="50%" valign="top">';

                    $elementName = $allKeys[$index + $half];
                    $response .= $this->setModelline($elementName, $temp[$elementName]);

                    $response .= '</td>';
                }
                $response .= '</tr>';
            }
            $response .= '</tbody></table>';   
        }
        
        $this->render('modelline', array(
            'response' => $response,
            'title' => $title
        ));
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
            }
        }
        
        $modellineIds = Yii::app()->db->createCommand()
            ->selectDistinct('model_line_id')
            ->from('product_in_model_line m')
            ->join('product p', 'p.id = m.product_id')
            ->where(array('and', 'p.published = 1', array('in', 'p.product_group_id', $groups)))
            ->queryColumn()
        ;
        
//        echo '<pre>';
//        var_dump($modellineIds); 
//        exit;
        
        $parts = array_chunk($modellineIds, 900);
        $temp = array();
        foreach($parts as $part) {
            $criteria = new CDbCriteria;
            $criteria->compare('category_id', $categoryId);
            $criteria->addCondition('maker_id = '.$brandId);
            //$criteria->addInCondition('id', $modellineIds);
            $criteria->addInCondition('id', $part);
            $modellines = ModelLine::model()->findAll($criteria);


            foreach($modellines as $model) {
                $parent = $model->parent()->find();
                //$temp[$parent->name][$model->id]['name'] = $model->name;
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
        
        $this->render('brand', array(
            'response' => $response,
            'title' => $title
        ));
    }
    
    /* Show page with modelline */
//    public function actionModelline($categoryId, $groupId, $brandId, $modellineId)
//    {
//        $response = '';
//        
//        // Breadcrumbs
//        $filter = ProductGroupFilter::model()->findByPk($groupId);
//        $breadcrumbs[$filter->name] = $filter->path.'/';
//        
//        $category = Category::model()->findByPk($categoryId);
//        $categoryParent = $category->parent()->find();
//        $breadcrumbs[$categoryParent->name] = $filter->path.$categoryParent->path.'/';
//        $breadcrumbs[$category->name] = $filter->path.$category->path.'/';
//        
//        $brand = EquipmentMaker::model()->findByPk($brandId);
//        $breadcrumbs[$brand->name] = $filter->path.$category->path.$brand->path.'/';
//        
//        $modelline = Modelline::model()->findByPk($modellineId);
//        $title = $modelline->name;
//        
//        $breadcrumbs[] = $title;
//        Yii::app()->params['breadcrumbs'] = $breadcrumbs;
//        // end Breadcrubs
//        
//        $groups = array($filter->group_id);
//        if(!$filter->isLeaf()) {
//            $children = $filter->children()->findAll();
//            foreach($children as $child) {
//                $groups[] = $child->group_id;
//            }
//        }
//        
//        $modellineIds = Yii::app()->db->createCommand()
//            ->selectDistinct('model_line_id')
//            ->from('product_in_model_line m')
//            ->join('product p', 'p.id = m.product_id')
//            ->where(array('and', 'p.published = 1', array('in', 'p.product_group_id', $groups)))
//            ->queryColumn()
//        ;
//        
//        $currentModellineIds = array();
//        
//        $modellines = $modelline->children()->findAll();
//        foreach($modellines as $modelline) {
//            if(in_array($modelline->id, $modellineIds)) {
//                $currentModellineIds[] = $modelline->id;
//            }
//        }
//        
//        $criteria = new CDbCriteria;
//        $criteria->compare('category_id', $categoryId);
//        $criteria->addCondition('maker_id = '.$brandId);
//        $criteria->addInCondition('id', $currentModellineIds);
//        $modellines = ModelLine::model()->findAll($criteria);
//        
//        $temp = array();
//        foreach($modellines as $model) {
//            $temp[$model->id]['name'] = $model->name;
//            $temp[$model->id]['path'] = $filter->path.$category->path.$brand->path.$model->path.'/';
//        }
//        
//        if(!empty($temp)) {
//            ksort($temp);
//            $allKeys = array_keys($temp);
//            
//            $count = count($temp);
//            $half = ceil($count/2);
//
//            $response = '<table cellspacing="0" cellpadding="0" border="0"><tbody>';
//            for($index = 0; $index < $half; $index++) {
//                $response .= '<tr>';
//                $response .= '<td width="50%" valign="top">';
//
//                $element = $temp[$allKeys[$index]];
//                $response .= '<a href="'.$element['path'].'" class="sub-child-title">'.$element['name'].'</a>';
//
//                $response .= '</td>';
//                if(($index + $half) < $count) {
//                    $response .= '<td width="50%" valign="top">';
//
//                    $element = $temp[$allKeys[$index + $half]];
//                    $response .= '<a href="'.$element['path'].'" class="sub-child-title">'.$element['name'].'</a>';
//
//                    $response .= '</td>';
//                }
//                $response .= '</tr>';
//            }
//            $response .= '</tbody></table>';   
//        }
//        
//        $this->render('modelline', array(
//            'response' => $response,
//            'title' => $title
//        ));
//    }
    public function actionModelline($categoryId, $groupId, $brandId, $modellineId)
    {
        // Breadcrumbs
        $filter = ProductGroupFilter::model()->findByPk($groupId);
        $breadcrumbs[$filter->name] = $filter->path.'/';
        
        $category = Category::model()->findByPk($categoryId);
        $categoryParent = $category->parent()->find();
        $breadcrumbs[$categoryParent->name] = $filter->path.$categoryParent->path.'/';
        $breadcrumbs[$category->name] = $filter->path.$category->path.'/';
        
        $brand = EquipmentMaker::model()->findByPk($brandId);
        $breadcrumbs[$brand->name] = $filter->path.$category->path.$brand->path.'/';
        
        $modelline = Modelline::model()->findByPk($modellineId);
        $title = $modelline->name;
        
        $breadcrumbs[] = $title;
        Yii::app()->params['breadcrumbs'] = $breadcrumbs;
        // end Breadcrubs
        
        //-----------------------------------------------------------
        
//        echo $groupId.'<br>';
//        if(!empty($filter) && !$filter->isLeaf()) {
//            $children = $filter->children()->findAll();
//            foreach($children as $child) {
//                $groups[] = $child->group_id;
//                if(!$child->isLeaf()) {
//                    $subChildren = $child->children()->findAll();
//                    foreach($subChildren as $subChild) {
//                         $groups[] = $subChild->group_id;
//                    }
//                }
//            }
//        }
//        
//        echo '<pre>';
//        var_dump($groups);
//        exit;

        ///////////////////
        
        
        $products = new Product;
        $products->unsetAttributes();
        
        if (isset($_GET['Product']))
            $products->attributes = $_GET['Product'];
        
        $products->modelLineId = $modellineId;
        $products->product_group_id = $filter->group_id;
        
        $result = $products->searchGroupfilter();
        $dataProvider = $result['dataProvider'];
        $dataProvider->sort = array(
            'defaultOrder' => 'count desc, name'
        );
        $dataProvider->pagination = array(
            'pageVar' => 'page',
            'pageSize' => 10,
        );
        
        $this->render('model', array(
            'products' => $products,
            'dataProvider' => $dataProvider,
            'title' => $title
        ));   
    }
    
    /* Show page with brands and models */
    
//    public function actionModel($categoryId, $groupId, $modelId, $brandId)
//    {
//        $products = new Product;
//        $products->unsetAttributes();
//        
//        if (isset($_GET['Product']))
//            $products->attributes = $_GET['Product'];
//        
//        $products->modelLineId = $modelId;
//        $products->product_group_id = $groupId;
//        
//        $result = $products->searchEvent();
//        $dataProvider = $result['dataProvider'];
//        $dataProvider->sort = array(
//            'defaultOrder' => 'count desc, name'
//        );
//        $dataProvider->pagination = array(
//            'pageVar' => 'page',
//            'pageSize' => 10,
//        );
//        
//        // Breadcrumbs
//        $filter = ProductGroupFilter::model()->findByAttributes(array('group_id' => $groupId));
//        $breadcrumbs[$filter->name] = $filter->path.'/';
//        
//        $category = Category::model()->findByPk($categoryId);        
//        $categoryParent = $category->parent()->find();
//        $breadcrumbs[$categoryParent->name] = $filter->path.$categoryParent->path.'/';
//        $breadcrumbs[$category->name] = $filter->path.$category->path.'/';
//        
//        $brand = EquipmentMaker::model()->findByPk($brandId);
//        $breadcrumbs[$brand->name] = $filter->path.$category->path.$brand->path.'/';
//        
//        $model = ModelLine::model()->findByPk($modelId);
//        $modelline = $model->parent()->find();
//        $breadcrumbs[$modelline->name] = $filter->path.$category->path.$brand->path.$modelline->path.'/';
//        $title = $model->name;
//                
//        $breadcrumbs[] = $title;
//        Yii::app()->params['breadcrumbs'] = $breadcrumbs;
//        // end Breadcrubs
//        
//        $this->render('model', array(
//            'products' => $products,
//            'dataProvider' => $dataProvider,
//            'title' => $title
//        ));
//    }    
    
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
    // переделать по новое
    public function setModelline($categoryName, $modellines)
    {
        $response = '<ul class="accordion modelline">'.
                     '<li>'.
                       '<a href="#" class="sub-title">'.$categoryName.'</a>'.
                       '<ul>'
        ;
        
        foreach($modellines as $key=>$modelline) {
            $response .= '<li><a href="'.$modelline['path'].'" class="sub-child-title">'.$modelline['name'].'</a></li>';
        }

        $response .= '</ul>'.
                  '</li>'.
                '</ul>'
        ;
        return $response;
    }
    
//    public function setModelline($categoryName, $modellines)
//    {
//        $response = '<ul class="accordion modelline">'.
//                     '<li>'.
//                       '<a href="#" class="sub-title">'.$categoryName.'</a>'.
//                       '<ul>'
//        ;
//        
//        foreach($modellines as $key=>$modelline) {
//           $response .= '<li><a href="#" class="sub-child-title">'.$key.'</a><ul>';
//           foreach($modelline as $model) {
//               $response .= '<li><a href="'.$model['path'].'" class="modelline-child">'.$model['name'].'</a></li>';
//           }
//           $response .= '</ul></li>';
//        }
//
//        $response .= '</ul>'.
//                  '</li>'.
//                '</ul>'
//        ;
//        return $response;
//    }
    
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
    
//    public function setModel($categoryName, $models)
//    {
//        $response = '<ul class="accordion modelline">'.
//                     '<li>'.
//                       '<a href="#" class="sub-title">'.$categoryName.'</a>'
//                       //.'<ul>'
//        ;
//        
//        foreach($models as $model) {
//           $response .= '<li><a href="'.$model['path'].'" class="modelline-child">'.$model['name'].'</a></li>';
//        }
//
//        $response .= //'</ul>'.
//                  '</li>'.
//                '</ul>'
//        ;
//        return $response;
//    }
}