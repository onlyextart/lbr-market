<?php

class TestController extends Controller
{
    public function actionIndex()
    {
        $models = Category::model()->findAll();
        foreach($models as $model){
            $model->top_text = null;
            $model->bottom_text = null;
            $model->saveNode();
        }
        
        $models = CategorySeo::model()->findAll();
        foreach($models as $model){
            $model->top_text = null;
            $model->bottom_text = null;
            $model->save();
        }
        
        $models = EquipmentMaker::model()->findAll();
        foreach($models as $model){
            $model->top_text = null;
            $model->bottom_text = null;
            $model->save();
        }
        
        $prod = Product::model()->findByPk(69109);
        $prod->date_sale_off = null;
        $prod->save();
        
        $prod = Product::model()->findByPk(68983);
        $prod->date_sale_off = null;
        $prod->save();
    }
}
