<?php

class TestController extends Controller
{
    public function actionIndex()
    {
        set_time_limit(0);
        $models = Category::model()->findAll();
        foreach($models as $model){
            $model->top_text = null;
            $model->bottom_text = null;
            $model->saveNode();
        }
        
        $categorySeo = CategorySeo::model()->findAll();
        foreach($categorySeo as $model){
            $model->top_text = null;
            $model->bottom_text = null;
            $model->save();
        }
        
        $equipmentMakers = EquipmentMaker::model()->findAll();
        foreach($equipmentMakers as $model){
            $model->top_text = null;
            $model->bottom_text = null;
            $model->save();
        }
        
        $prod = Product::model()->findByPk(69109);
        $prod->date_sale_off = null;
        $prod->save();
        
        $prod2 = Product::model()->findByPk(68983);
        $prod2->date_sale_off = null;
        $prod2->save();
    }
}
