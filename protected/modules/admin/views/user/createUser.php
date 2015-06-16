<?php
    $name = 'Создание пользователя';
    $submit_text = 'Продолжить';
    $action = '/admin/user/create/';
    
    $this->breadcrumbs = array(
        'Home'=>$this->createUrl('/admin/'),
        'Пользователи'=>Yii::app()->createUrl(''),
        'Все пользователи'=>Yii::app()->createUrl('/admin/user/'),
        $name
    );
    
?>
<div class="total">
    <div class="left">
        <div class="wide">
        <?php $form = $this->beginWidget('CActiveForm', array(
                'id'=>'transport-form',
                'action'=>$action,
                'enableClientValidation' => true,        
                'clientOptions'=>array(
                    'validateOnSubmit'=>true,
                    'validateOnChange' => true,
                    'afterValidate'=>'js:function( form, data, hasError ) 
                    {     
                        if( hasError ){
                            return false;
                        }
                        else{
                            return true;
                        }
                    }'
                ),
            ));
        ?>
        <span class="admin-btn-wrapper">
            <div class="admin-btn-one">
                <span class="admin-btn-next"></span>
                <?php echo CHtml::submitButton($submit_text, array('class'=>'btn-admin')); ?>
            </div>
            <div class="admin-btn-one">
                <span class="admin-btn-close-user"></span>
                <?php echo CHtml::button('Закрыть',array('id'=>'close-user', 'class'=>'btn-admin')); ?>
            </div>
        </span>
        <h1><?php echo $name; ?></h1>
        <div class="form padding-all user-wrapper">    
            
            <div class="row">
            <?php  
                echo $form->error($model_form, 'organization_type'); 
                echo $form->labelEx($model_form, 'organization_type');
                echo $form->dropDownList($model_form, 'organization_type', User::$userType); 
            ?>
            </div>
            
        <?php $this->endWidget(); ?> 
        </div>
    </div>
    
</div>
</div>
<script>
$(function(){
    $('#close-user').click(function(){
        document.location.href = "<?php echo Yii::app()->getBaseUrl(true) ?>/admin/user/";
    });
});
</script>