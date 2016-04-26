<?php
class PartnersController extends Controller
{
    public function actionIndex()
    {
        $sectionName="Наши партнеры";
        Yii::app()->params['meta_description'] = Yii::app()->params['meta_title'] = $sectionName;

        $data_productmaker = ProductMaker::model()->findAll(array('condition'=>'published=1 and logo IS NOT NULL'));
        $data_equipmentmaker = EquipmentMaker::model()->findAll(array('condition'=>'published=1 and logo IS NOT NULL'));
        $breadcrumbs[] = $sectionName;
        Yii::app()->params['breadcrumbs'] = $breadcrumbs;
        if(!empty($data_productmaker)||!empty($data_equipmentmaker)) {
            $this->render('view_all', array(
                'data_productmaker' => $data_productmaker,
                'data_equipmentmaker' => $data_equipmentmaker
            ));
        } else $this->redirect('/');
    }
}

