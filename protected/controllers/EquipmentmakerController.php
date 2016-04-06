<?php
class EquipmentmakerController extends Controller
{    
    public function actionIndex($path)
    {
        $sectionName="Наши партнеры";
        Yii::app()->params['meta_description'] = Yii::app()->params['meta_title'] = $sectionName;
        
        if (!empty($path)) {
            $data = EquipmentMaker::model()->find('path=:path and published=1', array('path'=>'/'.$path));
            if(!empty($data)) {
                Yii::app()->getController()->redirect(array('manufacturer/'.$path.'/'));
            } else {
                throw new CHttpException(404, 'Страница не найдена');
            }
            
//            $breadcrumbs[$sectionName] = '/partners/';
//            $breadcrumbs[] = $data->name;
//            Yii::app()->params['breadcrumbs'] = $breadcrumbs;
//            Yii::app()->params['meta_title'] = $data->name;
//            
//            if(!empty($data)) {
//                Yii::app()->params['analiticsMark'] = 'maker='.$data->external_id;
//                $this->render('index', array('data'=>$data));
//            }
//            else $this->redirect('/');
        }
    }
    
    /*
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
    }*/
}

