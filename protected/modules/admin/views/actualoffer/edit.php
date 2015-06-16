<?php
Yii::app()->getClientScript()->registerCss('infoStyles', "
	table.imagesList {
		float: left;
		width: 45%;
		min-width:250px;
		margin-right: 15px;
		margin-bottom: 15px;
	} 
");

$name = 'Создание';
$submit_text = 'Создать';
$action = '/admin/actualoffer/create/';
if (!empty($model->id)) {
    $submit_text = 'Сохранить';
    $name = 'Редактирование';
    $action = '/admin/actualoffer/edit/id/'.$model->id;
}
$this->breadcrumbs = array(
    'Home'=>$this->createUrl('/admin/'),
    'Сайт'=>Yii::app()->createUrl(''),
    'Актуальные предложения'=>Yii::app()->createUrl('/admin/actualoffer/'),
    $name
);

$alertMsg = Yii::app()->user->getFlash('message');
$errorMsg = Yii::app()->user->getFlash('error');
?>
<span class="admin-btn-wrapper">
    <?php if(!empty($model->id)): ?>
    <div class="admin-btn-one">
        <span class="admin-btn-del"></span>
        <?php echo CHtml::link('Удалить', '/admin/actualoffer/delete/id/'.$model->id, array('class' => 'btn-admin')); ?>
    </div>
    <?php endif; ?>
    <div class="admin-btn-one">
        <span class="admin-btn-save"></span>
        <?php echo CHtml::button($submit_text, array('id' => 'save-btn', 'class'=>'btn-admin')); ?>
    </div>
    <div class="admin-btn-one">
        <span class="admin-btn-close"></span>
        <?php echo CHtml::button('Закрыть', array('id'=>'close-btn', 'class'=>'btn-admin')); ?>
    </div>
</span>
<h1><?php echo $name.' актуального предложения'; ?></h1>
<div class="total">
    <div class="left">
        <div class="form wide wide-wrapper padding-all">
            <?php 
            $form = $this->beginWidget('CActiveForm', array(
                'id'=>'actualoffer-form',
                'action'=>$action,
                'htmlOptions'=>array('enctype'=>'multipart/form-data'),
                'enableClientValidation' => true,        
                'clientOptions'=>array(
                    'validateOnSubmit'=>true,
                    'validateOnChange' => true,
                    'afterValidate'=>'js:function( form, data, hasError ) 
                    {     
                        if( hasError ){
                            return false;
                        }
                        else{
                            return true;
                        }
                    }'
                ),
            )); 
            
            echo $form->errorSummary($model);
            
            echo CHtml::openTag('div', array('class'=>'row'));
            echo $form->labelEx($model, 'name');
            echo $form->textField($model, 'name');
            echo $form->error($model, 'name'); 
            echo CHtml::closeTag('div');
            
            echo CHtml::openTag('div', array('class'=>'row'));
            echo $form->labelEx($model, 'published');
            echo $form->dropDownList($model, 'published', array('0'=>'Нет','1'=>'Да'));
            echo $form->error($model, 'published'); 
            echo CHtml::closeTag('div');
            
            echo CHtml::openTag('div', array('class'=>'row'));
            echo $form->labelEx($model, 'level');
            echo $form->textField($model, 'level');
            echo $form->error($model, 'level'); 
            echo CHtml::closeTag('div');
            
            /*echo CHtml::openTag('div', array('class'=>'row'));
            echo $form->error($model, 'level'); 
            echo $form->labelEx($model, 'level');
            echo $form->dropDownList($model, 'level');
            echo CHtml::closeTag('div');*/
            
            // Upload button
            echo CHtml::openTag('div', array('class'=>'row'));
            echo $form->error($model, 'img'); 
            echo CHtml::label('Выберите изображение', 'img');
            echo $form->fileField($model, 'img');
            //echo $form->error($model, 'img'); 
            echo CHtml::label('*размер 380x250', 'img', array('class'=>'note'));
            echo CHtml::closeTag('div');

            // Image
            if(!empty($model->img)) {   
                $this->widget('zii.widgets.CDetailView', array(
                    'data'=>$model,
                    'htmlOptions'=>array(
                        'class'=>'detail-view imagesList',
                    ),
                    'attributes'=>array(
                        array(
                            'label'=>'Изображение',
                            'type'=>'raw',
                            'value'=>CHtml::link(
                                CHtml::image(
                                    $model->img,
                                    CHtml::encode('test'),
                                    array('style'=>'max-height: 150px')
                                ),
                                $model->img,
                                array('target'=>'_blank', 'class'=>'pretty')
                            ),
                        ),
                    ),
                ));
            }

            // Fancybox ext
            $this->widget('application.extensions.fancybox.EFancyBox', array(
                'target'=>'a.pretty',
                'config'=>array(),
            ));

            $this->endWidget(); ?>
        </div>
    </div>
</div>
<script>
$(function(){
    alertify.set({ delay: 6000 });
    <?php if ($alertMsg) :?>
        alertify.success('<?php echo $alertMsg; ?>');
    <?php elseif ($errorMsg): ?>
        alertify.error('<?php echo $errorMsg; ?>');
    <?php endif; ?>
        
    $( "#save-btn" ).click(function() {
        $('form').submit();
    });
    
    $('#close-btn').click(function(){
        document.location.href = "<?php echo Yii::app()->getBaseUrl(true) ?>/admin/actualoffer/";
    });
});
</script>

