<?php

class CurrencyController extends Controller
{
    public function actionIndex()
    {
       // if(Yii::app()->user->checkAccess('shopReadCurrency'))
       // {
            $model = new Currency('search');
            
            $model->unsetAttributes();

            if (!empty($_GET['Currency']))
                $model->attributes = $_GET['Currency'];
                    
            $dataProvider = $model->search();
            
            $dataProvider->pagination->pageSize = 10;

            $this->render('currency', array(
                    'model'=>$model,
                    'data'=>$dataProvider,
            ));
       // } else {
      //      $this->render('application.modules.admin.views.default.error', array('error' => 'У Вас недостаточно прав доступа.'));
      //  }
    }
    
    public function actionEdit($id)
    {
        
    }
    
    
    public function actionDelete($id)
    {
        if(!empty($id)){
            $currency = Currency::model()->findByPk($id);
            if(!empty($currency)) {
                $currency->delete();
                Yii::app()->user->setFlash('message', 'Запись о единице валюты удалена.');
                $this->redirect(array('/admin/catalog/currency/'));
            }
        }
    }
}

