<?php

class TestController extends Controller 
{    
//    public function actionPathcheck() 
//    {
//        set_time_limit(0);
//
//        $sql = 'SELECT * FROM product_group_filter WHERE level=2 and path is null';
//        $all = ProductGroupFilter::model()->findAllBySql($sql);
//        echo count($all).'<br>';
//        echo '======================<br>';
//        
//        $sql = 'SELECT * FROM product_group_filter WHERE level=2 and path is null LIMIT 50';
//        $products = ProductGroupFilter::model()->findAllBySql($sql);
//
//        
//        foreach($products as $product) {
//            echo $product->id.'<br>';
//            $product->path = '/products/'.Translite::rusencode($product->name, '-');
//            $product->saveNode();
//        }
//
//        echo '======================<br>';
//        echo 'work done - '.date('H:i:s');
//    }
    
    /*public function actionFilial() 
    {
        set_time_limit(0);
        $filials = Filial::model()->findAll();

        foreach($filials as $filial) {
            $name = $filial->name;
            $pos = strpos($name, "-филиал");
            if($pos !== false) {
                echo $filial->id.'<br>';
                $name = substr($name, 0, $pos);

                $filial->name = $name;
                $filial->saveNode();
            }
        }

        echo '======================<br>';
        echo 'work done - '.date('H:i:s');
    }*/
}
