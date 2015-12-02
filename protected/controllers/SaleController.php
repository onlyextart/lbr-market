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
        
        Yii::app()->params['meta_description'] = Yii::app()->params['meta_title'] = 'Распродажа';
        $breadcrumbs[] = 'Распродажа';
        Yii::app()->params['breadcrumbs'] = $breadcrumbs;  

        
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
}
