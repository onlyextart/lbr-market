<?php

class TestController extends Controller 
{    
    public function actionPathcheck() 
    {        
        $products = Product::model()->findAll('id = 75959');
        foreach($products as $product) {
            if(empty($product->path)) {
                $newPath = '/sparepart/'.$product->id.'-'.Translite::rusencode($product->name, '-').'/';
                $product->path = $newPath;
                echo $newPath.'<br>';
                $product->save();
                echo 'done';
            } else echo 'no';
        }
    }
}
