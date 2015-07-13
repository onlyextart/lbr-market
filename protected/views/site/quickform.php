<style> 
    .disabled{
        disabled: disabled;
    }
    .hide{
        display:none;
    }
</style>
<script type="text/javascript">
    $(function(){
        
         $(".delivery_type select").change(function(){
             value=$(".delivery_type select").val();
             if(value==<?= 1?>){            
             
                 $(".adress").addClass('hide');
                 $('.adress *').prop("disabled", true);
             }
             else{
                 $('.adress *').prop("disabled", false);
                 $(".adress").removeClass('hide');
             }
             
              if(value==<?= 3 ?> || value==<?= 4 ?>){
                  $('.region *').prop("disabled", true);
                     $(".region").addClass('hide');
             }
             else{
                 $('.region *').prop("disabled", false);
                 $(".region").removeClass('hide');
             }
             
             if(value==<?= 0 ?>){
            
             
                 $(".adress, .region").addClass('hide');
             }
             
         });
        
    });
</script>
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
        <?php echo $form->labelEx($model,'organization'); ?>
        <?php echo $form->textField($model,'organization'); ?>
    </div> 
    
    <div class="row">
        <?php echo $form->labelEx($model,'body'); ?>
        <?php echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>80, 'style'=>'max-width: 570px;')); ?>
        
    </div>
    <div class="row">
        <div class="row delivery_type">
            <?php echo $form->error($model, "delivery"); ?>
            <?php echo $form->labelEx($model, "delivery"); ?>
            <?php echo $form->dropDownList($model, "delivery", QuickForm::getDeliveryTypes(), array_merge(array('empty'=>'Выберите способ доставки'))); ?>
        </div>
        <div disabled="disabled" class="row region <?php echo ($model->region) ? '': 'hide'?>">
            <?php echo $form->error($model, "region"); ?>
            <?php echo $form->labelEx($model, "region"); ?>
            <?php echo $form->dropDownList($model,"region", QuickForm::getAllFilials(), array_merge(array('empty'=>'Выберите филиал')), array('class'=>'reg-filial')); ?>
        </div>             
        <div disabled="disabled" class="row adress <?php echo ($model->adress) ? '': 'hide'?>">
            <?php echo $form->error($model, "adress"); ?>
            <?php echo $form->labelEx($model, "adress"); ?>
            <?php echo $form->textField($model,"adress"); ?>
        </div>
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
<div class="row">
    <p class="text_quickform_info">
        Для составления заявки заполните следующие поля:
   <ol> <li>Укажите Вашу контактную информацию.</li>
<li>	В примечании укажите производителя, модель техники, каталожные номера запчастей и их количество. Если количество номеров более 5  воспользуйтесь <a href="/images/files/QuickFormsexample.xlsx" target="_blank" download="">ФОРМОЙ ЗАЯВКИ</a>, которую необходимо прикрепить во Вложение.</li> 
<li>	Если каталожные номера запчастей неизвестны, прикрепите их фотографии во Вложение, а в примечании при этом укажите их количество, место установки, модель и производителя техники.</li>
<li>	В строке доставка укажите способ доставки:</li> 
а) самовывоз с филиалом отгрузки;<br /> 
б) транспортной компанией, выбор и оплату услуг транспортной компании производит клиент;<br /> 
в) транспортной компанией, оплата услуг по доставке включается в счет-фактуру.<br /> 
При доставке транспортной компанией необходимо указать адрес.<br /> <br /> </ol>
В течение суток с Вами свяжется персональный менеджер для согласования и оформления заказа.

    </p>
    </div> 
</div><!-- form -->
</div>
        </center>
    </div>



