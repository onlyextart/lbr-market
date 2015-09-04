<?php
    $name = 'Журнал редактирования - подробная информация';
    $this->breadcrumbs = array(
        'Home'=>$this->createUrl('/admin/'),
        'Сайт'=>Yii::app()->createUrl(''),
        'Журнал редактирования'=>Yii::app()->createUrl('/admin/changes/'),
        $name
    );
?>
<span class="admin-btn-wrapper">
    
    <div class="admin-btn-one">
        <span class="admin-btn-back"></span>
        <?php echo CHtml::link('Назад', '/admin/changes/index', array('id'=>'close-change','class' => 'btn-admin')); ?>
    </div>
</span>
<h1><?php echo $name; ?></h1>
<div class="total">
    <div class="left">
        <div class="form wide padding-all user-wrapper">
        <?php 
            $form = $this->beginWidget('CActiveForm', array(
                'id'=>'change-form',
               
            ));

            echo CHtml::openTag('div', array('class'=>'row'));
            echo $form->labelEx($model, 'id');
            echo $form->textField($model, 'id', array('disabled'=>'true'));
            echo CHtml::closeTag('div');
            
            echo CHtml::openTag('div', array('class'=>'row'));
            echo $form->labelEx($model, 'date');
            echo $form->textField($model, 'date', array('disabled'=>'true'));
            echo CHtml::closeTag('div');
            
            echo CHtml::openTag('div', array('class'=>'row'));
            echo $form->labelEx($model, 'description');
            echo $form->textArea($model, 'description', array('disabled'=>'true','class'=>'description'));
            echo CHtml::closeTag('div');
            
            echo CHtml::openTag('div', array('class'=>'row'));
            echo $form->labelEx($model, 'user_name');
            echo CHtml::textField('user_name', Changes::getAuthUser($model->user_id), array('disabled'=>true));
            echo CHtml::closeTag('div');
            
            $this->endWidget();
            ?>
        </div>
    </div>
    <div class="right">
       
    </div>
</div>
