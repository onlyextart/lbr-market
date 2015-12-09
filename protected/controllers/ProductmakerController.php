<?php
class ProductmakerController extends Controller
{
    public function actionIndex($path=null)
    {
        $sectionName="Наши партнеры";
        Yii::app()->params['meta_description'] = Yii::app()->params['meta_title'] = $sectionName;
        
        if (!empty($path)) {
            $data = ProductMaker::model()->find('path=:path and published=1', array('path'=>'/'.$path));
            
            $breadcrumbs[$sectionName] = '/product-maker/';
            $breadcrumbs[] = $data->name;
            Yii::app()->params['breadcrumbs'] = $breadcrumbs;
            Yii::app()->params['meta_title'] = $data->name;
            
            if(!empty($data)) {
                Yii::app()->params['analiticsMark'] = 'pmaker='.$data->external_id;
                $this->render('index', array('data'=>$data));
            }
            else $this->redirect('/');
        }
        else{
            $data = ProductMaker::model()->findAll(array('condition'=>'published=1 and logo IS NOT NULL'));
            $breadcrumbs[] = $sectionName;
            Yii::app()->params['breadcrumbs'] = $breadcrumbs;
            if(!empty($data)) $this->render('view_all', array('data'=>$data));
            else $this->redirect('/');
        }
    }
}

