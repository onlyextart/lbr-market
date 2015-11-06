<?php
class SeasonalsaleController extends Controller
{
    public function actionIndex($id = null)
    {
        $sectionName = 'Спецпредложения';
        Yii::app()->params['meta_description'] = Yii::app()->params['meta_title'] = $sectionName;
        
        if (!empty($id)) {
            $data = BestOffer::model()->findByPk($_REQUEST['id'], 'published=1');
            $breadcrumbs[$sectionName] = '/seasonalsale/';
            $breadcrumbs[] = $data->name;
            Yii::app()->params['meta_description'] = Yii::app()->params['meta_title'] .= ' '.$data->name;
            Yii::app()->params['breadcrumbs'] = $breadcrumbs;
            
            $products = new Product('searchEventMaker');
            
            $products->unsetAttributes();
        
            if (isset($_GET['Product']))
                $products->attributes = $_GET['Product'];
            
            $makers=BestofferMakers::model()->findAllByAttributes(array('bestoffer_id'=>$id));
            
            if(!empty($makers)){
                $makers_array=array();
                foreach ($makers as $maker) {
                    $makers_array[]=$maker->maker_id; 
                }
            
                $products->makersID='('.join(',',$makers_array).')'; 
                       
                $criteria=new CDbCriteria;
                $criteria->condition = 't.id IN '.$products->makersID;
                $filter_data=ProductMaker::model()->findAll($criteria);
                
                //проверка, есть ли товары у выбранных производителей
                $criteria_prod=new CDbCriteria;
                $criteria_prod->condition='product_maker_id IN '.$products->makersID;
                $products_by_makers=Product::model()->findAll($criteria_prod);
                
                $dataProvider = $products->searchEventMaker();
                $dataProvider->pagination = array(
                    'pageVar' => 'page',
                    'pageSize' => 10,
                );
            }
            if(!empty($data)){ 
                if(!empty($makers)){
                    $this->render('index', array('data'=>$data,'products' => $products,'dataProvider' => $dataProvider,'filter_data'=>$filter_data,'products_by_makers'=>$products_by_makers));
                }
                else{
                  $this->render('index', array('data'=>$data,'products' => $products));  
                }     
            }
            else $this->redirect('/');
        } else {
            $data = BestOffer::model()->findAll(array('condition'=>'published=1', 'order'=>'IFNULL(level, 1000000000)'));
            $breadcrumbs[] = $sectionName;
            Yii::app()->params['breadcrumbs'] = $breadcrumbs;
            if(!empty($data)) $this->render('view_all', array('data'=>$data));
            else $this->redirect('/');
        }
    }
}

































