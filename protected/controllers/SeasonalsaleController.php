<?php
class SeasonalsaleController extends Controller
{
    public function actionIndex()
    {
        if (!empty($_REQUEST['id'])) {
            $data = BestOffer::model()->findByPk($_REQUEST['id'], 'published=1');
            $breadcrumbs["Спецпредложения"] = '/seasonalsale/';
            $breadcrumbs[] = $data->name;
            Yii::app()->params['breadcrumbs'] = $breadcrumbs;
            if(!empty($data)) $this->render('index', array('data'=>$data));
            else $this->redirect('/');
        }
        else{
            $data = BestOffer::model()->findAll(array('condition'=>'published=1', 'order'=>'IFNULL(level, 1000000000)'));
            $breadcrumbs[] = "Спецпредложения";
            Yii::app()->params['breadcrumbs'] = $breadcrumbs;
            if(!empty($data)) $this->render('view_all', array('data'=>$data));
            else $this->redirect('/');
        }
    }
}

































