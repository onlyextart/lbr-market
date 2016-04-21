<?php

class TestController extends Controller 
{    
    public function actionCheck() 
    {
        set_time_limit(0);

//        $sql = 'SELECT * FROM product_in_model_line';
//        $all = ProductInModelLine::model()->findAllBySql($sql);
//        echo count($all).'<br>';
//        echo '======================<br>';
        
        $sql = 'SELECT * FROM product_in_model_line LIMIT 50';
        $products = ProductInModelLine::model()->findAllBySql($sql);

        foreach($products as $product) {
            $id = $product->product_id;
            $exists = Product::model()->exists('id = '.$id);
            if(!$exists) {
                echo 'del = '.$id.'<br>';
                ProductInModelLine::model()->deleteAll('product_id = :id', array(':id'=>$id));
            }
        }

        echo '======================<br>';
        echo 'work done - '.date('H:i:s');
    }
    
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
    
//    public function actionPath() 
//    {
//        $categories = Category::model()->findAll('level != 1');
//        foreach($categories as $category) {
//            $parentName = $parentOldName = '';
//            $path = '/'.Translite::rusencode($category->name, '-');
//            $pathOld = '/'.Translite::oldrusencode($category->name, '-');
//            
//            if($category->level == 3) {
//                $parentName = '/'.Translite::rusencode($category->parent()->find()->name, '-');
//                $parentOldName = '/'.Translite::oldrusencode($category->parent()->find()->name, '-');
//            }
//            
//            $path = $parentName.$path;
//            $pathOld = $parentOldName.$pathOld;
//            
//            if($path != $pathOld) {
//                echo 'RewriteRule ^(.*)(/catalog'.$pathOld.')(.*) http://www.lbr-market.ru/catalog'.$path.'/ [R=301,L]';
//                echo '<br>';
//            }
//        }
//    }
}
