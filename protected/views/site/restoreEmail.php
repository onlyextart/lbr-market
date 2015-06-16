<?php
/* @var $this SiteController */
/* @var $model RestoreEmailForm */
/* @var $form CActiveForm  */

$this->pageTitle='Восстановление доступа';
$mess = Yii::app()->user->getFlash('message');
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
                    'id'=>'restore-form-email',
                    'action'=>$action,
                    'enableClientValidation'=>true,
                    'clientOptions'=>array(
                        'validateOnSubmit'=>true,
                    ),
                ));
    
//            if ($mess = Yii::app()->user->getFlash('message')){
//                echo '<div class="message success">'.$mess.'</div>';
//            }
        ?>
        <?php echo CHtml::errorSummary($form_email); ?>
	<div class="row">
		<?php echo $form->labelEx($form_email,'email'); ?>
		<?php echo $form->textField($form_email,'email'); ?>
	</div>
        <div class="clearfix"></div>
        <?php echo CHtml::submitButton('Отправить', array('class'=>'btn')); ?>
        
<?php $this->endWidget();    

?>
</div><!-- form -->
</div>
</div>
