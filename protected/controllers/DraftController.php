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
        
        if(!empty($product)) {
            $checkedIfProductExists = ProductInDraft::model()->exists('draft_id = :id and product_id = :product', array(':id'=>$id, ':product'=>$product));
            if(!$checkedIfProductExists) {
                Yii::app()->getController()->redirect(array('draft/'.$id.'/'));
            }
        }
        
        $title = 'Сборочный чертеж "'.$model->name.'"';
                
        $productsInDraft = ProductInDraft::model()->with('product')->findAllByAttributes(array('draft_id'=>$id), array('order'=>'CAST(level AS UNSIGNED), product.name'));
        foreach($productsInDraft as $productInDraft) {
            $prod = Product::model()->findByPk($productInDraft->product_id);
            $products[$productInDraft->id]['id']    = $prod->id;
            $products[$productInDraft->id]['name']  = $prod->name;
            $products[$productInDraft->id]['path']  = $prod->path;
            $products[$productInDraft->id]['level'] = $productInDraft->level;
            $products[$productInDraft->id]['count'] = $productInDraft->count;
            $products[$productInDraft->id]['note']  = $productInDraft->note;
        }
        
        Yii::app()->params['meta_title'] = Yii::app()->params['meta_description'] = $title; 
        $breadcrumbs[] = $title;
        
        Yii::app()->params['breadcrumbs'] = $breadcrumbs;
        
        $this->render('index', array('model'=>$model, 'products'=>$products, 'productId' => $product));
    }
}

