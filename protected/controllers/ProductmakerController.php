<?php
class ProductmakerController extends Controller
{
    public function actionIndex($path)
    {
        if (!empty($path)) {
            $data = ProductMaker::model()->find('path=:path and published=1', array('path'=>'/'.$path));
            
            $breadcrumbs[] = "Производители запчастей";
            $breadcrumbs[] = $data->name;
            Yii::app()->params['breadcrumbs'] = $breadcrumbs;
            
            if(!empty($data)) {
                Yii::app()->params['analiticsMark'] = 'pmaker='.$data->external_id;
                $this->render('index', array('data'=>$data));
            }
            else $this->redirect('/');
        }
    }
}

