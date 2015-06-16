<?php 
class StructureController extends Controller
{
    public function actionIndex()
    {
        $criteria = new CDbCriteria;
        $criteria->order = 'parent, lft';
        $criteria->condition = 'level = 1';
        $groups = ProductGroup::model()->findAll($criteria);
            
        $this->render('index', array('groups'=>$groups));
    }
}