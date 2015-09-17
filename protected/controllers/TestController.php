<?php

class TestController extends Controller 
{
    public function actionShow($id = 920) 
    {
        set_time_limit(0);
        $model = new Product;//('search');
        $model->unsetAttributes();
        
        if (isset($_GET['Product']))
            $model->attributes = $_GET['Product'];
        
        $model->modelLineId = $id;
        
        $dataProvider = $model->searchEvent();
        $dataProvider->pagination = array(
            'pageVar' => 'page',
            'pageSize' => 10,
        );

        $params = array(
            'model' => $model,
            'dataProvider' => $dataProvider
        );

        if (!isset($_GET['ajax']))
            $this->render('model', $params);
        else
            $this->renderPartial('model', $params);
    }
}
