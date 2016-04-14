<?php
class DraftController extends Controller
{
    public function actionIndex($id, $product = null)
    {
        $products = array();
        $model = Draft::model()->findByPk($id);
        if(!$model) {
            throw new CHttpException(404, 'Сборочный чертеж не найден');
        }
        
        $checkedProductExists = ProductInDraft::model()->exists('draft_id = :id and product_id = :product', array(':id'=>$id, ':product'=>$product));
        if($checkedProductExists) {
            
        } else {
            
        }
        
        $title = 'Сборочный чертеж "'.$model->name.'"';
                
        $productsInDraft = ProductInDraft::model()->with('product')->findAllByAttributes(array('draft_id'=>$id), array('order'=>'CAST(level AS UNSIGNED), product.name'));
        foreach($productsInDraft as $product) {
            $prod = Product::model()->findByPk($product->product_id);
            $products[$product->id]['id']    = $prod->id;
            $products[$product->id]['name']  = $prod->name;
            $products[$product->id]['path']  = $prod->path;
            $products[$product->id]['level'] = $product->level;
            $products[$product->id]['count'] = $product->count;
            $products[$product->id]['note']  = $product->note;
        }
        
        Yii::app()->params['meta_title'] = Yii::app()->params['meta_description'] = $title; 
        $breadcrumbs[] = $title;
        
        Yii::app()->params['breadcrumbs'] = $breadcrumbs;
        
        $this->render('index', array('model'=>$model, 'products'=>$products));
    }
}

