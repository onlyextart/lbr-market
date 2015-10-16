

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
             $tabs=array(
                    'Общая информация'=>$this->renderPartial('_general', array('model'=>$model,'form'=>$form), true),
                    'Производители запчастей' => $this->renderPartial('_makers', array('model'=>$model,'form'=>$form,'data'=>$makers, 'model_maker'=>$model_maker, 'selected_makers'=>$selected_makers), true),
              );
                
             $this->beginWidget('ext.yiiext.sidebartabs.SAdminSidebarTabs', array('tabs'=>$tabs));
             $this->endWidget();
                
            $this->endWidget(); ?>
        </div>
    </div>
    <div class="right">
        <?php 
            echo $this->sidebarContent; 
        ?>
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

