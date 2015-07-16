<?php
    $err = Yii::app()->user->getFlash('error');
    $mess = Yii::app()->user->getFlash('message');
?>
<div class='cabinet-wrapper'>
    <div class='cabinet-header'>
        <h1>Личный кабинет</h1>
    </div>
    <div class='cabinet-data'>
        <?php if(Yii::app()->user->isShop): ?>
        <div class='cabinet-data-info'>
            <div class='form wide'>
                <?php
                    $action = '/user/cabinet/index/';

                    $form=$this->beginWidget('CActiveForm', array(
                        'id'=>'personalInfo-form',
                        'action'=>$action,
                )); ?>

                <?php echo CHtml::errorSummary($model_info); ?>

<!--                <div class="row">
                    <?php //echo $form->labelEx($model_info,'name'); ?>
                    <?php //echo $form->textField($model_info,'name'); ?>
                </div>-->
                <div class="row">
                    <?php echo $form->labelEx($model_info,'email'); ?>
                    <?php echo $form->textField($model_info,'email'); ?>
                </div>
                <div class="row phone">
                    <?php echo $form->labelEx($model_info,'phone'); ?>
                    <?php echo $form->textField($model_info,'phone'); ?>
                    <div class="note">пример: +7(4722)402104</div>
                </div>
               <div class="row filial">
                    <?php echo $form->labelEx($model_info,'filial'); ?>
                    <?php 
                    if(User::model()->checkCart(Yii::app()->user->_id,$model_info->filial)){
                            echo $form->dropDownList($model_info,'filial',User::model()->getAllFilials());
                            echo "<div class='note'>для отображения цен на условиях самовывоза</div>";
                        }
                        else{
                            echo $form->dropDownList($model_info,'filial',User::model()->getAllFilials(),array('disabled'=>true));
                            echo "<div class='note'>изменение региона возможно только при пустой корзине</div>";
                        }
                    
                    ?>
                </div>

                <?php echo CHtml::submitButton('Сохранить', array('class'=>'btn')); ?>
                <div class="clearfix"></div>


            <?php $this->endWidget(); ?>
            </div>


        </div>
        <div class='cabinet-data-pass'>
            <h3>Изменить пароль</h3>
            <div class='form wide'>
                <?php
                    $action = '/user/cabinet/index/';

                    $form=$this->beginWidget('CActiveForm', array(
                        'id'=>'changePass-form',
                        'action'=>$action,
                        'htmlOptions'=>array('autocomplete'=>'off'),
                )); ?>

                <?php echo CHtml::errorSummary($model_pass); ?>

                <div class="row">
                    <?php echo $form->labelEx($model_pass,'password_old'); ?>
                    <?php echo $form->passwordField($model_pass,'password_old',array('value'=>'')); ?>
                </div>
                <div class="row">
                    <?php echo $form->labelEx($model_pass,'password_new'); ?>
                    <?php echo $form->passwordField($model_pass,'password_new',array('value'=>'')); ?>
                </div>
                <div class="row">
                    <?php echo $form->labelEx($model_pass,'password_confirm'); ?>
                    <?php echo $form->passwordField($model_pass,'password_confirm',array('value'=>'')); ?>
                </div>
                <?php echo CHtml::submitButton('Сохранить', array('class'=>'btn')); ?>
                <div class="clearfix"></div>


            <?php $this->endWidget(); ?>
            </div>
        </div>
        <?php else: ?>
        <div class="no-access"> Вы зашли как администратор, данный раздел доступен только для покупателей.</div>
        <?php endif; ?>
  </div>
</div>
<script>
        <?php if ($mess) :?>
            alertify.success('<?php echo $mess; ?>');
        <?php elseif ($err) :?>
            alertify.error('<?php echo $err; ?>');
        <?php endif; ?>
</script>
