<?php
class EquipmentmakerController extends Controller
{
    public function actionIndex($id)
    {
        if (!empty($id)) {
            $data = EquipmentMaker::model()->findByPk($id, 'published=1');
            $breadcrumbs[] = "Производители техники";
            $breadcrumbs[] = $data->name;
            Yii::app()->params['breadcrumbs'] = $breadcrumbs;
            if(!empty($data)) $this->render('index', array('data'=>$data));
            else $this->redirect('/');
        }
    }

    public function actionBestoffer($id)
    { 
        if (!empty($id)) {
            $data = BestOffer::model()->findByPk($id, 'published=1');
            $breadcrumbs[] = "Сезонные предложения";
            $breadcrumbs[] = $data->name;
            Yii::app()->params['breadcrumbs'] = $breadcrumbs;
            if(!empty($data)) $this->render('bestoffer', array('data'=>$data));
            else $this->redirect('/');
        }
    }
}

