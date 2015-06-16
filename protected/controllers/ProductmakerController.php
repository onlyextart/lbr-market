<?php
class ProductmakerController extends Controller
{
    public function actionIndex($id)
    {
        if (!empty($id)) {
            $data = ProductMaker::model()->findByPk($id, 'published=1');
            $breadcrumbs[] = "Производители запчастей";
            $breadcrumbs[] = $data->name;
            Yii::app()->params['breadcrumbs'] = $breadcrumbs;
            if(!empty($data)) $this->render('index', array('data'=>$data));
            else $this->redirect('/');
        }
    }

}

