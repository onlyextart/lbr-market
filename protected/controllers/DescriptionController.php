<?php
class DescriptionController extends Controller
{
    public function actionIndex()
    {}

    public function actionMaker($id)
    { 
        if (!empty($id)) {
            $data = EquipmentMaker::model()->find('id = :id and published=1', array(':id'=>$id));
            $breadcrumbs[] = "Производители техники";
            $breadcrumbs[] = $data->name;
            Yii::app()->params['breadcrumbs'] = $breadcrumbs;
            if(!empty($data)) $this->render('maker', array('data'=>$data));
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
