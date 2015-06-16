<?php
    $err = Yii::app()->user->getFlash('error');
?>
<script>
     alertify.set({ delay: 6000 });
        <?php if ($err) :?>
            alertify.error('<?php echo $err; ?>');
        <?php endif; ?>

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
        'id'=>'registration-form1',
        'action'=>$action,
        'enableClientValidation'=>true,
        'enableAjaxValidation'=>true,
        'clientOptions'=>array(
                'validateOnSubmit'=>true,
                'validateOnChange'=>true,
        ),
        'htmlOptions'=>array('autocomplete'=>'off'),
    )); ?>
    <?php echo CHtml::errorSummary($model_form_start); ?>
    
    <div class="row radiobutton">
            <?php echo $form->labelEx($model_form_start,'organization_type',User::$userType); ?>
                <?php echo $form->radioButtonList($model_form_start,'organization_type',User::$userType); ?>
    </div>
    
    
    <div class="clearfix"></div>
    <?php echo CHtml::submitButton('Продолжить', array('class'=>'btn step1')); ?>
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

