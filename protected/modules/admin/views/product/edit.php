<?php

$submit_text = 'Сохранить';
$name = 'Редактирование запчасти';
$action = '/admin/product/edit/id/'.$model->id;

$this->breadcrumbs = array(
    'Home'=>$this->createUrl('/admin/'),
    'Каталог'=>Yii::app()->createUrl(''),
    'Запчасти'=>Yii::app()->createUrl('/admin/product/'),
    $name
);

$alertMsg = Yii::app()->user->getFlash('message');
$errorMsg = Yii::app()->user->getFlash('error');
?>
<span class="admin-btn-wrapper">
    <?php /*if(!empty($model->id)): ?>
    <div class="admin-btn-one">
        <span class="admin-btn-del"></span>
        <?php echo CHtml::link('Удалить', '/admin/product/delete/id/'.$model->id, array('class' => 'btn-admin')); ?>
    </div>
    <?php endif; */?>
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
                    'id'=>'product-form',
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
                    'Сборочный чертеж' => $this->renderPartial('_images', array('model'=>$model,'form_view'=>$form_view), true),
                    'Дополнительно' => $this->renderPartial('_addinfo', array('model'=>$model,'form_view'=>$form_view), true),
                    'Модельные ряды' => $this->renderPartial('_modellines', array('model'=>$model,'form_view'=>$form_view, 'data'=>$modellines), true),
                    //'Группа' => $this->renderPartial('_group', array('model'=>$model,'form_view'=>$form_view), true)
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
        
        <?php 
            echo $this->sidebarContent; 
//            echo CHtml::openTag('div',array('id'=>'group','style'=>'display:none;'));
//                echo $this->renderPartial('_group_tree',array('groups'=>$groups,'id'=>$id),true);
//            echo CHtml::closeTag('div');
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
        document.location.href = "<?php echo Yii::app()->getBaseUrl(true) ?>/admin/product/";
    });
    
//    $(".SidebarTabsControl a:last").click(function(){
//        $('div#group').show();
//    });
//    
//    $(".SidebarTabsControl a:lt(3)").click(function(){
//        $('div#group').hide();
//    });
    
});
</script>


