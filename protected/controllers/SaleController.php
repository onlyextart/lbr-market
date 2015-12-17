<?php
class SaleController extends Controller
{
    public function actionIndex()
    {
        $dependency = new CDbCacheDependency('SELECT MAX(update_time) FROM product');
        
        $additional_filter=new SaleFilterForm();
        if (isset($_POST['SaleFilterForm'])){
           $additional_filter->attributes=$_POST['SaleFilterForm']; 
        }
        $products = new Product;
        $products->unsetAttributes();
        $filters=$this->initFilters($additional_filter->category);
        if (isset($_GET['Product']))
            $products->attributes = $_GET['Product'];
      
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
            'breadcrumbs' => $breadcrumbs,
            'filter_category'=>$filters['filter_category'],
            'filter_maker'=>$filters['filter_maker'] 
        );
         
        if (!isset($_GET['ajax']))
            $this->render('index', $params);
        else
            $this->renderPartial('index', $params);
    }
    
     public function actionGetMakers()
     {
         
             $category_id=$_GET['category_id'];
//             $sql="SELECT DISTINCT ml.maker_id AS maker_id,maker.name AS maker_name FROM model_line ml, equipment_maker maker WHERE ml.maker_id=maker.id AND ml.category_id=".$category_id;
             $sql="SELECT DISTINCT model_line.maker_id AS maker_id,
                        model_line.maker_name AS maker_name
                    FROM product_in_model_line piml, 
                         product prod,
                        (SELECT ml.id AS ml_id,ml.maker_id AS maker_id,maker.name AS maker_name,ml.category_id AS cat_id,cat.name AS cat_name
                         FROM model_line ml, equipment_maker maker, category cat
                         WHERE ml.maker_id=maker.id and ml.category_id=cat.id
                        ) model_line
                    WHERE prod.liquidity = 'D' and prod.count > 0 and prod.published = 1 and prod.date_sale_off IS NULL and piml.product_id=prod.id 
                          and model_line.ml_id=piml.model_line_id and model_line.ml_id=piml.model_line_id";
             if (isset($category_id)&&$category_id!=null){
                 $sql.=" and model_line.cat_id=".$category_id;
             }
             $sql.=";";
             $command=Yii::app()->db->createCommand($sql);
             $array_makers=$command->query()->readAll();
             echo json_encode($array_makers);
     }
     
      private function initFilters($category_id)
      {
        $sql="SELECT  prod.id AS prod_id, 
                        model_line.maker_name AS maker_name,
                        model_line.maker_id AS maker_id,
                        model_line.cat_name AS cat_name,
                        model_line.cat_id AS cat_id, 
                        model_line.ml_id
        FROM product_in_model_line piml, 
             product prod,
            (SELECT ml.id AS ml_id,ml.maker_id AS maker_id,maker.name AS maker_name,ml.category_id AS cat_id,cat.name AS cat_name
            FROM model_line ml, equipment_maker maker, category cat
            WHERE ml.maker_id=maker.id and ml.category_id=cat.id
            ) model_line
        WHERE prod.liquidity = 'D' and prod.count > 0 and prod.published = 1 and prod.date_sale_off IS NULL and piml.product_id=prod.id 
            and model_line.ml_id=piml.model_line_id and model_line.ml_id=piml.model_line_id;";
        $result_query=Yii::app()->db->createCommand($sql)->query();
        $result_array=$result_query->readAll();
        $array_category_id=array();
        $filter_maker=array();
        if(!isset($category_id)||($category_id==null)){
            foreach ($result_array as $result_row){
                $filter_maker[$result_row["maker_id"]]=$result_row["maker_name"];
                $array_category_id[]=$result_row["cat_id"];
            }
        }
        else{
            foreach ($result_array as $result_row){
                if($result_row["cat_id"]==$category_id){
                    $filter_maker[$result_row["maker_id"]]=$result_row["maker_name"];
                }
                $array_category_id[]=$result_row["cat_id"];
            }
        }
        asort($filter_maker);
        $sql='SELECT * FROM category WHERE external_id IS NOT NULL AND published=1 AND id in(';
        $string_category_id='';
        foreach ($array_category_id as $category_id){
          $string_category_id.=$category_id.',';
        }
        $string_category_id=substr($string_category_id, 0, -1); 
        $sql.=$string_category_id.');';        
        $categories=Category::model()->findAllBySql($sql);
        $data=array();
        foreach($categories as $category){
            $ancestors = $category->ancestors()->findAll();
            if(!empty($ancestors)){
                $categoryParent = $category->parent()->find();
                if($categoryParent->level == 1){
                   $data[$category->name][$category->id] = $category->name;
                }
                elseif($categoryParent->level == 2) {
                    $data[$categoryParent->name][$category->id] = $category->name;
                }   
            }
        }
        $filter_category=$data;
        ksort($filter_category);
        
        return array(
            'filter_category'=>$filter_category,
            'filter_maker'=>$filter_maker,
        );
    }
}
