<?php
class SubcategoryController extends Controller
{        
    public function actionIndex($type = null, $maker = null)
    {
        $breadcrumbs = array();
        $category = $type;
        $title = $response = $topText = $bottomText = '';
        $modelline = array();
        $makerName = '';
        
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
                if(empty($maker)) {
                    $subcategories = $categoryRoot->children()->findAll(array('order'=>'name', 'condition'=>'published=:published', 'params'=>array(':published' => true)));
                } else {
                    $makerName = ' '.EquipmentMaker::model()->findByPk($maker)->name;
                    
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
                $brand = '';
                if(!empty(Yii::app()->params['currentMaker'])) $brand = EquipmentMaker::model()->findByPk(Yii::app()->params['currentMaker'])->path;
                
                $count = count($subcategories);
                $half = ceil($count/2);
                
                $response = '<table cellspacing="0" cellpadding="0" border="0"><tbody>';
                for($index = 0; $index < $half; $index++) {                    
                    $response .= '<tr>';
                    $response .= '<td width="50%" valign="top">';
                    $subcategory = $subcategories[$index];
                    $response .= '<a href="/catalog'.$subcategory['path'].$brand.'/">'.$subcategory['name'].'</a>';
                    $response .= '</td>';
                    if(($index + $half) < $count) {
                        $subcategory = $subcategories[$index + $half];
                        $response .= '<td width="50%" valign="top">';
                        $response .= '<a href="/catalog'.$subcategory['path'].$brand.'/">'.$subcategory['name'].'</a>';
                        $response .= '</td>';
                    }
                    $response .= '</tr>';
                }
                $response .= '</tbody></table>';
            }

            // bradcrumbs
            $breadcrumbs[] = $categoryRoot->name.' '.$makerName;
            
            $title = $categoryRoot->name.$makerName;
            if(!empty($categoryRoot->h1))
                $title = $categoryRoot->h1.$makerName;
            
            Yii::app()->params['meta_title'] = Yii::app()->params['meta_description'] = $title;
            if(!empty($categoryRoot->meta_title)) Yii::app()->params['meta_title'] = $categoryRoot->meta_title;            
            if(!empty($categoryRoot->meta_description)) Yii::app()->params['meta_description'] = $categoryRoot->meta_description;
            if(Yii::app()->params['showSeoTexts']) {
                if(!empty($categoryRoot->top_text)) $topText = $categoryRoot->top_text;
                if(!empty($categoryRoot->bottom_text)) $bottomText = $categoryRoot->bottom_text;
            }
        } else if(!empty($maker)) {
            /*$criteria = new CDbCriteria;
            $criteria->select = 'category_id';
            $criteria->distinct = true;
            $criteria->condition = 'maker_id=:maker';
            $criteria->params = array(':maker'=>$maker);
            //$depend = new CDbCacheDependency('SELECT MAX(update_time) FROM model_line');
            $models = ModelLine::model()->findAll($criteria);
            
            $temp = array();
            foreach($models as $model) {
                $temp[] = $model['category_id'];
            }*/
            
            $temp = Yii::app()->db->createCommand()
                ->selectDistinct('category_id')
                ->from('model_line')
                ->where('maker_id=:maker', array(':maker'=>$maker))
                ->queryColumn()
            ;
            
            $crit = new CDbCriteria();
            $crit->condition = 'published=:published';
            $crit->params = array(':published'=>true);
            $crit->addInCondition('id', $temp);
            $crit->order = 'name';
            $subcategories = Category::model()->findAll($crit);
            ///////////////////////////////////////////////////////////////////////////
            
            $count = 0;
            foreach($subcategories as $subcategory) {
                $label = $subcategory->parent()->find()->name;
                //preg_match('/\d{2,}\./i', $label, $result);
                //$label = trim(substr($label, strlen($result[0])));
            
                $modelline[$label][$count]['id'] = $subcategory->id;
                $modelline[$label][$count]['name'] = $subcategory->name;
                $modelline[$label][$count]['path'] = $subcategory->path;
                $count++;
            }
            
            // сортировать по типу техники
            ksort($modelline);
            
            if(!empty($modelline)) {
                foreach($modelline as $categoryName=>$models) {
                    $response .= '<div class="sub-title">'.$categoryName.'</div>
                       <table cellspacing="0" cellpadding="0" border="0"><tbody>'
                    ;
                    $count = 0;
                    $dividend = 2;

                    //usort($models, array($this, 'sortByName'));

                    foreach($models as $modelline) {
                        $brand = '';
                        if(!empty(Yii::app()->params['currentMaker'])) $brand = EquipmentMaker::model()->findByPk(Yii::app()->params['currentMaker'])->path;
                        
                        $ids[] = $modelline['id'];
                        $category = ModelLine::model()->findByPk($modelline['id']);
                        $children = $category->children()->findAll();

                        $count++;
                        if($count == 1) $response .= '<tr>';

                        $response .= '<td valign="top">'.
                               '<a href="/catalog'.$modelline['path'].$brand.'/">'.$modelline['name'].'</a>'.
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
            
//            if(!empty($subcategories)) {
//                $brand = '';
//                if(!empty(Yii::app()->params['currentMaker'])) $brand = EquipmentMaker::model()->findByPk(Yii::app()->params['currentMaker'])->path;
//                
//                $count = count($subcategories);
//                $half = ceil($count/2);
//                
//                $response = '<table cellspacing="0" cellpadding="0" border="0"><tbody>';
//                for($index = 0; $index < $half; $index++) {                    
//                    $response .= '<tr>';
//                    $response .= '<td width="50%" valign="top">';
//                    $subcategory = $subcategories[$index];
//                    $response .= '<a href="/catalog'.$subcategory['path'].$brand.'/">'.$subcategory['name'].'</a>';
//                    $response .= '</td>';
//                    if(($index + $half) < $count) {
//                        $subcategory = $subcategories[$index + $half];
//                        $response .= '<td width="50%" valign="top">';
//                        $response .= '<a href="/catalog'.$subcategory['path'].$brand.'/">'.$subcategory['name'].'</a>';
//                        $response .= '</td>';
//                    }
//                    $response .= '</tr>';
//                }
//                $response .= '</tbody></table>';
//            }
            
            ////////////////////////////////////////////////////////////////
            // bradcrumbs
            $equipmentMaker = EquipmentMaker::model()->findByPk($maker);
            $name = $equipmentMaker->name;
            $breadcrumbs[] = $name;
            
            $title = 'Запчасти '.$name;
            if(!empty($equipmentMaker->h1)) 
                $title = $equipmentMaker->h1;
            
            Yii::app()->params['meta_title'] = Yii::app()->params['meta_description'] = $name;
            if(!empty($equipmentMaker->meta_title)) Yii::app()->params['meta_title'] = $equipmentMaker->meta_title;
            if(!empty($equipmentMaker->meta_description)) Yii::app()->params['meta_description'] = $equipmentMaker->meta_description;
            if(Yii::app()->params['showSeoTexts']) {
                if(!empty($equipmentMaker->top_text)) $topText = $equipmentMaker->top_text;
                if(!empty($equipmentMaker->bottom_text)) $bottomText = $equipmentMaker->bottom_text;
            }
        }
        
        Yii::app()->params['breadcrumbs'] = $breadcrumbs;
        
        $this->render('subcategory', array('response' => $response, 'title'=>$title, 'topText'=>$topText, 'bottomText'=>$bottomText));
    }
    
    /*
    private function setHitProducts($ids)
    {
        $sql = '';
        $hitProducts = array();
        
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
                    'p.liquidity = "A" and p.image not NULL and p.published=:flag'.$sql,
                     array('in', 'm.category_id', $ids)
               ), array(':flag'=>true)
            )
            ->queryColumn()
        ;
        
        set_time_limit(200);
        $max = count($elements);
        $temp = array();
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
