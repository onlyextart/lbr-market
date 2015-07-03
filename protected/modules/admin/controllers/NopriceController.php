<?php

class NopriceController extends Controller
{
    public function actionIndex()
    {
       // if(Yii::app()->user->checkAccess('shopReadCurrency'))
       // {
            /*$model = new Product('search');
            
            $model->unsetAttributes();

            if (!empty($_GET['Product']))
                $model->attributes = $_GET['Product'];
                    
            $dataProvider = $model->search();
            
            $dataProvider->pagination->pageSize = 11;
            */
            //$dataProvider->with = '';
            //$dataProvider->join = 'LEFT JOIN price_in_filial p on t.id = p.product_id';
            //$dataProvider->condition = 'p.id is NULL';
            
            $dataProvider = new CActiveDataProvider('Product', array(
                'criteria' => array(
                    'select' => array('t.id id, t.name name, t.external_id external_id, p.price price'),
                    'join' => 'LEFT JOIN price_in_filial AS p ON p.product_id = t.id',
                    'condition' => 'price IS NULL'
                ),
                'pagination' => array(
                    'pageSize' => 19,
                )
            ));
            
            $this->render('noprice', array(
                    'model'=>$model,
                    'data'=>$dataProvider,
            ));
       // } else {
      //      $this->render('application.modules.admin.views.default.error', array('error' => 'У Вас недостаточно прав доступа.'));
      //  }
    }
}

