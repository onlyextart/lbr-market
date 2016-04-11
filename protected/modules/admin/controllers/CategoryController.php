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
        $this->render('edit', array('model'=>$model, 
                                    'form' => $form,
                                    'equipmentMaker'=>'',
                                    'equipmentMakerTop'=>''), false, true);
    }
    
    public function actionEdit($id)
    {
        $message='';
        $fieldsShortInfo=array('top_text','bottom_text');
        $model = Category::model()->findByPk($id);
        if (!$model)
	    $this->render('application.modules.admin.views.default.error', array('error' => 'Категория не найдена.'));
        //selected makers for view in top
        $selected_makers=$this->getIdMakers($id);
        
        if(!empty($_POST['Category'])) {
            $editFieldsMessage=Changes::getEditMessage($model,$_POST['Category'],$fieldsShortInfo);
            if (!empty($editFieldsMessage)){
                $message.= 'Редактирование категории "'.$model->name.'", ';
                $message.= $editFieldsMessage;
            }
            $model->attributes = $_POST['Category'];
            if($model->validate()) {
                
                $success=true;
                $connection=Yii::app()->db; 
                $transaction=$connection->beginTransaction();
                if($_POST['makers']!=$selected_makers){
                    if(!empty($selected_makers)){
                            $sql_delete="DELETE FROM category_makers_top WHERE category_id=".$id.";";
                            $rowCount=$connection->createCommand($sql_delete)->execute();
                            if ($rowCount==0){
                                $success=false;
                            }
                    }
                    if(!empty($_POST['makers'])){
                            foreach($_POST['makers'] as $maker_id){
                                $sql_insert="INSERT INTO category_makers_top(category_id,maker_id) VALUES('".$id."','".$maker_id."')";
                                $rowCount=$connection->createCommand($sql_insert)->execute();
                                if ($rowCount==0){
                                    $success=false;
                                }
                           }
                    }
                    if(empty($message)){
                        $message.= 'Редактирование категории "'.$model->name.'", ';
                    }
                    else{
                         $message.="; ";
                    }
                    $message.="изменен список топ-производителей";
                }
                
                if(!$model->saveNode()) $success=false;
                if($model->level != 1) {
                    $path = '';
                    $ancestors = $model->ancestors()->findAll(); //предки
                    foreach($ancestors as $ancestor) {
                        if($ancestor->level != 1) $path .= '/'.trim(Translite::rusencode($ancestor->name, '-'));
                    }
                    $model->path = $path.'/'.trim(Translite::rusencode($model->name, '-'));
                    if(!$model->saveNode()) $success=false;
                    $this->updateChildPath($model);
                }
                if ($success){
                    $transaction->commit();
                    if(!empty($message)) Changes::saveChange($message);
                    Yii::app()->user->setFlash('message', 'Категория сохранена.');
                }
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
        
        //$equipmentMaker = CategorySeo::model()->findAll('category_id=:id', array('id'=>$id));
        $criteria = new CDbCriteria();
        $criteria->with = array('equipmentMaker');
        $criteria->condition = 'category_id=:id';
        $criteria->params = array(':id'=>$id);

        $equipmentMaker = new CActiveDataProvider('CategorySeo', 
            array(
                'criteria'=>$criteria,
                'pagination'=>array(
                    'pageSize'=>'15'
                ),
                /*'sort'=>array(
                    'defaultOrder' => 'modelLine.name ASC',
                    'attributes'=>array(
                        'modelline_name' => array(
                            'asc' => $expr='modelLine.name',
                            'desc' => $expr.' DESC',
                        ),
                )),*/
            )
        );
        
        $criteriaTop = new CDbCriteria();
        $criteriaTop->with = array('maker');
        $criteriaTop->select='maker.name';
        $criteriaTop->distinct ='true';
        $criteriaTop->condition = 'category_id=:id';
        $criteriaTop->params = array(':id'=>$id);
        $criteriaTop->group='maker.name';
        $criteriaTop->order='maker.name';

        $equipmentMakerTop = new CActiveDataProvider('ModelLine', 
            array(
                'criteria'=>$criteriaTop,
                'pagination'=>array(
                    'pageSize'=>'15'
                ),
            )
        );
        
        $this->render('edit', array('model'=>$model, 
                    'equipmentMaker'=>$equipmentMaker, 
                    'equipmentMakerTop'=>$equipmentMakerTop), false, true);
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
        $message = 'Удалена категория "'.$model->name;
        if (!$model)
	    $this->render('application.modules.admin.views.default.error', array('error' => 'Категория не найдена.'));
        
        $model->deleteNode();
        Changes::saveChange($message);
        Yii::app()->user->setFlash('message', 'Категория удалена.');
        $this->redirect(array('index'));        
    }
    
    public function getIdMakers($category_id){
        $sql="SELECT maker_id FROM category_makers_top WHERE category_id=".$category_id.";";
        $connection=Yii::app()->db;
        $command=$connection->createCommand($sql);
        $makers=$command->queryColumn();
        return $makers;
    }
    
    public function actionGetModelLines(){
        $makerId=$_GET['makerId'];
        $categoryId=$_GET['categoryId'];
        if (!empty($categoryId)&&!empty($makerId)){
            $sql="SELECT id, name, catalog_top FROM model_line WHERE level=2 and maker_id=".$makerId." and category_id=".$categoryId;
            $command=Yii::app()->db->createCommand($sql);
            $dataReader=$command->query();
            $rows=$dataReader->readAll();
            echo json_encode($rows);
        }
    }
    
     public function actionSaveModelLines(){
          if(isset($_POST['modelLinesId_show'])){
            $criteria=new CDbCriteria();
            $criteria->addInCondition('id',$_POST['modelLinesId_show']);
            ModelLine::model()->updateAll(array('catalog_top'=>1),$criteria);
          }
          if(isset($_POST['modelLinesId_hide'])){
             $criteria=new CDbCriteria();
             $criteria->addInCondition('id',$_POST['modelLinesId_hide']);
             ModelLine::model()->updateAll(array('catalog_top'=>0),$criteria);
          }
         

    }
}

