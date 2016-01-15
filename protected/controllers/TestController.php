<?php

class TestController extends Controller 
{    
//    public function actionPathcheck() 
//    {
//        set_time_limit(0);
//
//        $sql = 'SELECT * FROM product WHERE path like "/sparepart/-%"';
//        $all = Product::model()->findAllBySql($sql);
//        echo count($all).'<br>';
//        echo '======================<br>';
//        
//        $sql = 'SELECT * FROM product WHERE path like "/sparepart/-%" LIMIT 50';
//        $products = Product::model()->findAllBySql($sql);
//
//        
//        foreach($products as $product) {
//            echo $product->id.'<br>';
//            $product->path = '/sparepart/'.$product->id.'-'.Translite::rusencode($product->name, '-').'/';
//            $product->save();
//        }
//
//        echo '======================<br>';
//        echo 'work done - '.date('H:i:s');
//    }
}
