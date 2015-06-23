<?php

class ProductController extends Controller
{
    public $sidebarContent;
    
    public function actionIndex()
    {
       // if(Yii::app()->user->checkAccess('shopReadProduct'))
       // {
        
            $model = new Product('search');
            
            $model->unsetAttributes();

            if (!empty($_GET['Product']))
                $model->attributes = $_GET['Product'];
                    
            $dataProvider = $model->search();
            
            $dataProvider->pagination->pageSize = 11;

            $this->render('product', array(
                    'model'=>$model,
                    'data'=>$dataProvider,
            ));
       // } else {
      //      $this->render('application.modules.admin.views.default.error', array('error' => 'У Вас недостаточно прав доступа.'));
      //  }
    }
    
    public function actionEdit($id)
    {
        $model = Product::model()->findByPk($id);
        if (isset($model->price_id)){
            $model->price_value=(int)$model->price->value;
            //$model->currency_iso=$model->price->currency->iso;
        }
        $model->productMaker_name=$model->productMaker->name;
        $model->group=$model->productGroup->name;
        
        $modellines = array();
        $allModelLines = ProductInModelLine::model()->findAllByAttributes(array('product_id'=>$id));
        
        foreach ($allModelLines as $oneModelLine)
        {
            $modelline = ModelLine::model()->findByPk($oneModelLine->model_line_id);
            $brand = EquipmentMaker::model()->findByPk($modelline->maker_id)->path;
            $type = Category::model()->findByPk($modelline->category_id)->path;
            $modellines[$oneModelLine->model_line_id]['id'] = $oneModelLine->model_line_id;
            $modellines[$oneModelLine->model_line_id]['path'] = '/catalog'.$type.$brand.$modelline->path.'/';
            $modellines[$oneModelLine->model_line_id]['name'] = $modelline->name;
        }
        
        $criteria = new CDbCriteria;
        $criteria->order = 'parent, lft';
        $criteria->condition = 'level = 1';
        $groups = ProductGroup::model()->findAll($criteria);
  
        $root = ProductGroup::model()->findByAttributes(array('level'=>1));
        $id = null;
        if(!empty($model->product_group_id)) $id = $model->product_group_id;
        
        if(!empty($_POST['Product'])) {
                $model->attributes = $_POST['Product'];
                if ($model->product_group_id===""){
                    $model->product_group_id=null;
                }
                if ($model->validate()){
                $image=CUploadedFile::getInstance($model,'image');
                if (isset($image)){
                    $filePath = '/images/upload/'.$image->name;
                    $image->saveAs(Yii::getPathOfAlias('webroot').$filePath);
                    $model->image = $filePath;
                }

                if (isset($model->price_id)){
                    $modelPrice=Price::model()->findByPk($model->price_id);
                    $modelPrice->value=$_POST['Product']['price'];
                }
                
                if($model->save()) {
                    Yii::app()->user->setFlash('message', 'Запчасть сохранена успешно.');
                    $this->redirect(array('edit', 'id'=>$model->id));
                } else {
                    //$errors = $model->getErrors();
                    $errors = "Ошибка при сохранении";
                    Yii::log($errors, 'error');
                    Yii::app()->user->setFlash('error', $errors);
                    $this->render('edit', array(
                        'model'=>$model,
                        'groups'=>$groups,
                        'id'=>$id
                    ));
                }
            } else $this->render('edit', array('model'=>$model, 'groups'=>$groups, 'id'=>$id, 'modellines'=>$modellines), false, true); 
        } else $this->render('edit', array('model'=>$model, 'groups'=>$groups, 'id'=>$id, 'modellines'=>$modellines), false, true);  
    }
    
    
    public function actionDelete($id)
    {
        if(!empty($id)){
            $product = Product::model()->findByPk($id);
            if(!empty($product)) {
                $product->delete();
                Yii::app()->user->setFlash('message', 'Продукт удален.');
                $this->redirect(array('/admin/product/'));
            }
        }
    }
    
    public function actionFindGroup()
    {
        if(isset($_POST['id'])){
            $group_info=ProductGroup::model()->findByPk($_POST['id']);
            $group='{"id":'.$group_info->id.', "name":"'.$group_info->name.'"}';
            echo $group;
        }
    }
}