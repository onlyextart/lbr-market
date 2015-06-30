<div class="row">
<?php  
    echo $form->error($model_form, 'organization_type'); 
    echo $form->labelEx($model_form, 'organization_type');
    echo CHtml::textField('organization_type', User::$userType[$model_form->organization_type],array('disabled'=>true));
    echo $form->hiddenField($model_form, 'organization_type');
 ?>
</div>
<div class="row">
<?php  
    echo $form->error($model_form, 'login'); 
    echo $form->labelEx($model_form, 'login');
    if (!empty($model_form->id)){
        echo $form->textField($model_form, 'login',array('disabled'=>true));
    }
    else{
        echo $form->textField($model_form, 'login');
    }
?>
</div>
<div class="row">
<?php  
    echo $form->error($model_form, 'email'); 
    echo $form->labelEx($model_form, 'email');
    if (!empty($model_form->id)){
        echo $form->textField($model_form, 'email',array('disabled'=>true));
    }
    else{
        echo $form->textField($model_form, 'email',array('autocomplete'=>'off'));
    }

?>
</div>
<div class="row">
<?php if(empty($model_form->id)) { 
    echo $form->error($model_form, 'password');
    echo $form->labelEx($model_form, 'password');
    echo $form->passwordField($model_form, 'password',array('autocomplete'=>'off'));
}
?>
</div>
<div class="row">
<?php  
    //echo $form->error($model_form, 'name'); 
    echo $form->labelEx($model_form, 'name');
    echo $form->textField($model_form, 'name');
?>
</div>
<div class="row">
<?php  
    echo $form->error($model_form, 'company'); 
    echo $form->labelEx($model_form, 'company');
    echo $form->textField($model_form, 'company');
?>
</div>
<div class="row">
<?php if($model_form->organization_type==User::INDIVIDUAL){ 
    echo $form->error($model_form, 'address'); 
    echo $form->labelEx($model_form, 'address');
    echo $form->textArea($model_form, 'address');
    }
?>
</div>
<div class="row">
<?php if($model_form->organization_type==User::LEGAL_PERSON){ 
    echo $form->error($model_form, 'country_id'); 
    echo $form->labelEx($model_form,'country_id'); 
    echo $form->dropDownList($model_form,'country_id',UserCountry::model()->getAllCountries(),array('id'=>'country_user'));
    }
?>
</div>
<div class="row">
<?php if($model_form->organization_type==User::LEGAL_PERSON){ 
    echo $form->error($model_form, 'organization_address'); 
    echo $form->labelEx($model_form, 'organization_address');
    echo $form->textArea($model_form, 'organization_address');
    }
?>
</div>
<div class="row">
<?php if($model_form->organization_type==User::LEGAL_PERSON){ 
    echo $form->error($model_form, 'inn'); 
    //echo $form->labelEx($model_form, 'inn');?>
    <label class="required" for="UserFormLegalPerson_inn">
        <span id="label_inn">
            <?php echo empty($model_form->country_id)? UserCountry::model()->getCountryLabel(UserCountry::RUSSIA):UserCountry::model()->getCountryLabel($model_form->country_id) ;?>  
        </span>
        <span class="required">*</span>
    </label>
    <?php echo $form->textArea($model_form, 'inn');
    }
?>
</div>
<div class="row">
<?php  
    echo $form->error($model_form, 'phone'); 
    echo $form->labelEx($model_form, 'phone');
    echo $form->textField($model_form, 'phone');
?>
</div>
<div class="row filial">
    <?php 
        echo $form->error($model_form, 'filial');
        echo $form->labelEx($model_form,'filial'); 
        if(User::model()->checkCart($model_form->id,$model_form->filial)){
                echo $form->dropDownList($model_form,'filial',User::model()->getAllFilials(),array('class'=>'reg-filial')); 
            }
            else{
                echo $form->dropDownList($model_form,'filial',User::model()->getAllFilials(),array('class'=>'reg-filial','disabled'=>true));
                echo '<span class="note_change"> Изменение невозможно: корзина не пуста</span>';

            }
        ?>
</div>
<div class="status row">
<?php  
    //echo $form->error($model_form, 'status');
    echo $form->labelEx($model_form, 'status');
    if(!empty($model_form->id))
    {
        if($model_form->status==User::USER_NOT_ACTIVATED){
            echo CHtml::textField('status', User::$userStatus[User::USER_NOT_ACTIVATED], array('disabled'=>true));
        }
        else{
          echo $form->dropDownList($model_form, 'status', User::$userStatus, array('id'=>'User_status'));   
        }
    }
    else echo CHtml::textField('status', User::$userStatus[User::USER_ACTIVE], array('disabled'=>true)); 
?>
</div>
<div class="row <?php echo ($model_form->status == User::USER_TEMPORARY_BLOCKED) ? '': 'hide'?>">
<?php  
    echo $form->error($model_form, 'block_date'); 
    echo $form->labelEx($model_form, 'block_date');
    if(!empty($model_form->block_date)) $model_form->block_date = date("Y-m-d H:i:s", strtotime($model_form->block_date));
    echo $form->textField($model_form, 'block_date', array('id'=>'User_block_date'));
?>
</div>
<div class="row <?php echo ($model_form->status == User::USER_TEMPORARY_BLOCKED||$model_form->status == User::USER_BLOCKED||$model_form->status == User::USER_WARNING) ? '': 'hide'?>">
<?php  
    echo $form->error($model_form, 'block_reason'); 
    echo $form->labelEx($model_form, 'block_reason');
    echo $form->textArea($model_form, 'block_reason', array('id'=>'User_block_reason'));
?>
</div>
<?php if(!empty($model_form->id)):?>
    <div class="row">
    <?php
        echo $form->labelEx($model_form, 'date_created');
        if(!empty($model_form->date_created))$model_form->date_created = date("Y-m-d H:i:s", strtotime($model_form->date_created));
        echo CHtml::textField('date_created', $model_form->date_created, array('disabled'=>true)); //$form->textField($model, 'date_created', array('disabled'=>true)); 
    ?>
    </div>
    <div class="row">
    <?php  
        echo $form->labelEx($model_form, 'date_last_login');
        if(!empty($model_form->date_last_login))$label = date("Y-m-d H:i:s", strtotime($model_form->date_last_login));
        else $label = 'Не входил';
        echo CHtml::textField('date_last_login', $label, array('disabled'=>true)); 
    ?>
    </div>
    <div class="row">
    <?php echo $form->hiddenField($model_form, 'id'); ?>
    </div>
<?php endif; ?>