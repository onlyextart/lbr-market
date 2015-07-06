<?php
Yii::import('ext.yiiext.sidebartabs.STabbedForm');
class CategoryController extends Controller
{
    public $sidebarContent;
    
    public function actionIndex()
    {
        $model = new Category;
        
        if(!empty($_POST['Category'])) {
            $model->attributes = $_POST['Category'];
            if($model->validate()) {
                $root = Category::model()->findByAttributes(array('level'=>1));
                if(empty($root)) {
                    $root = new Category;
                    $root->name = 'Все категории';
                    $root->saveNode();
                }
                $model->path = '/'.trim(Translite::rusencode($model->name, '-'));
                $model->appendTo($root);
                Yii::app()->user->setFlash('message', 'Категория создана.');
                $this->redirect(array('edit', 'id'=>$model->id));
            }
        }
        
        $form = new STabbedForm('application.modules.admin.views.category.form', $model);
        $form->formWidget = 'ext.yiiext.sidebartabs.CJuiTabs';
        $form->summaryOnEachTab = true;
        
        $criteria = new CDbCriteria;
        $criteria->order = 'parent, lft';
        $criteria->condition = 'level = 1';
        $groups = Category::model()->findAll($criteria);
        
        $root = Category::model()->findByAttributes(array('level'=>1));
        $rootId = null;
        if(!empty($root)) $rootId = $root->id;
        
        $this->sidebarContent = $this->renderPartial('_sidebar', array(
                'model'=>$model, 'groups'=>$groups, 'rootId'=>$rootId
        ), true);
        
        $this->render('edit', array('model'=>$model, 'form' => $form), false, true);
    }
    
    public function actionEdit($id)
    {
        $model = Category::model()->findByPk($id);
        if (!$model)
	    $this->render('application.modules.admin.views.default.error', array('error' => 'Категория не найдена.'));
        if(!empty($_POST['Category'])) {
            $model->attributes = $_POST['Category'];
            if($model->validate()) {
                $model->saveNode();
                if($model->level != 1) {
                    $path = '';
                    $ancestors = $model->ancestors()->findAll(); //предки
                    foreach($ancestors as $ancestor) {
                        if($ancestor->level != 1) $path .= '/'.trim(Translite::rusencode($ancestor->name, '-'));
                    }
                    $model->path = $path.'/'.trim(Translite::rusencode($model->name, '-'));
                    $model->saveNode();
                    $this->updateChildPath($model);
                }

                Yii::app()->user->setFlash('message', 'Категория сохранена.');
            }
        }
        
        //$form = new STabbedForm('application.modules.admin.views.category.form', $model);
        //$form->formWidget = 'ext.yiiext.sidebartabs.CJuiTabs';
        //$form->summaryOnEachTab = true;
        ////////////////////////////
        
        
        $criteria = new CDbCriteria;
        $criteria->order = 'parent, lft';
        $criteria->condition = 'level = 1';
        $groups = Category::model()->findAll($criteria);
        
        $root = Category::model()->findByAttributes(array('level'=>1));
        $rootId = null;
        if(!empty($root)) $rootId = $root->id;
        
        $this->sidebarContent = $this->renderPartial('_sidebar', array(
                'model'=>$model, 'groups'=>$groups, 'rootId'=>$rootId
        ), true);
        
        //$this->render('edit', array('model'=>$model, 'form' => $form), false, true);
        
        $equipmentMaker = CategorySeo::model()->findAll('category_id=:id', array('id'=>$id));
        //var_dump($equipmentMaker);exit;
        $this->render('edit', array('model'=>$model, 'equipmentMaker'=>$equipmentMaker), false, true);
    }
    
    public function updateChildPath($model)
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
    }
    public function actionMoveNode()
    {
        $pos    = (int) $_POST['position'];
        $node   = Category::model()->findByPk($_POST['id']); // node_id
        $target = Category::model()->findByPk($_POST['ref']); // where insert
        //var_dump($_POST);exit;
        if($pos > 0) {
            $childs = $target->children()->findAll();
            if(isset($childs[$pos-1]) && $childs[$pos-1]['id'] != $node->id) {
               $node->moveAfter($childs[$pos-1]);
               if($node->level != 1) {
                    $path = '';
                    $ancestors = $node->ancestors()->findAll(); //предки
                    foreach($ancestors as $ancestor) {
                        if($ancestor->level != 1) $path .= '/'.trim(Translite::rusencode($ancestor->name, '-'));
                    }
                    $node->path = $path.'/'.trim(Translite::rusencode($node->name, '-'));
                    $node->saveNode();
                    $this->updateChildPath($node);
                    /*$descendants=$category->descendants()->findAll();
                    foreach($descendants as $descendant){
                        $path = '';
                        $ancestors = $descendant->ancestors()->findAll(); //предки
                        foreach($ancestors as $ancestor) {
                            if($ancestor->level != 1) $path .= '/'.trim(Translite::rusencode($ancestor->name, '-'));
                        }
                        $descendant->path = $path.'/'.trim(Translite::rusencode($descendant->name, '-'));
                        $descendant->saveNode();
                    }*/
                }
            }
        } else {
            $node->moveAsFirst($target);
            if($node->level != 1) {
                $path = '';
                $ancestors = $node->ancestors()->findAll(); //предки
                foreach($ancestors as $ancestor) {
                    if($ancestor->level != 1) $path .= '/'.trim(Translite::rusencode($ancestor->name, '-'));
                }
                $node->path = $path.'/'.trim(Translite::rusencode($node->name, '-'));
                $node->saveNode();
                $this->updateChildPath($node);
            }
        }
        echo 'Узел успешно перемещен';
    }
    
    public function actionDelete($id)
    {
        $model = Category::model()->findByPk($id);
        if (!$model)
	    $this->render('application.modules.admin.views.default.error', array('error' => 'Категория не найдена.'));
        
        $model->deleteNode();
        Yii::app()->user->setFlash('message', 'Категория удалена.');
        $this->redirect(array('index'));        
    }
}

