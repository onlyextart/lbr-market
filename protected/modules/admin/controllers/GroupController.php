<?php
Yii::import('ext.yiiext.sidebartabs.STabbedForm');
class GroupController extends Controller
{
    public $sidebarContent;
    
    public function actionIndex()
    {
        $model = new ProductGroup;
        if(!empty($_POST['ProductGroup'])) {
            $model->attributes = $_POST['ProductGroup'];
            $model->name = $_POST['ProductGroup']['name'];
            if($model->validate()) {
                $root = ProductGroup::model()->findByAttributes(array('level'=>1));
                if(empty($root)) {
                    $root = new ProductGroup;
                    $root->name = 'Все категории';
                    $root->saveNode();
                }
                $model->appendTo($root);
                $message = 'Создана группа товаров "'.$model->name.'"';
                Changes::saveChange($message);
                Yii::app()->user->setFlash('message', $message);
                $this->redirect(array('edit', 'id'=>$model->id));
            }
        }
        //echo '<pre>';
        //var_dump($model->attributes );exit;
        $form = new STabbedForm('application.modules.admin.views.group.form', $model);
        $form->formWidget = 'ext.yiiext.sidebartabs.CJuiTabs';
        $form->summaryOnEachTab = true;

        $criteria = new CDbCriteria;
        $criteria->order = 'parent, lft';
        $criteria->condition = 'level = 1';
        $groups = ProductGroup::model()->findAll($criteria);
        
        $root = ProductGroup::model()->findByAttributes(array('level'=>1));
        $rootId = null;
        if(!empty($root)) $rootId = $root->id;
        
        $this->sidebarContent = $this->renderPartial('_sidebar', array(
                'model'=>$model, 'groups'=>$groups, 'rootId'=>$rootId
        ), true);
        
        
        $this->render('edit', array('model'=>$model, 'form' => $form), false, true);
    }
    
    public function actionEdit($id)
    {
        $message = '';
        $model = ProductGroup::model()->findByPk($id);
        if (!$model)
	    $this->render('application.modules.admin.views.default.error', array('error' => 'Категория не найдена.'));
        if(!empty($_POST['ProductGroup'])) {
            //$model->attributes = $_POST['ProductGroup'];
            //$model->name = $_POST['ProductGroup']['name'];
            $editFieldsMessage=Changes::getEditMessage($model,$_POST['ProductGroup']);
            if (!empty($editFieldsMessage)){
                $message.= 'Редактирование группы товаров "'.$model->name.'", ';
                $message.= $editFieldsMessage;
            }
            $model->name = $_POST['ProductGroup']['name'];
            if($model->validate()) {
                $model->saveNode();
                if(!empty($message)) Changes::saveChange($message);
                Yii::app()->user->setFlash('message', 'Группа сохранена.');
            }
        }
        $form = new STabbedForm('application.modules.admin.views.group.form', $model);
        $form->formWidget = 'ext.yiiext.sidebartabs.CJuiTabs';
        $form->summaryOnEachTab = true;
        
        $criteria = new CDbCriteria;
        $criteria->order = 'parent, lft';
        $criteria->condition = 'level = 1';
        $groups = ProductGroup::model()->findAll($criteria);
        
        $root = ProductGroup::model()->findByAttributes(array('level'=>1));
        $rootId = null;
        if(!empty($root)) $rootId = $root->id;
        
        $this->sidebarContent = $this->renderPartial('_sidebar', array(
                'model'=>$model, 'groups'=>$groups, 'rootId'=>$rootId
        ), true);
        
        $this->render('edit', array('model'=>$model, 'form' => $form), false, true);
    }
    
    public function actionMoveNode()
    {
        $pos    = (int) $_GET['position'];
        $node   = ProductGroup::model()->findByPk($_GET['id']);
        $target = ProductGroup::model()->findByPk($_GET['ref']);
        
        if($pos > 0) {
            $childs = $target->children()->findAll();
            if(isset($childs[$pos-1]) && $childs[$pos-1]['id'] != $node->id)
                  $node->moveAfter($childs[$pos-1]);
        } else
            $node->moveAsFirst($target);
    }
    
    public function actionDelete($id)
    {
        $model = ProductGroup::model()->findByPk($id);
        if (!$model)
	    $this->render('application.modules.admin.views.default.error', array('error' => 'Группа не найдена.'));
        
        
        $message = 'Удалена группа товаров "'.$model->name.'"';
        if(!empty($model->external_id)) $message .= ' (external_id = "'.$model->external_id.'")';
        
        $children = $model->children()->findAll();
        if(!empty($children)){
            $i = 1;
            $message .= ', также удалены подгруппы: ';
            foreach($children as $child) {
                $message .= $i.') '.$child->name;
                if(!empty($child->external_id)) $message .= ' (external_id = "'.$child->external_id.'"); ';
                $i++;
            }
        }
        
        set_time_limit(1200);
        $model->deleteNode();
        Changes::saveChange($message);
        Yii::app()->user->setFlash('message', 'Группа удалена.');
        $this->redirect(array('index'));        
    }
}

