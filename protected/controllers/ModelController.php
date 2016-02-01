<?php

class ModelController extends Controller 
{
    public function actionShow($id) 
    {
        //set_time_limit(0);
        $hitProducts = array();
        
        $model = ModelLine::model()->findByPk($id);
        if(!$model)
            throw new CHttpException(404, 'Модель не найдена');
        
        $title = $model->name;
        Yii::app()->params['meta_title'] = Yii::app()->params['meta_description'] = $title;
        //$url = ModelLine::model()->getUrl($model->id);

        // random products for hit products            
        $hitProducts = $this->setHitProducts($id);
        
        $products = new Product;//('search');
        $products->unsetAttributes();
        
        if (isset($_GET['Product']))
            $products->attributes = $_GET['Product'];
        
        $products->modelLineId = $id;
        
        $result = $products->searchEvent();
        $dataProvider = $result['dataProvider'];
        $dataProvider->pagination = array(
            'pageVar' => 'page',
            'pageSize' => 10,
        );
        
        $filter = $this->getAllGroups($id, $products->product_maker_id);
        $brandFilter = $this->getAllBrands($result['brandCriteria']);
        
        // breadcrumbs
        $categoryDependency = new CDbCacheDependency('SELECT MAX(update_time) FROM category');
        $category = Category::model()->cache(1000, $categoryDependency)->findByPk($model->category_id);
        $categoryParent = $category->parent()->find();
        $parent = $model->parent()->find();
        $brand = EquipmentMaker::model()->findByPk($model->maker_id);
        
        $breadcrumbs[$categoryParent->name] = '/catalog'.$categoryParent->path.'/';
        $breadcrumbs[$category->name] = '/catalog'.$category->path.'/';
        $breadcrumbs[$brand->name] = '/catalog'.$category->path.$brand->path.'/';
        $breadcrumbs[$parent->name] = '/catalog'.$category->path.$brand->path.$parent->path.'/';
        $breadcrumbs[] = $title;
        Yii::app()->params['breadcrumbs'] = $breadcrumbs;
        // end breadcrumbs
        
        $titleH1 = 'Запасные части для '.$title;
        if(!empty($model->h1)) $titleH1 = $model->h1;
        
        $params = array(
            'products' => $products,
            'dataProvider' => $dataProvider,
            'title' => $title,
            'titleH1' => $brand->name.' '.$title,
            'titleH2' => $titleH1,
            'filter' => $filter,
            'brand' => $brandFilter,
            'hitProducts' => $hitProducts,
            'breadcrumbs' => $breadcrumbs
        );
        
        if (!isset($_GET['ajax']))
            $this->render('model', $params);
        else
            $this->renderPartial('model', $params);
    }
    
    private function getAllGroups($id, $brand)
    {
        $data = $temp = array();
        
        $criteria = new CDbCriteria;
        $criteria->distinct = true;
        $criteria->select = 'product.product_group_id as id';
        $criteria->join ='JOIN product ON product.id = t.product_id';
        $criteria->condition = 't.model_line_id=:model_line_id';
        $criteria->params = array(':model_line_id'=>$id);
        // !!!
        $criteria->addCondition('product.original = 1');
        
        if(!empty($brand)){
            $criteria->addCondition('product.product_maker_id = '.$brand);
        }
        
        $productsInModel = ProductInModelLine::model()->findAll($criteria);
        foreach($productsInModel as $productInModel){
            $temp[] = $productInModel['id'];
        }
        
        $crit = new CDbCriteria();
        $crit->distinct = true;
        $crit->select = '*';
        $crit->condition = 'external_id IS NOT NULL';
        $crit->addInCondition('id', $temp);
        $groups = ProductGroup::model()->findAll($crit);

        foreach($groups as $group) {
            $ancestors = $group->ancestors()->findAll();
            if(!empty($ancestors)) {
                $count = 1;
                $groupParent = $group->parent()->find();
                if($groupParent->level == 1){
                    $data[$group->name][$group->id] = $group->name;
                } else {
                    foreach($ancestors as $ancestor) {
                        $parent = $ancestor->parent()->find();
                        if(!empty($parent)) {
                            if(count($ancestors) == 2) {
                                $data[$ancestor->name][$group->id] = $group->name;
                            } else if(count($ancestors) == 3 && $parent->level > 1) {
                                $data[$parent->name][$ancestor->id] = $ancestor->name;
                            } else if(count($ancestors) == 4 && $parent->level > 2) {
                                $parent2 = $parent->parent()->find();
                                if($parent2->level > 1){
                                    $data[$parent2->name][$parent->id] = $parent->name;
                                }
                            }
                        }
                        $count++;
                    }
                }
            }
        }

        return $data;
    }
    
    private function getAllBrands($criteria)
    {
        $data = $temp = array();
        
        $productsInModel = ProductInModelLine::model()->findAll($criteria);
        foreach($productsInModel as $productInModel){
            $temp[] = $productInModel['id'];
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

        return $data;
    }
    
    private function setHitProducts($id)
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
                    'p.liquidity = "A" and p.image not NULL and p.published=:flag'.$sql,
                    'pm.model_line_id=:id'
               ), array(':id'=>$id,':flag'=>true)
            )
            ->queryColumn()
        ;

        $max = count($elements);
        $count = 4;
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
    }
}
