<?php
Yii::import('ext.yiiext.sidebartabs.STabbedForm');
class NopriceController extends Controller
{
    public $sidebarContent;
     
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
            set_time_limit(0);
            
            $dataProvider = new CActiveDataProvider('Product', array(
                'criteria' => array(
                    'select' => array('t.id id, t.name name, t.liquidity liquidity, t.count count, t.external_id external_id, p.price price'),
                    'join' => 'LEFT JOIN price_in_filial AS p ON p.product_id = t.id',
                    'order' => 't.liquidity',
                    'condition' => 'price IS NULL'
                ),
                'pagination' => array(
                    'pageSize' => 18,
                )
            ));
            
            /*$dataProviderNotAllPrices = new CActiveDataProvider('Filial', array(
                'criteria' => array(
                    //'with' => array('product'=>array('select'=>'name')),
                    'select' => array('p.product_id id, p.currency_code currency_code, p.price price, t.name name'),
                    'join' => 'LEFT JOIN price_in_filial AS p ON p.filial_id = t.id',
                    'order' => 'p.product_id',
                    'condition' => 'p.price IS NULL and t.level > 1'
                ),
                'pagination' => array(
                    'pageSize' => 18,
                )
            ));*/
            
            /*$dataProviderNotAllPrices = new CActiveDataProvider('PriceInFilial', array(
                'criteria' => array(
                    //'with' => array('product'=>array('select'=>'name')),
                    'select' => array('t.product_id id, t.price price, f.name name'),
                    'join' => 'RIGHT JOIN filial AS f ON t.filial_id = f.id',
                    //'condition' => 'price IS NULL'
                ),
                'pagination' => array(
                    'pageSize' => 18,
                )
            ));*/
            
            $this->render('noprice', array(
                    'model'=>$model,
                    'data'=>$dataProvider,
                    //'notAll'=>$dataProviderNotAllPrices ,
            ));
       // } else {
      //      $this->render('application.modules.admin.views.default.error', array('error' => 'У Вас недостаточно прав доступа.'));
      //  }
    }
}

