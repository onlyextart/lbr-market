<?php
/* @var $this SiteController */
/* @var $model RestoreEmailForm */
/* @var $form CActiveForm  */

$this->pageTitle='Восстановление доступа: смена пароля';
$mess = Yii::app()->user->getFlash('restore');
?>
<script>
     alertify.set({ delay: 6000 });
        <?php if ($mess) :?>
            alertify.success('<?php echo $mess; ?>');
        <?php endif; ?>
</script>
<div class="restore-wrapper">
    <div class="restore-header">
          <h1>Восстановление доступа</h1>
    </div>
    <div class="restore-data">
        <div class="form wide">
        <?php
                $action = '/site/restore/';
            
                $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'restore-form-password',
                    'action'=>$action,
                    'enableClientValidation'=>true,
                    'clientOptions'=>array(
                        'validateOnSubmit'=>true,
                    ),
                    'htmlOptions'=>array('autocomplete'=>'off'),
                ));
    
        ?>
        <?php echo CHtml::errorSummary($form_password); ?>
	<div class="row">
		<?php echo $form->labelEx($form_password,'password_new'); ?>
		<?php echo $form->passwordField($form_password,'password_new'); ?>
	</div>
        <div class="row">
		<?php echo $form->labelEx($form_password,'password_confirm'); ?>
		<?php echo $form->passwordField($form_password,'password_confirm'); ?>
	</div>
        <div class="row">
		<?php echo $form->hiddenField($form_password,'id'); ?>
	</div>
        <div class="clearfix"></div>
        <?php echo CHtml::submitButton('Изменить пароль', array('class'=>'btn')); ?>
        
<?php $this->endWidget();    

?>
</div><!-- form -->
</div>
</div>
