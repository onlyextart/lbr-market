<?php
    $err = Yii::app()->user->getFlash('error');
?>
<script>
     alertify.set({ delay: 6000 });
        <?php if ($err) :?>
            alertify.error('<?php echo $err; ?>');
        <?php endif; ?>   
</script>
<div class="quick-wrapper">
    <center>
<div class='quick-header'>
    <h1>Оставьте заявку</h1>
</div>
 
<div class='quick-data'>
    
    <div class='form wide'>
        
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'quick-form',
    'enableAjaxValidation'=>true,
    
    'htmlOptions'=>array('enctype' => 'multipart/form-data'),
    'clientOptions'=>array(
                        'validateOnSubmit'=>true,),
    )); ?>

    <?php echo $form->errorSummary($model); ?>
    
    <div class="row">
        <?php echo $form->labelEx($model,'name'); ?>
        <?php echo $form->textField($model,'name'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model,'email'); ?>
        <?php echo $form->textField($model,'email'); ?>
    </div>  
    
    <div class="row">
        <?php echo $form->labelEx($model,'phone'); ?>
        <?php echo $form->textField($model,'phone'); ?>
    </div>
        
    <div class="row">
        <?php echo $form->labelEx($model,'region'); ?>
        <?php echo $form->textField($model,'region'); ?>
    </div>
        
    <div class="row">
        <?php echo $form->labelEx($model,'organization'); ?>
        <?php echo $form->textField($model,'organization'); ?>
    </div> 
    
    <div class="row">
        <?php echo $form->labelEx($model,'body'); ?>
        <?php echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>80, 'style'=>'max-width: 570px;')); ?>
        
    </div>
<div id="attachments"></div>
<div class="row">
<?php echo $form->labelEx($model,'attachments'); ?>
<?php  $this->widget('CMultiFileUpload',
array(
      'model'=>$model,
      'attribute' => 'attachments',
      'accept'=> 'jpg,jpeg,png,doc,docx,pdf,txt,xls,xlsx,',
      'denied'=>'Прикреплять можно только форматы: jpg,jpeg,png,doc,docx,xls,xlsx,pdf,txt', 
      'max'=>4,
      'remove'=>'[x]',
      'duplicate'=>'Вы уже прикрепили файл с таким именем.',
      )
);?>
<div class="row">
<?php echo $form->error($model,'attachments'); ?>
</div>
    <div class="row captcha">
    <?php 
        if(CCaptcha::checkRequirements() && Yii::app()->user->isGuest){
              echo $form->labelEx($model,'verifyCode');
              echo $form->textField($model,'verifyCode',array('value'=>''));?>
              <div id='pict_captcha'><?php $this->widget('CCaptcha');?></div>
              <?php 
            
        }
    ?>
    </div>
    <div class="row submit">
        <?php echo CHtml::submitButton('Отправить', array('class'=>'btn')); ?>
    </div>

    
    
<?php $this->endWidget(); ?>
</div>
</div><!-- form -->
</div>
        </center>
    </div>



