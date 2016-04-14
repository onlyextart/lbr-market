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
        $message='';
        $fieldsShortInfo=array('image','additional_info');
        $file=array('image');
        $model = Product::model()->findByPk($id);
        if (isset($model->price_id)){
            $model->price_value=(int)$model->price->value;
            //$model->currency_iso=$model->price->currency->iso;
        }
         
        $model->productMaker_name=$model->productMaker->name;
        $model->group=$model->productGroup->name;
        
        $modellines = $this->getModellines($id);
        $prices = $this->getPrices($id);
        
        $criteria = new CDbCriteria;
        $criteria->order = 'parent, lft';
        $criteria->condition = 'level = 1';
        $groups = ProductGroup::model()->findAll($criteria);
  
        $root = ProductGroup::model()->findByAttributes(array('level'=>1));
        $id = null;
        if(!empty($model->product_group_id)) $id = $model->product_group_id;
        
        if(!empty($_POST['Product'])) {
                $editFieldsMessage=Changes::getEditMessage($model, $_POST['Product'], $fieldsShortInfo, $file);
                if (!empty($editFieldsMessage)){
                    $message.= 'Редактирование запчасти "'.$model->name.'"';
                    if(!empty($model->external_id)) $message .= ' (external_id = "'.$model->external_id.'"), ';
                    $message.=$editFieldsMessage;
                } 
                $model->attributes = $_POST['Product'];
                if ($model->product_group_id===""){
                    $model->product_group_id=null;
                }
                if ($model->validate()){
//                $image=CUploadedFile::getInstance($model,'image');
//                if (isset($image)){
//                    $filePath = '/images/upload/'.$image->name;
//                    $image->saveAs(Yii::getPathOfAlias('webroot').$filePath);
//                    $model->image = $filePath;
//                }

                if (isset($model->price_id)){
                    $modelPrice=Price::model()->findByPk($model->price_id);
                    $modelPrice->value=$_POST['Product']['price'];
                }
                
                if($model->save()) {
                    if(!empty($message)) Changes::saveChange($message, Changes::ITEM_PRODUCT);
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
            } else $this->render('edit', array('model'=>$model, 'groups'=>$groups, 'id'=>$id, 'modellines'=>$modellines, 'prices'=>$prices), false, true); 
        } else $this->render('edit', array('model'=>$model, 'groups'=>$groups, 'id'=>$id, 'modellines'=>$modellines, 'prices'=>$prices), false, true);  
    }
    
    
    public function actionDelete($id)
    {
        if(!empty($id)){
            $product = Product::model()->findByPk($id);
            $message = 'Удалена запчасть "'.$product->name.'" (external_id = "'.$product->external_id.'")';
            if(!empty($product)) {
                $product->delete();
                Changes::saveChange($message, Changes::ITEM_PRODUCT);
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
    
    public function getModellines($id)
    {
        $criteria = new CDbCriteria();
        $criteria->with = array('modelLine');
        $criteria->condition = 'product_id=:id';
        $criteria->params = array(':id'=>$id);

        $modellines = new CActiveDataProvider('ProductInModelLine', 
            array(
                'criteria'=>$criteria,
                'pagination'=>array(
                    'pageSize'=>'18'
                ),
                'sort'=>array(
                    'defaultOrder' => 'modelLine.name ASC',
                    'attributes'=>array(
                        'modelline_name' => array(
                            'asc' => $expr='modelLine.name',
                            'desc' => $expr.' DESC',
                        ),
                )),
            )
        );
            
        return $modellines;
    }
    
    public function getPrices($id)
    {
        $criteria = new CDbCriteria();
        $criteria->with = array('currency', 'filial', 'product');
        $criteria->condition = 'product_id=:id';
        $criteria->params = array(':id'=>$id);

        $modellines = new CActiveDataProvider('PriceInFilial', 
            array(
                'criteria'=>$criteria,
                'pagination'=>array(
                    'pageSize'=>'18'
                ),
                'sort'=>array(
                    'defaultOrder' => 'filial.name ASC',
                    'attributes'=>array(
                        'price',
                        'filial.name' => array(
                            'asc' => $expr='filial.name',
                            'desc' => $expr.' DESC',
                        ),
                        'price_in_rub' => array(
                            'asc' => $expr='price*currency.exchange_rate',
                            'desc' => $expr.' DESC',
                        ),
                    )
                ),
            )
        );
            
        return $modellines;
    }
}
