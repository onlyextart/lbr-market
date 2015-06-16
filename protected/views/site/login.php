<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle='Магазин - Вход';
$mess = Yii::app()->user->getFlash('message');
?>
<script>
     alertify.set({ delay: 6000 });
        <?php if ($mess) :?>
            alertify.success('<?php echo $mess; ?>');
        <?php endif; ?>
</script>
<div class="login-big-wrapper">
    <div class="login-big-header">
          <h1>Авторизация</h1>
    </div>
    <div class="login-big-data">
        <div class="form wide">
        <?php
            if(!Yii::app()->user->isGuest){
                $this->redirect('/');
            } else {
                $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'login-form-big',
                    'enableClientValidation'=>true,
                    'clientOptions'=>array(
                        'validateOnSubmit'=>true,
                    ),
                ));
    
//            if ($mess = Yii::app()->user->getFlash('message')){
//                echo '<div class="message success">'.$mess.'</div>';
//            }
        ?>
        <?php echo CHtml::errorSummary($model); ?>
	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
	</div>
	<!--div class="row rememberMe">
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->label($model,'rememberMe'); ?>
	</div-->
	<!--div class="row buttons">
		<?php echo CHtml::submitButton('Войти', array('class'=>'btn')); ?>
	</div-->
        <div class="clearfix"></div>
        <?php echo CHtml::submitButton('Войти', array('class'=>'btn')); ?>
<!--        <div class="reg-link">
            <a href="#">Регистрация</a>
        </div>-->
        <div id='login-link'>
            <?php echo CHtml::link('Регистрация',array('site/registration'));?>
            <br>
            <?php echo CHtml::link('Восстановление доступа',array('site/restore'));?>
        </div>
<?php $this->endWidget();    
}
?>
</div><!-- form -->
</div>
</div>
<?php if(Yii::app()->user->isGuest): ?>

<!--div class="restore">
<?php echo CHtml::link('Восстановление доступа', array('/site/restore'), array('class' => 'color')); ?>
</div>
<div class="registration">
<?php echo CHtml::link('Заявка на регистрацию', array('/site/registration'), array('class' => 'color')); ?>
</div-->

<?php endif; ?>
