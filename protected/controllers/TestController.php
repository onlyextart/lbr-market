<?php

class TestController extends Controller 
{    
    public function actionPathcheck() 
    {     
        ini_set('memory_limit', '1024M');
        set_time_limit(0);
        $products = Product::model()->findAll();
        echo count($products).'<br>';
        foreach($products as $product) {
            if(empty($product->path)) {
                $product->path = '/sparepart/'.$product->id.'-'.Translite::rusencode($product->name, '-').'/';
                $product->save();
            }
        }
        echo 'work done';
    }
}
