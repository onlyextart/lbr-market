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
    
    public function getCategories($filter)
    {
        $response = '';
        
        $temp = $this->getSubcategories($filter);
        
        ksort($temp);
        $allKeys = array_keys($temp);
        
        $count = count($temp);
        $half = ceil($count/2);
        
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

        return $response;
    }
    
    public function getSubcategories($filter, $subcategoryId = null)
    {
        $temp = array();
        $modellines = Yii::app()->db->createCommand()
            ->selectDistinct('model_line_id')
            ->from('product_in_model_line m')
            ->join('product p', 'p.id = m.product_id')
            ->where('p.published = 1 and p.product_group_id=:id', array(':id'=>$filter->group_id))
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
        
//        echo '<pre>';
//        var_dump($temp);
//        exit;
        
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
        
    public function actionModellines($categoryId, $groupId)
    {
        $response = '';
        
        $group = ProductGroup::model()->findByPk($groupId);
        $groups = array($groupId);
        if(!$group->isLeaf()) {
            $ancestors = $group->ancestors()->findAll();
            foreach($ancestors as $ancestor) {
                $groups[] = $ancestor->id;
            }
        }
        
        $modellineIds = Yii::app()->db->createCommand()
            ->selectDistinct('model_line_id')
            ->from('product_in_model_line m')
            ->join('product p', 'p.id = m.product_id')
            ->where(array('and', 'p.published = 1', array('in', 'p.product_group_id', $groups)))
            ->queryColumn()
        ;
        
        $criteria = new CDbCriteria;
        $criteria->compare('category_id', $categoryId);
        $criteria->addInCondition('id', $modellineIds);
        $modellines = ModelLine::model()->findAll($criteria);

        // Breadcrumbs
        $filter = ProductGroupFilter::model()->findByAttributes(array('group_id' => $group->id));
        $breadcrumbs[$filter->name] = $filter->path.'/';
        
        $category = Category::model()->findByPk($categoryId);
        $title = $category->name;
        
        $categoryParent = $category->parent()->find();
        $breadcrumbs[$categoryParent->name] = $filter->path.$categoryParent->path.'/';
        
        $breadcrumbs[] = $title;
        Yii::app()->params['breadcrumbs'] = $breadcrumbs;
        // end Breadcrubs
        
        $temp = array();
        foreach($modellines as $model) {
            $parent = $model->parent()->find();
            $brand = EquipmentMaker::model()->findByPk($model->maker_id);
            $temp[$brand->name][$parent->name][$model->id]['name'] = $model->name;
            $temp[$brand->name][$parent->name][$model->id]['path'] = $filter->path.$category->path.$brand->path.$model->path.'/';
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
    
    public function setModelline($categoryName, $modellines)
    {
        $response = '<ul class="accordion modelline">'.
                     '<li>'.
                       '<a href="#" class="sub-title">'.$categoryName.'</a>'.
                       '<ul>'
        ;
        
        foreach($modellines as $key=>$modelline) {
           $response .= '<li><a href="#" class="sub-child-title">'.$key.'</a><ul>';
           foreach($modelline as $model) {
               $response .= '<li><a href="'.$model['path'].'" class="modelline-child">'.$model['name'].'</a></li>';
           }
           $response .= '</ul></li>';
        }

        $response .= '</ul>'.
                  '</li>'.
                '</ul>'
        ;
        return $response;
    }
    
    /* Show page with brands and models */
    
    public function actionModel($categoryId, $groupId, $modelId, $brandId)
    {
        $products = new Product;
        $products->unsetAttributes();
        
        if (isset($_GET['Product']))
            $products->attributes = $_GET['Product'];
        
        $products->modelLineId = $modelId;
        $products->product_group_id = $groupId;
        
        $result = $products->searchEvent();
        $dataProvider = $result['dataProvider'];
        $dataProvider->pagination = array(
            'pageVar' => 'page',
            'pageSize' => 10,
        );
        
        // Breadcrumbs
        $filter = ProductGroupFilter::model()->findByAttributes(array('group_id' => $groupId));
        $breadcrumbs[$filter->name] = $filter->path.'/';
        
        $category = Category::model()->findByPk($categoryId);        
        $categoryParent = $category->parent()->find();
        $breadcrumbs[$categoryParent->name] = $filter->path.$categoryParent->path.'/';
        $breadcrumbs[$category->name] = $filter->path.$category->path.'/';
        
        $brand = EquipmentMaker::model()->findByPk($brandId);
        $breadcrumbs[$brand->name] = $filter->path.$category->path.$brand->path.'/';
        
        $model = ModelLine::model()->findByPk($modelId);
        $modelline = $model->parent()->find();
        $breadcrumbs[$modelline->name] = $filter->path.$category->path.$brand->path.$modelline->path.'/';
        $title = $model->name;
                
        $breadcrumbs[] = $title;
        Yii::app()->params['breadcrumbs'] = $breadcrumbs;
        // end Breadcrubs
        
        $this->render('model', array(
            'products' => $products,
            'dataProvider' => $dataProvider,
            'title' => $title
        ));
    }
}