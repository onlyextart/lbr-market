<?php
/*
 * $model модель групп меню
 */
if($model->isNewRecord){
    $pageHeader = 'Создание новой группы';
}
else{
    $pageHeader = 'Редактирование группы "'.$model->name.'"';
}
?>
<h2>
    <?php echo $pageHeader ?>
</h2>
<div class="form">
    <?php $form = $this->beginWidget('CActiveForm'); ?>
    <div class="row">
        <?php echo $form->error($model, 'name'); ?>
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name'); ?>
    </div>
    <div class="row">
        <?php echo $form->error($model, 'description'); ?>
        <?php echo $form->labelEx($model, 'description'); ?>
        <?php echo $form->textarea($model, 'description'); ?>
    </div>
    <div class="row" style="position: relative;">
        <?php echo $form->error($model, 'color'); ?>
        <?php echo $form->labelEx($model, 'color'); ?>
        <?php $this->widget('ext.jPickerWidget.JPickerWidget', array('inputID'=>'color'));?>
        <?php echo $form->textField($model, 'color', array('size'=>8, 'id'=>'color',)); ?>
    </div>
    <div class="row">
        <?php echo CHtml::SubmitButton($model->isNewRecord?'Создать':'Сохранить'); ?>
    </div> 
    <?php $this->endWidget(); ?>
</div>
