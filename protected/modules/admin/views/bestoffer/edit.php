

<script type="text/javascript" charset="utf-8">
    $().ready(function() {
        var elf = $('#elfinder').elfinder({
            // lang: 'ru',             // language (OPTIONAL)
            url : 'php/connector.php'  // connector URL (REQUIRED)
        }).elfinder('instance');            
    });
</script>
<style>
    .ui-dialog{z-index:1002!important;}
    .ui-button-icon-only .ui-icon {left: -1px; top: -1px;}
    .el-paddinginput input {
	margin :0 1px 0 0;
	border:1px solid #ccc;
        height: 14px;
        width: 32px;
        font-size: 10px;
}
</style>
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

$name = 'Создание спецпредложения';
$submit_text = 'Создать';
$action = '/admin/bestoffer/create/';
if (!empty($model->id)) {
    $submit_text = 'Сохранить';
    $name = 'Редактирование спецпредложения';
    $action = '/admin/bestoffer/edit/id/'.$model->id;
}
$this->breadcrumbs = array(
    'Home'=>$this->createUrl('/admin/'),
    'Сайт'=>Yii::app()->createUrl(''),
    'Спецпредложения'=>Yii::app()->createUrl('/admin/bestoffer/'),
    $name
);

$alertMsg = Yii::app()->user->getFlash('message');
$errorMsg = Yii::app()->user->getFlash('error');
?>
<span class="admin-btn-wrapper">
    <?php if(!empty($model->id)): ?>
    <div class="admin-btn-one">
        <span class="admin-btn-del"></span>
        <?php echo CHtml::link('Удалить', '/admin/bestoffer/delete/id/'.$model->id, array('class' => 'btn-admin')); ?>
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
<h1><?php echo $name; ?></h1>
<div class="total">
    <div class="left">
        <div class="form wide wide-wrapper padding-all">
            <?php 
            $form = $this->beginWidget('CActiveForm', array(
                'id'=>'bestoffer-form',
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
            if (!empty($model->id)&&$model->published){
                echo CHtml::link('Предварительный просмотр', '/seasonalsale/index/id/'.$model->id, array('class' => 'link_view','target'=>'_blank')); 
            }
            echo CHtml::closeTag('div');
            
            echo CHtml::openTag('div', array('class'=>'row'));
            echo $form->labelEx($model, 'description');
            $this->widget('ext.elrtef.elRTE', array( 
                    'model' => $model,
                    'attribute' => 'description',
                    //'name' => 'text',
                    'htmlOptions' => array('height' => '400'),
                    'options' => array(
                            'doctype'=>'js:\'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">\'',
                            'cssClass' => 'el-rte',
                            'cssfiles' => array('css/elrte-inner.css'),
                            'allowSource' => true,
                            'lang' => 'ru',
                            'height' => 400,
                            'fmAllow'=>true, //if you want to use Media-manager
                            'fmOpen'=>'js:function(callback) {$("<div id=\"elfinder\" />").elfinder(%elfopts%);}',//here used placeholder for settings
                            'toolbar' => 'maxi',
                    ),
                    'elfoptions' => array( //elfinder options
                        'url'=>'auto',  //if set auto - script tries to connect with native connector
                        'passkey'=>'mypass', //here passkey from first connector`s line
                        'lang'=>'ru',
                        'dialog'=>array('width'=>'700','modal'=>true,'title'=>'Media Manager'),
                        'closeOnEditorCallback'=>true,
                        'editorCallback'=>'js:callback'
                    ),
                    )
            );
            
           echo CHtml::closeTag('div');
            
            echo CHtml::openTag('div', array('class'=>'row'));
            echo $form->labelEx($model, 'level');
            echo $form->textField($model, 'level');
            echo $form->error($model, 'level'); 
            echo CHtml::closeTag('div');
            
            // Upload button
            echo CHtml::openTag('div', array('class'=>'row'));
            echo $form->error($model, 'img'); 
            echo CHtml::label('Выберите изображение', 'img');
            echo $form->fileField($model, 'img');
            echo CHtml::label('*размер 770x250, не больше 1Мб', 'img', array('class'=>'note'));
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
        document.location.href = "<?php echo Yii::app()->getBaseUrl(true) ?>/admin/bestoffer/";
    });
});
</script>

