<?php

$name = 'Создание производителя техники в категории';
$submit_text = 'Создать';
$action = '/admin/categoryseo/create/';
if (!empty($model->id)) {
    $submit_text = 'Сохранить';
    $name = 'Редактирование "'.$model->category->name.' - '.$model->equipmentMaker->name.'"';
    $action = '/admin/categoryseo/edit/id/'.$model->id;
}
$this->breadcrumbs = array(
    'Home'=>$this->createUrl('/admin/'),
    'Каталог'=>Yii::app()->createUrl(''),
    'Производители техники в категории'=>Yii::app()->createUrl('/admin/categoryseo/'),
    $name
);

$alertMsg = Yii::app()->user->getFlash('message');
$errorMsg = Yii::app()->user->getFlash('error');
?>
<span class="admin-btn-wrapper">
    <?php if(!empty($model->id)): ?>
    <!--div class="admin-btn-one">
        <span class="admin-btn-del"></span>
        <?php echo CHtml::link('Удалить', '/admin/categoryseo/delete/id/'.$model->id, array('class' => 'btn-admin')); ?>
    </div-->
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
                    'id'=>'CategorySeo',
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
                    'Общая информация'=>$this->renderPartial('_general', array('model'=>$model,'form'=>$form), true),
                );
                
                $errorSummary = $form->errorSummary($model)."\n";
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
        document.location.href = "<?php echo Yii::app()->getBaseUrl(true) ?>/admin/categoryseo/";
    });
});
</script>

