<?php
class SeasonalsaleController extends Controller
{
    public function actionIndex($id = null)
    {
        $sectionName = 'Спецпредложения';
        Yii::app()->params['meta_description'] = Yii::app()->params['meta_title'] = $sectionName;
        
        if (!empty($id)) {
            $data = BestOffer::model()->findByPk($_REQUEST['id'], 'published=1');
            $breadcrumbs[$sectionName] = '/seasonalsale/';
            $breadcrumbs[] = $data->name;
            Yii::app()->params['meta_description'] = Yii::app()->params['meta_title'] .= ' '.$data->name;
            Yii::app()->params['breadcrumbs'] = $breadcrumbs;
            if(!empty($data)) $this->render('index', array('data'=>$data));
            else $this->redirect('/');
        } else {
            $data = BestOffer::model()->findAll(array('condition'=>'published=1', 'order'=>'IFNULL(level, 1000000000)'));
            $breadcrumbs[] = $sectionName;
            Yii::app()->params['breadcrumbs'] = $breadcrumbs;
            if(!empty($data)) $this->render('view_all', array('data'=>$data));
            else $this->redirect('/');
        }
    }
}

































