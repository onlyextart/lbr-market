<?php
$name = 'Редактирование заказа';
$submit_text = 'Сохранить';

$action = '/admin/order/edit/id/'.$model->id;
$this->breadcrumbs = array(
        'Home'=>$this->createUrl('/admin/'),
        'Заказы'=>Yii::app()->createUrl('/admin/order/'),
        'Все заказы'=>'',
        $name
 );

$alertMsg = Yii::app()->user->getFlash('message');
$errorMsg = Yii::app()->user->getFlash('error');
?>
<span class="admin-btn-wrapper">
    <?php if(!empty($model->id)): ?>
    <div class="admin-btn-one">
        <span class="admin-btn-del"></span>
        <?php echo CHtml::link('Удалить', '/admin/order/delete/id/'.$model->id, array('class' => 'btn-admin')); ?>
    </div>
    <?php endif; ?>
    <div class="admin-btn-one">
        <span class="admin-btn-save"></span>
        <?php echo CHtml::button($submit_text, array('id' => 'save-btn', 'class'=>'btn-admin')); ?>
    </div>
    <?php if(!empty($model->id)): ?>
    <div class="admin-btn-one">
        <span class="admin-btn-close"></span>
        <?php echo CHtml::button('Закрыть', array('id'=>'close-btn', 'class'=>'btn-admin')); ?>
    </div>
    <?php endif; ?>
</span>
<h1><?php echo $name; ?></h1>
<div class="full">
               
        <?php
            $this->widget('zii.widgets.jui.CJuiTabs', array(
                'tabs'=>array(
                    'Общая информация'=>array('content'=>$this->renderPartial('tabs/general',array('model'=>$model,'form'=>$form,'model_product'=>$model_product,'form_product'=>$form_product,'action'=>$action), true), 'id'=>'general'),
                ),
                // additional javascript options for the tabs plugin
                'options'=>array(
                    'collapsible'=>false,
                ),
            ));
          ?>
  
</div>
<script>
$(function(){
    alertify.set({ delay: 6000 });
    <?php if ($alertMsg) :?>
        alertify.success('<?php echo $alertMsg; ?>');
    <?php elseif ($errorMsg): ?>
        alertify.error('<?php echo $errorMsg; ?>');
    <?php endif; ?>
    
    editOrder.data = {
        deliveryPickup:'<?php echo Delivery::DELIVERY_PICKUP?>',
        deliveryClientTransport: '<?php echo Delivery::DELIVERY_CLIENT_TRANSPORT?>',
        deliveryTransportInvoice : '<?php echo Delivery::DELIVERY_TRANSPORT_INVOICE?>',
    };
    editOrder.editDelivery();
    
    $( "#save-btn" ).click(function() {
        $('form').submit();
    });
    
    $('#close-btn').click(function(){
        document.location.href = "<?php echo Yii::app()->getBaseUrl(true) ?>/admin/order/";
    });
});
</script>



