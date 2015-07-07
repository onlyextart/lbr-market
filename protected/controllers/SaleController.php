<?php
class SaleController extends Controller
{
    public function actionIndex()
    {   
        //$sql = '';
        
        $dependency = new CDbCacheDependency('SELECT MAX(update_time) FROM product');     
        $criteria = new CDbCriteria();
        
        /*
        if(!empty(Yii::app()->session['maker'])) {
            $sql = 'and m.maker_id = '.Yii::app()->session['maker'];
        }
        if(!empty(Yii::app()->session['category'])) {
            $allCategories = Category::model()->findByPk(Yii::app()->session['category'])->children()->findAll(array('order'=>'name', 'condition'=>'published=:published', 'params'=>array(':published' => true)));
            foreach($allCategories as $cat){
                $categories[] = $cat->id;
            }
            
            $criteria->addInCondition('m.category_id', $categories);
        }
        */
        //////////////////////////////////////////
        
        $criteria->distinct = true;
        $criteria->join = 'JOIN price_in_filial pr ON pr.product_id = t.id';
        $criteria->addCondition('t.liquidity = "D" and t.count > 0 and t.image IS NOT NULL and pr.price > 0');
        
        $data = new CActiveDataProvider(Product::model()->cache(1000, $dependency),
            array(
                'criteria' => $criteria,
                'pagination'=>array(
                   'pageSize' => 7,
                   'pageVar' => 'page',
                ),
                'sort'=>array(
                    'attributes'=>array(
                        'name'=>array(
                            'asc'=>'t.name ASC',
                            'desc'=>'t.name DESC',
                            'default'=>'asc',
                        ),
                    ),
                    'defaultOrder'=>array(
                        'name' => CSort::SORT_ASC,
                    ),
                ),
            )
        );
        
        Yii::app()->params['meta_title'] = 'Распродажа';
        $breadcrumbs[] = 'Распродажа';
        Yii::app()->params['breadcrumbs'] = $breadcrumbs;  

        $this->render('index', array('data' => $data));
    }
}
