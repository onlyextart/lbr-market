<?php
Yii::import('ext.yiiext.sidebartabs.STabbedForm');
class ModellineController extends Controller
{
    public $sidebarContent;
    
    public function actionIndex()
    {
        //if(Yii::app()->user->checkAccess('shopReadUser'))
        //{
            /*$model = new ModelLine('search');
            $model->unsetAttributes();

            if (!empty($_GET['ModelLine']))
                $model->attributes = $_GET['ModelLine'];

            $dataProvider = $model->search();
            $dataProvider->pagination->pageSize = 15;
            */
        
            /*old code
            $items = array();
            $mainRoots = ModelLine::model()->roots()->findAll();
            
            foreach($mainRoots as $mainRoot)
            {
               $modelline = ModelLine::model()->findByPk($mainRoot->id);
               $descendants = $modelline->children()->findAll();
               $count = 0;
               foreach($descendants as $descendant)
                {$count++;
                   $items[] = array('id'=>$descendant->id, 'name'=>$descendant->name);
                }
            }

               $sort = new CSort;
               $sort->defaultOrder = 'id ASC';
               $sort->attributes = array('id');

               $dataProvider = new CArrayDataProvider($items, array(
                  'keyField'   => 'id', 
                  'pagination' => array(
                     'pageSize'=> 19,
                  ),
                     'sort' => $sort
                  )
               );
               old code --*/
        
        
               //exit;
               //$dataProvider = $model->search();
               //$dataProvider->pagination->pageSize = 15;
        
                $model = new ModelLine('search');
            
                $model->unsetAttributes();

                if (!empty($_GET['ModelLine']))
                    $model->attributes = $_GET['ModelLine'];
                    
                $dataProvider = $model->search();
            
                $dataProvider->pagination->pageSize = 19;

               $this->render('index', array(
                  'model'=>$model,
                  'data'=>$dataProvider,
               ));
        //} else {
        //    $this->render('application.modules.admin.views.default.error', array('error' => 'У Вас недостаточно прав доступа.'));
        //}
    }
    
    /*public function actionIndex2()
    {
        $model = new ModelLine;
        
        if(!empty($_POST['ModelLine'])) {
            $model->attributes = $_POST['ModelLine'];
            if($model->validate()) {
                $root = ModelLine::model()->findByAttributes(array('level'=>1));
                if(empty($root)) {
                    $root = new ModelLine;
                    $root->name = 'Все модельные ряды';
                    $root->saveNode();
                }
                $model->appendTo($root);
                Yii::app()->user->setFlash('message', 'Модельный ряд создан.');
                $this->redirect(array('edit', 'id'=>$model->id));
            }
        }
        
        $form = new STabbedForm('application.modules.admin.views.modelline.form', $model);
        $form->formWidget = 'ext.yiiext.sidebartabs.CJuiTabs';
        $form->summaryOnEachTab = true;

        $criteria = new CDbCriteria;
        $criteria->order = 'parent, lft';
        $criteria->condition = 'level = 1';
        $groups = ModelLine::model()->findAll($criteria);
        
        $this->sidebarContent = $this->renderPartial('_sidebar', array(
                'model'=>$model, 'groups'=>$groups
        ), true);
        
        $this->render('edit', array('model'=>$model, 'form' => $form), false, true);
    }*/
    
    public function actionEdit($id)
    {
        $message = '';
        $fieldsShortInfo=array('top_text','bottom_text');
        $model = ModelLine::model()->findByPk($id);
        if (!$model)
	    $this->render('application.modules.admin.views.default.error', array('error' => 'Категория не найдена.'));
        if(!empty($_POST['ModelLine'])) {
            $editFieldsMessage=Changes::getEditMessage($model,$_POST['ModelLine'],$fieldsShortInfo);
            if (!empty($editFieldsMessage)){
                $message.= 'Редактирование модельного ряда "'.$model->name.'", ';
                $message.= $editFieldsMessage;
            }
            $model->attributes = $_POST['ModelLine'];
            if($model->validate()) {
                $model->saveNode();
                if(!empty($message)) Changes::saveChange($message);
                Yii::app()->user->setFlash('message', 'Модельный ряд сохранен.');
            }
        }
        $form = new STabbedForm('application.modules.admin.views.modelline.form', $model);
        $form->formWidget = 'ext.yiiext.sidebartabs.CJuiTabs';
        $form->summaryOnEachTab = true;
        
        /*
        $criteria = new CDbCriteria;
        $criteria->order = 'parent, lft';
        $criteria->condition = 'level = 1';
        $groups = ModelLine::model()->findAll($criteria);
        */
        $groups = $model->children()->findAll();
        
        $this->sidebarContent = $this->renderPartial('_sidebar', array(
                'model'=>$model, 'groups'=>$groups
        ), true);
        
        $this->render('edit', array('model'=>$model, 'form' => $form), false, true);
    }
    
    public function actionMoveNode()
    {
        $pos    = (int) $_GET['position'];
        $node   = ModelLine::model()->findByPk($_GET['id']);
        $target = ModelLine::model()->findByPk($_GET['ref']);
        
        if($pos > 0) {
            $childs = $target->children()->findAll();
            if(isset($childs[$pos-1]) && $childs[$pos-1]['id'] != $node->id)
                  $node->moveAfter($childs[$pos-1]);
        } else
            $node->moveAsFirst($target);
    }
    
    public function actionDelete($id)
    {
        $model = ModelLine::model()->findByPk($id);
        $message = 'Удален модельный ряд "'.$model->name;
        if (!$model)
	    $this->render('application.modules.admin.views.default.error', array('error' => 'Модельный ряд не найден.'));
        
        $model->deleteNode();
        Changes::saveChange($message);
        Yii::app()->user->setFlash('message', 'Модельный ряд удален.');
        $this->redirect(array('index'));        
    }
}

