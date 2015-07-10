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
?>
<span class="admin-btn-wrapper">
    <?php if(!empty($model->id)): ?>
    <div class="admin-btn-one">
        <span class="admin-btn-del"></span>
        <?php echo CHtml::link('Удалить', '/admin/user/delete/id/'.$model->id, array('class' => 'btn-admin')); ?>
    </div>
    <?php else: ?>
    <div class="admin-btn-one">
        <span class="admin-btn-back"></span>
        <?php echo CHtml::link('Назад', '/admin/user/create', array('id'=>'close-user','class' => 'btn-admin')); ?>
    </div>
    <?php endif; ?>
    <div class="admin-btn-one">
        <span class="admin-btn-save"></span>
        <?php echo CHtml::button($submit_text, array('id' => 'save-btn', 'class'=>'btn-admin')); ?>
    </div>
    <div class="admin-btn-one">
        <span class="admin-btn-close-user"></span>
        <?php echo CHtml::button('Закрыть',array('id'=>'close-user', 'class'=>'btn-admin')); ?>
    </div>
</span>
<h1><?php echo $name; ?></h1>
<div class="total">
    <div class="left">
        <div class="form wide padding-all user-wrapper">
        <?php 
            $form = $this->beginWidget('CActiveForm', array(
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
    
            $tabs=array(
                'Общая информация'=>$this->renderPartial('_general', array('form'=>$form, 'model_form'=>$model_form), true),
                'Заказы' => $this->renderPartial('_orders', array('form'=>$form, 'model'=>$model_form, 'data'=>$orders), true),
            );
            
            $errorSummary = $form->errorSummary($model_form)."\n";
            foreach ($tabs as &$tab)
                $tab = $errorSummary.$tab;

            $this->beginWidget('ext.yiiext.sidebartabs.SAdminSidebarTabs', array('tabs'=>$tabs));
            $this->endWidget();
            $this->endWidget(); 
        ?> 
        </div>
    </div>
    <div class="right">
        <!--h1>Дополнительно</h1-->
        <?php /*if(count($orders) > 0): ?>
        <a href="#">Заказов: <?php echo count($orders) ?></a>
        <?php else: ?>
        <div class="user-order">Заказов: <?php echo $orderCount ?></div>
        <?php endif; */?>
        
        <?php echo $this->sidebarContent; ?>
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
    
    $('#close-user').click(function() {
        document.location.href = "<?php echo Yii::app()->getBaseUrl(true) ?>/admin/user/";
    });
    
    $( "#save-btn" ).click(function() {
        $('form').submit();
    });
    
    var labels=<?php echo json_encode(UserCountry::model()->getAllLabels());?>;
     $("#country_user").change(function(){
        country_id=$('#country_user').val();
        $("#label_inn").text(labels[country_id]+" ");
    });
});
</script>