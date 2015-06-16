<?php
Yii::import('ext.yiiext.sidebartabs.STabbedForm');
class FilialController extends Controller
{
    public $sidebarContent;
    
    public function actionIndex()
    {
        $model = new Filial;
        
        if(!empty($_POST['Filial'])) {
            $model->attributes = $_POST['Filial'];
            if($model->validate()) {
                $root = Filial::model()->findByAttributes(array('level'=>1));
                if(empty($root)) {
                    $root = new Filial;
                    $root->name = 'Все филиалы';
                    $root->saveNode();
                }
                $model->appendTo($root);
                Yii::app()->user->setFlash('message', 'Филиал создан.');
                $this->redirect(array('edit', 'id'=>$model->id));
            }
        }
        
        $form = new STabbedForm('application.modules.admin.views.category.form', $model);
        $form->formWidget = 'ext.yiiext.sidebartabs.CJuiTabs';
        $form->summaryOnEachTab = true;

        $criteria = new CDbCriteria;
        $criteria->order = 'parent, lft';
        $criteria->condition = 'level = 1';
        $groups = Filial::model()->findAll($criteria);
        
        $root = Filial::model()->findByAttributes(array('level'=>1));
        $rootId = null;
        if(!empty($root)) $rootId = $root->id;
        
        $this->sidebarContent = $this->renderPartial('_sidebar', array(
                'model'=>$model, 'groups'=>$groups, 'rootId'=>$rootId
        ), true);
        
        $this->render('edit', array('model'=>$model, 'form' => $form), false, true);
    }
    
    public function actionEdit($id)
    {
        $model = Filial::model()->findByPk($id);
        if (!$model)
	    $this->render('application.modules.admin.views.default.error', array('error' => 'Филиал не найден.'));
        if(!empty($_POST['Filial'])) {
            $model->attributes = $_POST['Filial'];
            if($model->validate()) {
                $model->saveNode();
                Yii::app()->user->setFlash('message', 'Филиал сохранен.');
            }
        }
        $form = new STabbedForm('application.modules.admin.views.category.form', $model);
        $form->formWidget = 'ext.yiiext.sidebartabs.CJuiTabs';
        $form->summaryOnEachTab = true;
        
        $criteria = new CDbCriteria;
        $criteria->order = 'parent, lft';
        $criteria->condition = 'level = 1';
        $groups = Filial::model()->findAll($criteria);
        
        $root = Filial::model()->findByAttributes(array('level'=>1));
        $rootId = null;
        if(!empty($root)) $rootId = $root->id;
        
        $this->sidebarContent = $this->renderPartial('_sidebar', array(
                'model'=>$model, 'groups'=>$groups, 'rootId'=>$rootId
        ), true);
        
        $this->render('edit', array('model'=>$model, 'form' => $form), false, true);
    }
    
    /*public function updateChildPath($model)
    {
        $children = $model->children()->findAll(); //потомки
        if(count($children) > 0) {
            foreach($children as $child) {
                $path = '';
                $ancestors = $child->ancestors()->findAll(); //предки
                foreach($ancestors as $ancestor) {
                    if($ancestor->level != 1) $path .= '/'.trim(Translite::rusencode($ancestor->name, '-'));
                }
                $child->path = $path.'/'.trim(Translite::rusencode($child->name, '-'));
                $child->saveNode();
                
                $this->updateChildPath($child);
            }
        }
    }*/
    public function actionMoveNode()
    {
        $pos    = (int) $_POST['position'];
        $node   = Filial::model()->findByPk($_POST['id']); // node_id
        $target = Filial::model()->findByPk($_POST['ref']); // where insert
        //var_dump($_POST);exit;
        if($pos > 0) {
            $childs = $target->children()->findAll();
            if(isset($childs[$pos-1]) && $childs[$pos-1]['id'] != $node->id) {
               $node->moveAfter($childs[$pos-1]);
               /*if($node->level != 1) {
                    $path = '';
                    $ancestors = $node->ancestors()->findAll(); //предки
                    foreach($ancestors as $ancestor) {
                        if($ancestor->level != 1) $path .= '/'.trim(Translite::rusencode($ancestor->name, '-'));
                    }
                    $node->path = $path.'/'.trim(Translite::rusencode($node->name, '-'));
                    $node->saveNode();
                    //$this->updateChildPath($node);
                }*/
            }
        } else {
            $node->moveAsFirst($target);
            /*if($node->level != 1) {
                $path = '';
                $ancestors = $node->ancestors()->findAll(); //предки
                foreach($ancestors as $ancestor) {
                    if($ancestor->level != 1) $path .= '/'.trim(Translite::rusencode($ancestor->name, '-'));
                }
                $node->path = $path.'/'.trim(Translite::rusencode($node->name, '-'));
                $node->saveNode();
                $this->updateChildPath($node);
            }*/
        }
        echo 'Узел успешно перемещен';
    }
    
    public function actionDelete($id)
    {
        $model = Filial::model()->findByPk($id);
        if (!$model)
	    $this->render('application.modules.admin.views.default.error', array('error' => 'Филиал не найден.'));
        
        $model->deleteNode();
        Yii::app()->user->setFlash('message', 'Филиал удален.');
        $this->redirect(array('index'));        
    }
}

