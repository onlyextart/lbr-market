<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle = 'Магазин - Вход';
?>
<div class="form">
    <?php
    if(!Yii::app()->user->isGuest):
        $this->redirect('/');
    else:
        $form = $this->beginWidget('CActiveForm', array(
            'id'=>'login-form',
            'enableClientValidation'=>true,
            'clientOptions'=>array(
                'validateOnSubmit'=>true,
            ),
        ));

        if ($mess = Yii::app()->user->getFlash('message')){
            echo '<div class="message success">'.$mess.'</div>';
        }
    ?>

        <h1>Вход в кабинет пользователя</h1>
        <div class="row">
            <?php echo $form->labelEx($model,'username'); ?>
            <?php echo $form->textField($model,'username'); ?>
            <?php echo $form->error($model,'username'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model,'password'); ?>
            <?php echo $form->passwordField($model,'password'); ?>
            <?php echo $form->error($model,'password'); ?>
        </div>
        <!--div class="row rememberMe">
            <?php echo $form->checkBox($model,'rememberMe'); ?>
            <?php echo $form->label($model,'rememberMe'); ?>
            <?php echo $form->error($model,'rememberMe'); ?>
        </div-->
        <div class="row buttons">
            <?php echo CHtml::submitButton('Войти', array('class'=>'btn')); ?>
        </div>
   <?php 
       $this->endWidget();    
    endif;
   ?>
</div>

<?php if(Yii::app()->user->isGuest): ?>
<!--div class="restore">
<?php echo CHtml::link('Восстановление доступа', array('/site/restore'), array('class' => 'color')); ?>
</div-->
<!--div class="registration">
<?php echo CHtml::link('Подать заявку на регистрацию', array('/site/registration'), array('class' => 'color')); ?>
</div-->
<?php endif; ?>
