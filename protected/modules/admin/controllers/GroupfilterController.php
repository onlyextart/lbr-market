<?php 
class GroupfilterController extends Controller
{
    public function actionIndex()
    {
        $criteria = new CDbCriteria;
        $criteria->order = 'parent, lft';
        $criteria->condition = 'level = 1';
        $groups = ProductGroupFilter::model()->findAll($criteria);
            
        $this->render('index', array('groups'=>$groups));
    }
}