<?php
    $err = Yii::app()->user->getFlash('error');
?>
<script>
     alertify.set({ delay: 6000 });
        <?php if ($err) :?>
            alertify.error('<?php echo $err; ?>');
        <?php endif; ?>
    }); 
</script>
<div class="reg-wrapper">
<div class='reg-header'>
    <h1>Регистрация</h1>
</div>
<div class='reg-data'>
    <div class='form wide'>
<!--<span class='note'>поля, обозначеннные *, обязательны для заполнения</span>-->
<?php
$action = '/site/registration/';

$form=$this->beginWidget('CActiveForm', array(
        'id'=>'registration-form-ind',
        'action'=>$action,
        'enableClientValidation'=>true,
        'enableAjaxValidation'=>true,
        'clientOptions'=>array(
                'validateOnSubmit'=>true,
                'validateOnChange'=>true,
        ),
        'htmlOptions'=>array('autocomplete'=>'off'),
    )); ?>
    <?php echo CHtml::errorSummary($model_form); ?>
    
    
    <div class="row">
            <?php echo $form->labelEx($model_form,'name'); ?>
            <?php echo $form->textField($model_form,'name'); ?>
    </div>
    <div class="row">
            <?php echo $form->labelEx($model_form,'company'); ?>
            <?php echo $form->textField($model_form,'company'); ?>
    </div>
    <div class="row">
            <?php echo $form->labelEx($model_form,'login'); ?>
            <?php echo $form->textField($model_form,'login'); ?>
    </div>
    <div class="row">
            <?php echo $form->labelEx($model_form,'password'); ?>
            <?php echo $form->passwordField($model_form,'password',array('autocomplete'=>'off')); ?>
    </div>
    <div class="row">
            <?php echo $form->labelEx($model_form,'password_confirm'); ?>
            <?php echo $form->passwordField($model_form,'password_confirm',array('autocomplete'=>'off')); ?>
    </div>
    <div class="row email">
            <?php echo $form->labelEx($model_form,'email'); ?>
            <?php echo $form->textField($model_form,'email'); ?>
            <div class="note">нужен для активации учетной записи</div>
    </div>
    <div class="row">
            <?php echo $form->labelEx($model_form,'filial'); ?>
            <?php echo $form->dropDownList($model_form,'filial',User::model()->getAllFilials(),array('class'=>'reg-filial')); ?>
    </div>
    <div class="row phone">
            <?php echo $form->labelEx($model_form,'phone'); ?>
            <?php echo $form->textField($model_form,'phone'); ?>
            <div class="note">пример: +7(4722)402104</div>
    </div>
    <div class="row">
            <?php echo $form->labelEx($model_form,'country'); ?>
            <?php echo $form->textField($model_form,'country'); ?>
    </div>
    <div class="row">
            <?php echo $form->labelEx($model_form,'region'); ?>
            <?php echo $form->textField($model_form,'region'); ?>
    </div>
    <div class="row">
            <?php echo $form->labelEx($model_form,'locality_type'); ?>
            <?php echo $form->dropDownList($model_form,'locality_type',array('город'=>'город','поселок'=>'поселок')); ?>
            <?php echo $form->textField($model_form,'locality_name',array('id'=>'locality_name')); ?>
    </div>
    <div class="row captcha">
    <?php 
        if(CCaptcha::checkRequirements() && Yii::app()->user->isGuest){
              echo $form->labelEx($model_form,'verifyCode');
              echo $form->textField($model_form,'verifyCode',array('value'=>''));?>
              <div id='pict_captcha'><?php $this->widget('CCaptcha');?></div>
              <?php 
            
        }
    ?>
    </div>
    
    
    <div class="clearfix"></div>
    <?php echo CHtml::submitButton('Отправить',array('onsubmit'=>"ga('send', 'pageview', '/registrationfiz'); yaCounter30254519.reachGoal('registrationfiz'); return true;",'class'=>'btn')); ?>
    <div id='reg-link'>
        <?php echo CHtml::link('Авторизация',array('site/login'));?>
        <br>
        <?php echo CHtml::link('Восстановление доступа',array('site/restore'));?>
    </div>
    <?php $this->endWidget();  
?>
        </div>
    
  
    </div>
</div>
<!--
<script>
$(document).ready(function(){
   //dynamic mask for phone 
   $('.phone').inputmask({"mask":"+9{1,3}(9{2,4})9{5,7}"}); 
});
</script>-->