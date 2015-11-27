<?php
class GroupfilterController extends Controller
{
    /* Show page with categories and subcategories */
    
    public function actionIndex($id)
    {
        $model = ProductGroupFilter::model()->findByPk($id);
        if(!$model)
            throw new CHttpException(404, 'Товар не найден');
        $title = $model->name;
        
        $response = $this->getCategory($model);
        
        $breadcrumbs[] = $title;
        Yii::app()->params['breadcrumbs'] = $breadcrumbs;
        
        $this->render('index', array(
            'response' => $response,
            'title' => $title
        ));
    }
    
    public function getCategory($model)
    {
        $response = '';
        $modellines = Yii::app()->db->createCommand()
            ->selectDistinct('model_line_id')
            ->from('product_in_model_line m')
            ->join('product p', 'p.id = m.product_id')
            ->where('p.published = 1 and p.product_group_id=:id', array(':id'=>$model->group_id))
            ->queryColumn()
        ;

        $subcategoryIds = Yii::app()->db->createCommand()
            ->selectDistinct('category_id')
            ->from('model_line')
            ->where(array('in', 'id', $modellines))
            ->queryColumn()
        ;
        
//        $criteria = new CDbCriteria;
//        $criteria->addInCondition('id', $subcategoryIds);
//        $criteria->order = 'name';
//        $subcategories = Category::model()->findAll($criteria);
//        
//        foreach($subcategories as $subcategory) {
//            $parent = $subcategory->ancestors()->find('level = 2');
//            $temp[$parent->name][$subcategory->name]['name'] = $subcategory->name;
//            $temp[$parent->name][$subcategory->name]['path'] = $subcategory->path;
//        }
        
        $temp = array();
        foreach($subcategoryIds as $id) {
            $subcategory = Category::model()->findByPk($id);
            $parent = $subcategory->ancestors()->find('level = 2');
            $temp[$parent->name][$subcategory->name]['name'] = $subcategory->name;
            $temp[$parent->name][$subcategory->name]['path'] = $subcategory->path;
        }
        
        ksort($temp);
        $allKeys = array_keys($temp);
        
        $count = count($temp);
        $half = ceil($count/2);
        
        $response = '<table cellspacing="0" cellpadding="0" border="0"><tbody>';
        for($index = 0; $index < $half; $index++) {                    
            $response .= '<tr>';
            $response .= '<td width="50%" valign="top">';
            
            $elementName = $allKeys[$index];
            $response .= $this->setChildren($elementName, $temp[$elementName], $model->path);
            
            $response .= '</td>';
            if(($index + $half) < $count) {
                $response .= '<td width="50%" valign="top">';
                
                $elementName = $allKeys[$index + $half];
                $response .= $this->setChildren($elementName, $temp[$elementName], $model->path);
                
                $response .= '</td>';
            }
            $response .= '</tr>';
        }
        $response .= '</tbody></table>';

        return $response;
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
    
    /* Show page with brands and models */
        
    public function actionModels($categoryId, $groupId)
    {
        $response = $title = '';
        
        $model = ProductGroup::model()->findByPk($groupId);
        $groups = array($groupId);
        if(!$model->isLeaf()) {
            $ancestors = $model->ancestors()->findAll();
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

        $temp = array();
        foreach($modellines as $model) {
            $parent = $model->parent()->find();
            $brand = EquipmentMaker::model()->findByPk($model->maker_id);
            $temp[$brand->name][$parent->name][] = $model->name;
        }
        
        //////////////////////
        if(!empty($temp)) {
            ksort($temp);


            foreach($temp as $brand => $modelline){

            }

            $response = '<table cellspacing="0" cellpadding="0" border="0"><tbody>';
            for($index = 0; $index < $half; $index++) {                    
                $response .= '<tr>';
                $response .= '<td width="50%" valign="top">';

                $elementName = $allKeys[$index];
                $response .= $this->setChildren($elementName, $temp[$elementName], $model->path);

                $response .= '</td>';
                if(($index + $half) < $count) {
                    $response .= '<td width="50%" valign="top">';

                    $elementName = $allKeys[$index + $half];
                    $response .= $this->setChildren($elementName, $temp[$elementName], $model->path);

                    $response .= '</td>';
                }
                $response .= '</tr>';
            }
            $response .= '</tbody></table>';   
        }
        
        
        echo '<pre>';
        var_dump($temp);
        exit;
        
        $this->render('model', array(
            'response' => $response,
            'title' => $title
        ));
    }
}