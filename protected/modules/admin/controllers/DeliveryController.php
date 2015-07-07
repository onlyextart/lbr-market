<?php

class DeliveryController extends Controller
{
    public function actionIndex()
    {
       // if(Yii::app()->user->checkAccess('shopReadCurrency'))
       // {
            $model = new Delivery('search');
            
            $model->unsetAttributes();

            if (!empty($_GET['Delivery']))
                $model->attributes = $_GET['Delivery'];
                    
            $dataProvider = $model->search();
            $dataProvider->sort->defaultOrder = 'name asc';
            $dataProvider->pagination->pageSize = 100;

            $this->render('delivery', array(
                    'model'=>$model,
                    'data'=>$dataProvider,
            ));
       // } else {
      //      $this->render('application.modules.admin.views.default.error', array('error' => 'У Вас недостаточно прав доступа.'));
      //  }
    }
}

