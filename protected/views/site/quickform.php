<?php
    $err = Yii::app()->user->getFlash('error');
?>
<style> 
    .disabled{
        disabled: disabled;
    }
    .hide{
        display:none;
    }
</style>
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
    
    'htmlOptions'=>array('enctype' => 'multipart/form-data','onsubmit'=>"yaCounter30254519.reachGoal('zajavka'); ga('send', 'event', 'action','zajavka'); return true;"),
    'clientOptions'=>array(
                        'validateOnSubmit'=>true,),
    )); ?>

    <?php echo $form->errorSummary($model); ?>    
        <div class="row">
            <?php
            if (Yii::app()->user->isGuest) {
                echo $form->labelEx($model, 'name');
                echo $form->textField($model, 'name');
                ?>
                <?php
            }
            ?>
        </div>
        <div class="row">
            <?php
            if (Yii::app()->user->isGuest) {
                echo $form->labelEx($model, 'email');
                echo $form->textField($model, 'email');
                ?>
                <?php
            }
            ?>
        </div>
        <div class="row">
            <?php
            if (Yii::app()->user->isGuest) {
                echo $form->labelEx($model, 'phone');
                echo $form->textField($model, 'phone'); ?>
                <div class = "phone_note">пример: +7(4722)402104</div>
                
                <?php
            }
            ?>
        </div>
    
    
    <div class="row">
        <?php echo $form->labelEx($model,'organization'); ?>
        <?php echo $form->textField($model,'organization'); ?>
    </div> 
        
    <div class="row">
        <?php echo $form->labelEx($model,'region'); ?>
        <?php echo $form->textField($model,'region'); ?>
    </div> 
    
    <div class="row">
        <?php echo $form->labelEx($model,'body'); ?>
        <?php echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>80, 'style'=>'max-width: 570px;')); ?>
        <div class = "phone_note">Указать производителя, номера и количество</div>
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
<div class="row quick-form-info">
    <p class="text_quickform_info">
        Для составления заявки заполните следующие поля:
    <ol>
        <?php if (Yii::app()->user->isGuest) { ?>
            <li>	Укажите Вашу контактную информацию.</li> 
            <?php
        }
        ?>
        <li>	В примечании укажите производителя, модель техники, каталожные номера запчастей и их количество. Если количество номеров более 5  воспользуйтесь <a href="/images/files/QuickFormsexample.xlsx" target="_blank" download="">ФОРМОЙ ЗАЯВКИ</a>, которую необходимо прикрепить во Вложение.</li> 
        <li>	Если каталожные номера запчастей неизвестны, прикрепите их фотографии во Вложение, а в примечании при этом укажите их количество, место установки, модель и производителя техники.</li>
    </ol>
В течение суток с Вами свяжется персональный менеджер для согласования и оформления заказа.

    </p>
    </div> 
</div><!-- form -->
</div>
        </center>
    </div>
<script type="text/javascript">    
    $(function(){        
         
        <?php if ($err) :?>
            alertify.error('<?php echo $err; ?>');
        <?php endif; ?>
        
    });
</script>

