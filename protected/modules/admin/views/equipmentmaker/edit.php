<?php
Yii::app()->getClientScript()->registerCss('infoStyles', "
	table.imagesList {
		float: left;
		width: 45%;
		min-width:250px;
		margin-right: 15px;
		margin-bottom: 15px;
	}
	div.MultiFile-list {
		margin-left:190px
	}
");

$name = 'Создание производителя техники';
$submit_text = 'Создать';
$action = '/admin/equipmentmaker/create/';
if (!empty($model->id)) {
    $submit_text = 'Сохранить';
    $name = 'Редактирование производителя техники';
    $action = '/admin/equipmentmaker/edit/id/'.$model->id;
}
$this->breadcrumbs = array(
    'Home'=>$this->createUrl('/admin/'),
    'Сайт'=>Yii::app()->createUrl(''),
    'Производители'=>Yii::app()->createUrl('/admin/equipmentmaker/'),
    $name
);

$alertMsg = Yii::app()->user->getFlash('message');
$errorMsg = Yii::app()->user->getFlash('error');
?>
<span class="admin-btn-wrapper">
    <?php if(!empty($model->id)): ?>
    <div class="admin-btn-one">
        <span class="admin-btn-del"></span>
        <?php echo CHtml::link('Удалить', '/admin/equipmentmaker/delete/id/'.$model->id, array('class' => 'btn-admin')); ?>
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
                $form_view = $this->beginWidget('CActiveForm', array(
                    'id'=>'EquipmentMaker',
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
            
                $tabs=array(
                    'Общая информация'=>$this->renderPartial('_general', array('model'=>$model,'form_view'=>$form_view), true),
                    'Логотип' => $this->renderPartial('_image', array('model'=>$model,'form_view'=>$form_view), true),
                    'meta-информация' => $this->renderPartial('_meta', array('model'=>$model,'form_view'=>$form_view), true),
                );
                
                $errorSummary = $form_view->errorSummary($model)."\n";
                foreach ($tabs as &$tab)
                    $tab = $errorSummary.$tab;

		$this->beginWidget('ext.yiiext.sidebartabs.SAdminSidebarTabs', array('tabs'=>$tabs));
                $this->endWidget();
                $this->endWidget();
            ?>
        </div>
    </div>
    
    <div class="right">
        <?php echo $this->sidebarContent; ?>
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
        document.location.href = "<?php echo Yii::app()->getBaseUrl(true) ?>/admin/equipmentmaker/";
    });
});
</script>

