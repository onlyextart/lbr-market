<?php
    $name = 'Создание пользователя';
    $submit_text = 'Создать';
    $action = '/admin/user/create/';
    if (!empty($model->id)) {
        $submit_text = 'Сохранить';
        $action = '/admin/user/edit/id/'.$model->id;
        $name = 'Редактирование пользователя';
    }
    $this->breadcrumbs = array(
        'Home'=>$this->createUrl('/admin/'),
        'Пользователи'=>Yii::app()->createUrl(''),
        'Все пользователи'=>Yii::app()->createUrl('/admin/user/'),
        $name
    );
    $alertMsg = Yii::app()->user->getFlash('message');
    $errorMsg = Yii::app()->user->getFlash('error');
    $orderCount = Order::model()->count(new CDbCriteria(array(
      'condition' => 'user_id = :user_id',
      'params' => array(':user_id'=>$model->id)
    )));
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
            <?php if(!empty($model->id)){ ?>
            <div class="admin-btn-one">
                <span class="admin-btn-del"></span>
                <?php echo CHtml::link('Удалить', '/admin/user/delete/id/'.$model->id, array('class' => 'btn-admin')); ?>
            </div>
            <?php } else { ?>
            <div class="admin-btn-one">
                <span class="admin-btn-back"></span>
                <?php echo CHtml::link('Назад', '/admin/user/create', array('id'=>'close-user','class' => 'btn-admin')); ?>
            </div>
            <?php } ?>
            <div class="admin-btn-one">
                <span class="admin-btn-save"></span>
                <?php echo CHtml::submitButton($submit_text, array('class'=>'btn-admin')); ?>
            </div>
            <div class="admin-btn-one">
                <span class="admin-btn-close-user"></span>
                <?php echo CHtml::button('Закрыть',array('id'=>'close-user', 'class'=>'btn-admin')); ?>
            </div>
        </span>
        <h1><?php echo $name; ?></h1>
        <div class="form padding-all user-wrapper">    
            <?php //echo $form->errorSummary($model); ?>
            <?php if(!empty($formErrors)) {
                echo '<div class="errorSummary"><p>Необходимо исправить следующие ошибки:</p><ul>';
                foreach($formErrors as $error){
                    echo '<li>'.$error[0].'</li>';
                }  
                foreach($model->errors as $error){
                    echo '<li>'.$error[0].'</li>';
                } 
               echo '</ul></div>';
            }?>
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
                echo $form->error($model_form, 'name'); 
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
                echo $form->error($model_form, 'organization_address'); 
                echo $form->labelEx($model_form, 'organization_address');
                echo $form->textArea($model_form, 'organization_address');
                }
            ?>
            </div>
            <div class="row">
            <?php if($model_form->organization_type==User::LEGAL_PERSON){ 
                echo $form->error($model_form, 'inn'); 
                echo $form->labelEx($model_form, 'inn');
                echo $form->textArea($model_form, 'inn');
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
        </div>
        <?php $this->endWidget(); ?> 
        </div>
    </div>
    <div class="right">
        <h1>Дополнительно</h1>
        <?php if($orderCount > 0): ?>
        <a href="#">Заказов: <?php echo $orderCount ?></a>
        <?php else: ?>
        <div class="user-order">Заказов: <?php echo $orderCount ?></div>
        <?php endif; ?>
    </div>
</div>
<script>
$(function(){
    alertify.set({ delay: 6000 });
    <?php if ($alertMsg) :?>
        alertify.success('<?php echo $alertMsg; ?>');
    <?php elseif ($errorMsg): ?>
        alertify.error('<?php echo $errorMsg; ?>');
    <?php endif; ?>
    
    editUser.data = {
        userNotActivated:'<?php echo User::USER_NOT_ACTIVATED?>',
        userNotConfirmed: '<?php echo User::USER_NOT_CONFIRMED?>',
        userActive : '<?php echo User::USER_ACTIVE?>',
        userWarning : '<?php echo User::USER_WARNING?>',
        userTemporaryBlocked : '<?php echo User::USER_TEMPORARY_BLOCKED?>',
        userBlocked : '<?php echo User::USER_BLOCKED?>',
    };
    editUser.initCalendar();
    editUser.editStatus();
    
    $('#close-user').click(function(){
        document.location.href = "<?php echo Yii::app()->getBaseUrl(true) ?>/admin/user/";
    });
    
   
});
</script>