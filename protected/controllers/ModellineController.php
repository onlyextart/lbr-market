<?php

class ModellineController extends Controller
{
    public function actionIndex($id = null)
    {
        $hitProducts = $modelIds = $ids = array();
        $topText = $bottomText = '';
        
        //$dependency = new CDbCacheDependency('SELECT MAX(update_time) FROM model_line');
        //$modelline = ModelLine::model()->cache(1000, $dependency)->findByPk($id);
        $modelline = ModelLine::model()->findByPk($id);
        if(!$modelline)
            throw new CHttpException(404, 'Модельный ряд не найден');
        
        $headTitle = 'Модельный ряд "'.$modelline->name.'"';
        Yii::app()->params['meta_title'] = Yii::app()->params['meta_description'] = $headTitle;
        if(!empty($modelline->meta_title))Yii::app()->params['meta_title'] = $modelline->meta_title;
        if(!empty($modelline->meta_description))Yii::app()->params['meta_description'] = $modelline->meta_description;
        if(!empty($modelline->top_text)) $topText = $modelline->top_text;
        if(!empty($modelline->bottom_text)) $bottomText = $modelline->bottom_text;
        
        $models = $modelline->children()->findAll(array('order'=>'name'));
        $response = '';
        
        // bradcrumbs
        if(!empty(Yii::app()->params['searchFlag'])) {
            $url = '/search/show/';
            if(strpos(Yii::app()->request->urlReferrer, $url))
               $breadcrumbs['Поиск'] = Yii::app()->request->urlReferrer;
            else $breadcrumbs['Поиск'] = $url; //Yii::app()->request->urlReferrer;
        }
        $category_dependency = new CDbCacheDependency('SELECT MAX(update_time) FROM category');
        $category = Category::model()->cache(1000, $category_dependency)->findByPk($modelline->category_id);
        $categoryParent = $category->parent()->find();
        //preg_match('/\d{2,}\./i', $categoryParent->name, $result);
        //$title = trim(substr($categoryParent->name, strlen($result[0])));
        $title = $categoryParent->name;
        //$breadcrumbs[$title] = '/subcategory/index/id/'.$categoryParent->id;
        $breadcrumbs[$title] = '/catalog'.$categoryParent->path.'/';
        $breadcrumbs[$category->name] = '/catalog'.$category->path.'/';
        if(!empty(Yii::app()->params['currentMaker'])) {
            $brand = EquipmentMaker::model()->findByPk(Yii::app()->params['currentMaker']);
            $breadcrumbs[$brand->name] = '/catalog'.$category->path.$brand->path.'/';
        }
        $breadcrumbs[] = $modelline->name;
        Yii::app()->params['breadcrumbs'] = $breadcrumbs;
        // end breadcrumbs

        if(!empty($models)) {
            $brand = '';
            if(!empty(Yii::app()->params['currentMaker'])) $brand = EquipmentMaker::model()->findByPk(Yii::app()->params['currentMaker'])->path;
                
            $count = count($models);
            $half = ceil($count/2);
            $response = '<table cellspacing="0" cellpadding="0" border="0"><tbody>';

            for($index = 0; $index < $half; $index++) {                    
                $response .= '<tr>';
                $response .= '<td width="50%" valign="top">';
                $model = $models[$index];
                $response .= '<a href="/catalog'.$category->path.$brand.$model['path'].'/">'.$model['name'].'</a>';
                $response .= '</td>';
                if(($index + $half) < $count) {
                    $model = $models[$index + $half];
                    $response .= '<td width="50%" valign="top">';
                    $response .= '<a href="/catalog'.$category->path.$brand.$model['path'].'/">'.$model['name'].'</a>';
                    $response .= '</td>';
                }
                $response .= '</tr>';
            }
            $response .= '</tbody></table>';
        }
        
        $this->render('modelline', array('response' => $response, 'hitProducts'=>$hitProducts, 'title'=>$headTitle, 'topText'=>$topText, 'bottomText'=>$bottomText));
    }
}

