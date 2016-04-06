<?php
    $err = Yii::app()->user->getFlash('error');
    Yii::app()->controller->createAction('captcha')->getVerifyCode(true);
?>
<script>
$(function(){ 
    var labels=<?php echo json_encode(UserCountry::model()->getAllLabels());?>;
     alertify.set({ delay: 6000 });
        <?php if ($err) :?>
            alertify.error('<?php echo $err; ?>');
        <?php endif; ?> 
     $(document).ready(function(){
            $("#country_user").change(function(){
                country_id=$('#country_user').val();
                $("#label_inn").text(labels[country_id]+" ");
         });
     });
     
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
        'id'=>'registration-form-legalPerson',
        'action'=>$action,
        'enableClientValidation'=>true,
        'enableAjaxValidation'=>true,
        'clientOptions'=>array(
                'validateOnSubmit'=>true,
                'validateOnChange'=>true,
        ),
        'htmlOptions'=>array('autocomplete'=>'off','onsubmit'=>"yaCounter30254519.reachGoal('registrationur'); ga('send','pageview','/registrationur'); return true;"),
    )); ?>
    <?php echo CHtml::errorSummary($model_form); ?>
    
    <div class="row">
            <?php echo $form->labelEx($model_form,'company'); ?>
            <?php echo $form->textField($model_form,'company'); ?>
    </div>
    <div class="row">
            <?php echo $form->labelEx($model_form,'country_id'); ?>
            <?php echo $form->dropDownList($model_form,'country_id',UserCountry::model()->getAllCountries(),array('id'=>'country_user')) ?>
    </div>
    <div class="row">
            <?php echo $form->labelEx($model_form,'organization_address'); ?>
            <?php echo $form->textArea($model_form,'organization_address'); ?>
    </div>
    <div class="row">
            <label class="required" for="RegFormLegalPerson_inn">
                <span id="label_inn">
                    <?php echo empty($model_form->country_id)? UserCountry::model()->getCountryLabel(UserCountry::RUSSIA):UserCountry::model()->getCountryLabel($model_form->country_id) ;?>  
                </span>
                <span class="required">*</span>
            </label>
            <?php echo $form->textField($model_form,'inn'); ?>
    </div>
    <div class="row">
            <?php echo $form->labelEx($model_form,'name'); ?>
            <?php echo $form->textField($model_form,'name'); ?>
    </div>
    <div class="row">
            <?php echo $form->labelEx($model_form,'login'); ?>
            <?php echo $form->textField($model_form,'login'); ?>
    </div>
    <div class="row">
            <?php echo $form->labelEx($model_form,'password'); ?>
            <?php echo $form->passwordField($model_form,'password',array('value'=>'')); ?>
    </div>
    <div class="row">
            <?php echo $form->labelEx($model_form,'password_confirm'); ?>
            <?php echo $form->passwordField($model_form,'password_confirm',array('value'=>'')); ?>
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
    
    <div class="row captcha">
    <?php 
        if(CCaptcha::checkRequirements() && Yii::app()->user->isGuest){
              echo $form->labelEx($model_form,'verifyCode');
              echo $form->textField($model_form,'verifyCode',array('value'=>''));?>
                <div id='pict_captcha'>
                    <?php $this->widget('CCaptcha', 
                            array(
                                'captchaAction' => 'site/captcha',
                                'clickableImage' => true, 
                                'showRefreshButton' => false,
                                'imageOptions'=>array('style'=>'border:none;cursor:pointer',
                                    'alt'=>'Изображение с кодом валидации',
                                    'title'=>'Обновить код'
                                )
                            )
                        );
                  ?>
                </div>
              <?php 
            
        }
    ?>
    </div>
    
    
    <div class="clearfix"></div>
    <?php echo CHtml::submitButton('Отправить', array('class'=>'btn')); ?>
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
