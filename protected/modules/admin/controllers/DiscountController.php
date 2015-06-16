<?php

class DiscountController extends Controller
{
    public $sidebarContent;
    
    public function actionIndex()
    {
       // if(Yii::app()->user->checkAccess('shopReadDiscount'))
       // {
            $model = new Discount('search');
            
            $model->unsetAttributes();

            if (!empty($_GET['Discount']))
                $model->attributes = $_GET['Discount'];
            
            $dataProvider = $model->search();
            
            $dataProvider->pagination->pageSize = 10;

            $this->render('discount', array(
                    'model'=>$model,
                    'data'=>$dataProvider,
            ));
       // } else {
      //      $this->render('application.modules.admin.views.default.error', array('error' => 'У Вас недостаточно прав доступа.'));
      //  }
    }
    
    public function actionEdit($id)
    {
        $model = Discount::model()->with('product','productGroup')->findByPk($id);
        if(!empty($model->start_date))$model->start_date = date("Y-m-d H:i:s", strtotime($model->start_date));
        if(!empty($model->end_date))$model->end_date = date("Y-m-d H:i:s", strtotime($model->end_date));
        if(!empty($model->product_id)){
            $model->group_name=$model->productGroup->name;
            $model->group_id=$model->productGroup->id;
            $model->product_name=$model->product->name;
        }
        
        $criteria = new CDbCriteria;
        $criteria->order = 'parent, lft';
        $criteria->condition = 'level = 1';
        $groups = ProductGroup::model()->findAll($criteria);
  
        $id = null;
        if(!empty($model->product->product_group_id)) $id = $model->product->product_group_id;
        
        // if(!empty($model))
        if(!empty($_POST['Discount'])) {
                $model->attributes = $_POST['Discount'];
                $model->published=$_POST['Discount']['published'];
                if ($_POST['Discount']['product_id']!==""){
                    $model->product_id=$_POST['Discount']['product_id'];
                }
                if ($model->validate()){
                    if($model->save()) {
                        Yii::app()->user->setFlash('message', 'Скидка сохранена успешно.');
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
           } else $this->render('edit', array('model'=>$model, 'groups'=>$groups, 'id'=>$id), false, true); 
        } else $this->render('edit', array('model'=>$model, 'groups'=>$groups, 'id'=>$id), false, true);  
    }
    
    public function actionCreate()
    {
       // if(Yii::app()->user->checkAccess('shopCreateUser')) {
            $model = new Discount;
            $criteria = new CDbCriteria;
            $criteria->order = 'parent, lft';
            $criteria->condition = 'level = 1';
            $groups = ProductGroup::model()->findAll($criteria);
            if(!empty($_POST['Discount'])) {
               $model->attributes = $_POST['Discount'];
               $model->published=$_POST['Discount']['published'];
               if ($_POST['Discount']['product_id']!==""){
                    $model->product_id=$_POST['Discount']['product_id'];
               }
               if ($model->validate()){
                    if($model->save()) {
                        Yii::app()->user->setFlash('message', 'Скидка сохранена успешно.');
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
               } else $this->render('edit', array('model'=>$model, 'groups'=>$groups, 'id'=>$id), false, true);
         } else $this->render('edit', array('model'=>$model, 'groups'=>$groups, 'id'=>$id), false, true); 
            
        //}else {
            //throw new CHttpException(403,Yii::t('yii','У Вас недостаточно прав доступа.'));
    }
    
    public function actionFindGroup()
    {
        if(isset($_POST['id'])){
            $group_info=ProductGroup::model()->findByPk($_POST['id']);
            $criteria=new CDbCriteria(); 
            $criteria->select = 'id,name';
            $criteria->condition='product_group_id=:group_id';
            $criteria->params = array(':group_id'=>$_POST['id']);
            $products_info = Product::model()->findAll($criteria);
            foreach($products_info as $product_info){
               $products_select[$product_info['id']]=$product_info['name'];
            }
            $products_select_str= json_encode($products_select);
            $products='{"id":'.$group_info->id.', "name":"'.$group_info->name.'","products_select":'.$products_select_str.'}';
            echo $products;
        }
        
    }
    
    public function actionDelete($id)
    {
        if(!empty($id)){
            $discount = Discount::model()->findByPk($id);
            if(!empty($discount)) {
                $discount->delete();
                Yii::app()->user->setFlash('message', 'Скидка удалена.');
                $this->redirect(array('/admin/discount/'));
            }
        }
    }
}
