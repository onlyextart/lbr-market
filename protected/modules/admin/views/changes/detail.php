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
            
            $this->endWidget();
            ?>
        </div>
    </div>
    <div class="right">
       
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
    
    editUser.data = {
        userNotActivated:'<?php echo User::USER_NOT_ACTIVATED?>',
        userNotConfirmed: '<?php echo User::USER_NOT_CONFIRMED?>',
        userActive : '<?php echo User::USER_ACTIVE?>',
        userWarning : '<?php echo User::USER_WARNING?>',
        userTemporaryBlocked : '<?php echo User::USER_TEMPORARY_BLOCKED?>',
        userBlocked : '<?php echo User::USER_BLOCKED?>',
    };
    editUser.initCalendar();
    editUser.editStatus();
    
    $('#close-user').click(function() {
        document.location.href = "<?php echo Yii::app()->getBaseUrl(true) ?>/admin/user/";
    });
    
    $( "#save-btn" ).click(function() {
        $('form').submit();
    });
    
    var labels=<?php echo json_encode(UserCountry::model()->getAllLabels());?>;
     $("#country_user").change(function(){
        country_id=$('#country_user').val();
        $("#label_inn").text(labels[country_id]+" ");
    });
});
</script>