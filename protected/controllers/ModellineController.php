<?php

class ModellineController extends Controller
{
    public function actionIndex($id = null)
    {
        $hitProducts = $modelIds = $ids = array();
        $topText = $bottomText = '';
        
        //$dependency = new CDbCacheDependency('SELECT MAX(update_time) FROM model_line');
        //$modelline = ModelLine::model()->cache(1000, $dependency)->findByPk($id);
        $modelline = ModelLine::model()->findByPk($id);
        if(!$modelline)
            throw new CHttpException(404, 'Модельный ряд не найден');
        
        $headTitle = 'Модельный ряд "'.$modelline->name.'"';
        Yii::app()->params['meta_title'] = $headTitle;
        if(!empty($modelline->meta_title))Yii::app()->params['meta_title'] = $modelline->meta_title;
        if(!empty($modelline->meta_description))Yii::app()->params['meta_description'] = $modelline->meta_description;
        if(!empty($modelline->top_text)) $topText = $modelline->top_text;
        if(!empty($modelline->bottom_text)) $bottomText = $modelline->bottom_text;
        
        $models = $modelline->children()->findAll(array('order'=>'name'));
        $response = '';
        
        // bradcrumbs
        if(!empty(Yii::app()->params['searchFlag'])) {
            $url = '/search/show/';
            if(strpos(Yii::app()->request->urlReferrer, $url))
               $breadcrumbs['Поиск'] = Yii::app()->request->urlReferrer;
            else $breadcrumbs['Поиск'] = $url; //Yii::app()->request->urlReferrer;
        }
        $category_dependency = new CDbCacheDependency('SELECT MAX(update_time) FROM category');
        $category = Category::model()->cache(1000, $category_dependency)->findByPk($modelline->category_id);
        $categoryParent = $category->parent()->find();
        preg_match('/\d{2,}\./i', $categoryParent->name, $result);
        $title = trim(substr($categoryParent->name, strlen($result[0])));
        //$breadcrumbs[$title] = '/subcategory/index/id/'.$categoryParent->id;
        $currentBrand = '';
        if(!empty(Yii::app()->params['currentMaker'])) $currentBrand = EquipmentMaker::model()->findByPk(Yii::app()->params['currentMaker'])->path;
        //$breadcrumbs[$title] = '/subcategory/index/id/'.$categoryParent->id;
        $breadcrumbs[$title] = '/catalog'.$categoryParent->path.$currentBrand.'/';
        
        //$breadcrumbs[$title] = '/catalog'.$categoryParent->path.'/';
        $breadcrumbs[$category->name] = '/catalog'.$category->path.'/';
        $breadcrumbs[] = $modelline->name;
        Yii::app()->params['breadcrumbs'] = $breadcrumbs;
        // end breadcrumbs
        
        if(!empty($models)) {
            $response .= '<table cellspacing="0" cellpadding="0" border="0"><tbody>';
            $count = 0;
            $dividend = 3;
        foreach($models as $model) {
                $ids[] = $model['id'];
                $brand = EquipmentMaker::model()->findByPk($model['maker_id'])->path;
                
                $count++;
                if($count == 1) $response .= '<tr>';
                $response .= '<td><a href="/catalog'.$category->path.$brand.$model['path'].'/">'.$model['name'].'</a></td>';
                if($count == $dividend) {
                    $count = 0;
                    $response .= '</tr>';
                }
                
                $modelIds[] = $model['id'];
            }
            
            $response .= '</tbody></table>';
        }
        
        // random products for hit products
        $hitProducts = $this->setHitProducts($ids);
        
        $this->render('modelline', array('response' => $response, 'hitProducts'=>$hitProducts, 'title'=>$headTitle, 'topText'=>$topText, 'bottomText'=>$bottomText));
    }
    
    private function setHitProducts($ids)
    {
        $sql = '';
        if(!empty(Yii::app()->params['currentMaker'])) {
            $sql = ' and m.maker_id = '.Yii::app()->params['currentMaker'];
        }
        
        //$depend = new CDbCacheDependency('SELECT MAX(update_time) FROM product');
        
        $elements = Yii::app()->db->cache(1000)->createCommand()
            ->selectDistinct('p.id')
            ->from('model_line m')
            ->join('product_in_model_line pm', 'm.id=pm.model_line_id')
            ->join('product p', 'p.id=pm.product_id')
            ->where(
               array('and', 
                    'p.liquidity = "A" and p.image not NULL',
                     array('in', 'pm.model_line_id', $ids)
               )
            )
            ->queryColumn()
        ;
        
        set_time_limit(200);
        $max = count($elements);
        $temp = array();
        $count = 16;
        
        if($max > $count) {
            if(Yii::app()->params['randomImages']) {
                for($i = 0; $i < $count; ) {
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
                //$hitProducts = Product::model()->cache(1000, $depend)->findAllByAttributes(array('id'=>$temp));
                $hitProducts = Product::model()->findAllByAttributes(array('id'=>$temp));
            } else {
                $offset = mt_rand(0, $max);
                //$hitProducts = Product::model()->cache(1000, $depend)->findAllByAttributes(
                $hitProducts = Product::model()->findAllByAttributes(
                    array(
                        'id' => $elements,
                    ), 
                    array(
                        'offset' => $offset,
                        'limit' => $count,
                    )
                );
            }
        } else {
            foreach($elements as $element) {
                $temp[] = $element;
            }
            //$hitProducts = Product::model()->cache(1000, $depend)->findAllByAttributes(array('id'=>$temp));
            $hitProducts = Product::model()->findAllByAttributes(array('id'=>$temp));
        }
        
        return $hitProducts;
    }
}

