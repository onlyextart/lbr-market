<?php
class MenuChoice extends CWidget
{
    public function init()
    {
        $groups = $types = $makers = $temp = $makersAll = array();
        $filterCategory = $filterMaker = null;
        
        $model = new Category;
        $mainRoot = $model->roots()->find();
        $dependency = new CDbCacheDependency('SELECT MAX(update_time) FROM model_line');
        $category_dependency = new CDbCacheDependency('SELECT MAX(update_time) FROM category');
        $equipmentMakerDependency = new CDbCacheDependency('SELECT MAX(update_time) FROM equipment_maker');
        //echo 'type = '.Yii::app()->params['currentType'];
        
        // формируем меню "По типу техники"
        if(!empty(Yii::app()->params['currentMaker'])) {
            $criteria = new CDbCriteria();
            $criteria->distinct = true;
            $criteria->condition = 'maker_id = '.Yii::app()->params['currentMaker'].' and level=3';      
            //$criteria->select = 'category_id';
            $models = ModelLine::model()->cache(1000, $dependency)->findAll($criteria);
            
            $flag = false;
            foreach($models as $model) {
                if($model->isLeaf()) {
                    //echo '<pre>';
                    //echo $model->id.'<br>';
                    $parent = Category::model()->cache(1000, $category_dependency)->findByPk($model['category_id']);
                    if(!empty($parent)){
                        if($parent->parent()->find()){
                            $parent = $parent->parent()->find();
                            $temp[$parent->id]['name'] = $parent->name;                        
                            $temp[$parent->id]['path'] = $parent->path; 
                        }
                    };
                    
                    /*
                    $ancestors = Category::model()->findByPk($model['category_id'])->ancestors()->findAll();
                    $count = 0;
                    foreach($ancestors as $ancestor) {
                        if($ancestor->id == Yii::app()->params['currentType']) $flag = true;
                        if(!array_key_exists($ancestor->id, $temp)){
                            if($count && $ancestor->published) {
                                $temp[$ancestor->id]['name'] = $ancestor->name;                        
                                $temp[$ancestor->id]['path'] = $ancestor->path;                        
                            }
                        }
                        $count++;
                    }*/
                    
                    //echo '<pre>';
                    //var_dump($temp);exit;
                    
                    foreach($temp as $key=>$value) {
                        //preg_match('/\d{2,}\./i', $value['name'], $result);
                        //$types[$key]['name'] = trim(substr($value['name'], strlen($result[0])));
                        /*
                        $types[$key]['name'] = $value['name'];
                        $types[$key]['id'] = $key;
                        $types[$key]['path'] = $value['path'];
                        */
                        $types[$key]['name'] = $value['name'];
                        $types[$key]['id'] = $key;
                        $types[$key]['path'] = $value['path'];
                    }
                }
                 
            }
            
            //exit;
            //if($flag == false) Yii::app()->session['category'] = null;
        } else {
            if(!empty($mainRoot)) {
                $roots = $mainRoot->children()->findAll('published=:published', array(':published' => true));
                foreach($roots as $k=>$root) {
                    //$children = $root->children()->findAll('published=:published', array(':published' => true));
                    //if(!empty($children)){
                        //foreach($children as $child) {
                        //   $types[$k]['children'][]['name'] = $child->name;
                        //}   
                    //}
                    $title = $root->name;
                    //preg_match('/\d{2,}\./i', $title, $result);
                    //if(!empty($types[$k]['children'])) $types[$k]['name'] = trim(substr($title, strlen($result[0])));
                    $types[$root->id]['name'] = trim($title);
                    $types[$root->id]['id'] = $root->id;
                    $types[$root->id]['path'] = $root->path;
                }
            }
        }
        ksort($types);
        //echo '<pre>';
        //var_dump($types);exit;
        
        // формируем меню "По производителю техники"
        if(!empty(Yii::app()->params['currentType'])) {
            $children = Category::model()->cache(1000, $category_dependency)->findByPk(Yii::app()->params['currentType'])->children()->findAll();
            foreach($children as $child) {
                $criteria = new CDbCriteria();
                $criteria->distinct = true;
                $criteria->condition = 'category_id = '.$child->id;      
                $criteria->select = 'maker_id';
                $models = ModelLine::model()->cache(1000, $dependency)->findAll($criteria);
                
                foreach($models as $model) {
                    $t[] = $model['maker_id'];
                }
                
                $crit = new CDbCriteria();
                $crit->addInCondition('id', $t);
                $makersAll = EquipmentMaker::model()->cache(1000, $equipmentMakerDependency)->findAll($crit);
            }
        } else {
            $makersAll = EquipmentMaker::model()->cache(1000, $equipmentMakerDependency)->findAll();
        }

        if(count($makersAll)){
            foreach($makersAll as $maker) {
                $makers[$maker->id]['name'] = $maker->name;
                $makers[$maker->id]['path'] = $maker->path;
                $makers[$maker->id]['id'] = $maker->id;
            }
        }
        
        // название выбранных фильтров
        if(!empty(Yii::app()->params['currentType'])) {
            //echo '= '; Yii::app()->params['currentType']; exit;
           $filterCategoryModel = Category::model()->cache(1000, $category_dependency)->findByPk(Yii::app()->params['currentType']);
           $filterCategoryName = $filterCategoryModel->name;
           //preg_match('/\d{2,}\./i', $filterCategoryName, $result);
           $filterCategory['name'] = trim($filterCategoryName);
           $filterCategory['id'] = $filterCategoryModel->id;
           $filterCategory['path'] = $filterCategoryModel->path;
        }

        if(!empty(Yii::app()->params['currentMaker'])){
            $filterMakerModel = EquipmentMaker::model()->cache(1000, $equipmentMakerDependency)->findByPk(Yii::app()->params['currentMaker']);
            $filterMaker['name'] = $filterMakerModel->name;
            $filterMaker['id'] = $filterMakerModel->id;
            $filterMaker['path'] = $filterMakerModel->path;
        }
        
        // формируем меню "Продукты в группах"
        $groupsRoot = ProductGroupFilter::model()->findByAttributes(array('level'=>1));
        if(!empty($groupsRoot)) {
            $groups = $groupsRoot->children()->findAll();
        }
        
        $this->render('index',array(
            'groups' => $groups,
            'types'=>$types, 
            'makers'=>$makers, 
            'filterMaker' => $filterMaker, 
            'filterCategory' => $filterCategory
        ));
    }
}
