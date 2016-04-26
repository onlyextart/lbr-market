<div class="login-big-wrapper">
    <div class="login-big-header">
          <h1>Авторизация</h1>
    </div>
    <div class="login-big-data">
        <div class="form wide">
        <?php
            if(!Yii::app()->user->isGuest) {
                $this->redirect('/');
            } else {
                $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'login-form-big',
                    'enableClientValidation'=>true,
                    'clientOptions'=>array(
                        'validateOnSubmit'=>true,
                    ),
                    'htmlOptions'=>array('onsubmit'=>"yaCounter30254519.reachGoal('vhod'); ga('send','event','action','vhod'); return true;")
                ));
        ?>
                <?php echo CHtml::errorSummary($model, ''); ?>
                <div class="row">
                        <?php echo $form->labelEx($model,'username'); ?>
                        <?php echo $form->textField($model,'username'); ?>
                </div>
                <div class="row">
                        <?php echo $form->labelEx($model,'password'); ?>
                        <?php echo $form->passwordField($model,'password'); ?>
                </div>
                <div class="clearfix"></div>
                <?php echo CHtml::submitButton('Войти', array('class'=>'btn')); ?>
                <div id='login-link'>
                    <?php echo CHtml::link('Регистрация',array('site/registration'));?>
                    <br>
                    <?php echo CHtml::link('Восстановление доступа',array('site/restore'));?>
                </div>
        <?php 
        $this->endWidget();    
        }
        ?>
        </div><!-- form -->
    </div>
</div>
<script>
    $(document).ready(function($){
        <?php if ($mess) :?>
            alertify.success('<?php echo $mess; ?>');
        <?php endif; ?>
    });
</script>
