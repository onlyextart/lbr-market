<?php
class SaleController extends Controller
{
    public function actionIndex()
    {
        $dependency = new CDbCacheDependency('SELECT MAX(update_time) FROM product');
        
        $products = new Product;
        $products->unsetAttributes();
        $products->initForSale();
        
        if (isset($_GET['Product']))
            $products->attributes = $_GET['Product'];
        
//        var_dump($products->attributes);
//        exit();
        
        $additional_filter=new SaleFilterForm();
        if (isset($_POST['SaleFilterForm'])){
           $additional_filter->attributes=$_POST['SaleFilterForm']; 
        }
        
        if (isset($_GET['ajax'])){
            if(isset($_GET['maker'])){
                $additional_filter->maker=$_GET['maker']; 
            }
            if(isset($_GET['category'])){
                $additional_filter->category=$_GET['category']; 
            }
        }
        
        $result =$products->searchEventSale($additional_filter);
        $dataProvider = $result['dataProvider'];
        $makerFilter=$result['makerFilter'];
        
        $dataProvider->pagination = array(
            'pageVar' => 'page',
            'pageSize' => 10,
            'params'    => isset($_GET['Product']) ? 
                                array('Product' => $_GET['Product'],'maker'=>$additional_filter->maker,
                                      'category'=>$additional_filter->category) : 
                                array('maker'=>$additional_filter->maker,
                                      'category'=>$additional_filter->category),
        );
        
        
//        $criteriaMaker=$result['criteriaMaker'];
        
//        $makersFilter=Product::model()->findAll($criteriaMaker);
        
//        $data=Product::model()->findAll($result['criteria']);
//        foreach ($data as $record){
//            
//        }
        //$groupFilter = $this->getAllGroups($products->product_maker_id);
        //$brandFilter = $this->getAllBrands($products->product_group_id);
        
        
        
        //$criteria = new CDbCriteria();
        
        /*
        $sql = '';
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
        
        //$criteria->distinct = true;
        //$criteria->join = 'JOIN price_in_filial pr ON pr.product_id = t.id';
        //$criteria->addCondition('t.liquidity = "D" and t.count > 0 and t.image IS NOT NULL and pr.price > 0');
        //$criteria->addCondition('t.liquidity = "D" and t.count > 0 and pr.price > 0');
        //$criteria->addCondition('t.liquidity = "D" and t.count > 0 and t.published = 1');
        
//        $data = new CActiveDataProvider(Product::model()->cache(1000, $dependency),
//            array(
//                'criteria' => $criteria,
//                'pagination'=>array(
//                   'pageSize' => 7,
//                   'pageVar' => 'page',
//                ),
//                'sort'=>array(
//                    'sortVar' => 'sort',
//                    'attributes'=>array(
//                        'name'=>array(
//                            'asc'=>'t.name ASC',
//                            'desc'=>'t.name DESC',
//                            'default'=>'asc',
//                        ),
//                    ),
//                    'defaultOrder'=>array(
//                        'name' => CSort::SORT_ASC,
//                    ),
//                ),
//            )
//        );
        
        Yii::app()->params['meta_description'] = Yii::app()->params['meta_title'] = 'Распродажа';
        $breadcrumbs[] = 'Распродажа';
        Yii::app()->params['breadcrumbs'] = $breadcrumbs;  

        //$this->render('index', array('data' => $data));
        
         $params = array(
            'products' => $products,
            'additional_filter'=>$additional_filter,
            'dataProvider' => $dataProvider,
            'makerFilter'=>$makerFilter,
            'breadcrumbs' => $breadcrumbs
        );
         
        if (!isset($_GET['ajax']))
            $this->render('index', $params);
        else
            $this->renderPartial('index', $params);
    }
    
//    private function getAllGroups($groups_id) {
//        $crit = new CDbCriteria();
//        $crit->distinct = true;
//        $crit->select = '*';
//        $crit->condition = 'external_id IS NOT NULL';
//        $crit->addInCondition('id', $groups_id);
//        $groups = ProductGroup::model()->findAll($crit);
//        $data=ProductGroup::getGroupHierarchy($groups);
//        return $data;
//    } 

}
