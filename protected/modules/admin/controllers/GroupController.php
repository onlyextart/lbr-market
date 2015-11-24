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
            $editFieldsMessage=Changes::getEditMessage($model,$_POST['ProductGroup']);
            if (!empty($editFieldsMessage)){
                $message.= 'Редактирование группы товаров "'.$model->name.'", ';
                $message.= $editFieldsMessage;
            }
            
            $model->use_in_group_filter = (int)$_POST['ProductGroup']['use_in_group_filter'];
            $model->alias = $_POST['ProductGroup']['alias'];

            if($model->validate()) {
                $model->saveNode();
                $node = ProductGroupFilter::model()->findByAttributes(array('group_id'=>$model->id));
                
                // add item to group filter
                if(!empty($model->use_in_group_filter)) {
                    if(empty($node)) { 
                        $root = ProductGroupFilter::model()->findByAttributes(array('level'=>1));
                        if(empty($root)) {
                            $mainRoot = ProductGroup::model()->findByAttributes(array('level'=>1));
                            $root = new ProductGroupFilter;
                            $root->group_id = $mainRoot->id;
                            $root->name = 'Все категории';
                            $root->saveNode();
                        }

                        $ancestors = $model->ancestors()->findAll();
                        $secondLevel = ProductGroupFilter::model()->findByAttributes(array('group_id'=>$ancestors[1]->id));
                        if(empty($check)) {
                            $secondLevel = new ProductGroupFilter;
                            $secondLevel->group_id = $ancestors[1]->id;
                            $secondLevel->name = $ancestors[1]->name;
                            if(!empty($ancestors[1]->alias)) $secondLevel->name = $ancestors[1]->alias;
                            $secondLevel->appendTo($root);
                        }
                            
                        $node = new ProductGroupFilter;
                        $node->group_id = $model->id;
                        $node->name = $model->name;
                        if(!empty($model->alias)) $node->name = $model->alias;
                        
                        if($model->level == 3) {
                            $node->appendTo($secondLevel);
                        } else if($model->level == 4) {
                            $thirdLevel = ProductGroupFilter::model()->findByAttributes(array('group_id'=>$ancestors[2]->id));
                            if(empty($thirdLevel)) {
                                $thirdLevel = new ProductGroupFilter;
                                $thirdLevel->group_id = $ancestors[2]->id;
                                $thirdLevel->name = $ancestors[2]->name;
                                if(!empty($ancestors[2]->alias)) $thirdLevel->name = $ancestors[2]->alias;
                                $thirdLevel->appendTo($secondLevel);
                            }

                            $node->appendTo($thirdLevel);
                        }
                    } else {
                        $node->name = $model->name;
                        if(!empty($model->alias)) $node->name = $model->alias;
                        $node->saveNode();
                    }
                } else if(!empty($node)) { // delete unnecessary
                    //$ancestors = $model->ancestors()->findAll();
                    $parent = $node->parent()->find();
                    $parentChildren = count($parent->children()->findAll());
                    if($parentChildren == 1) {
                        $grandparent = $parent->parent()->find();
                        $grandparentChildren = count($grandparent->children()->findAll());
                        if($grandparentChildren == 1) $grandparent->deleteNode();
                        else $parent->deleteNode();
                    } else {
                        $node->deleteNode();
                    }
                }
                
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

