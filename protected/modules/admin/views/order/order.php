<?php

$name = 'Все заказы';
$this->breadcrumbs = array(
    'Home'=>$this->createUrl('/admin/'),
    'Заказы'=>Yii::app()->createUrl(''),
    $name
);

$alertMsg = Yii::app()->user->getFlash('message');
$errorMsg = Yii::app()->user->getFlash('error');
?>

<h1><?php echo $name; ?></h1>
<div class="grid-wrapper">
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'orderListGrid',
        'filter'=>$model,
        'dataProvider'=>$data,
        'template'=>'{items}{pager}{summary}',
        'summaryText'=>'Элементы {start}—{end} из {count}.',
        'columns' => array(
            array (
                'name'=>'id',
                'type'=>'raw',
                'value'=>'CHtml::link(CHtml::encode($data->id), array("edit","id"=>$data->id))',
                ),
            
            array(
                'name'=>'user_name',
                'value'=>'(empty($data->user_id)) ? $data->user_name : $data->user->name',
             ),
            
            array(
                'name'=>'user_email',
                'value'=>'(empty($data->user_id)) ? $data->user_email : $data->user->email',
            ),
            
            array(
                'name'=>'user_phone',
                'value'=>'(empty($data->user_id)) ? $data->user_phone : $data->user->phone',
            ),
            
            array(
                'name'=>'status_id',
                'filter'=>CHtml::listData(OrderStatus::model()->findAll('id>:id_cart',array(':id_cart'=>Order::CART)),'id','name'),
                'value'=>function($data,$row){
                    return Order::$orderStatus[$data->status_id];
                },
            ),
            
            array(
                'name'=>'total_price',
                'value'=>'(int)$data->total_price',
            ),
                        
            array(
               'name'=>'date_created',
               'value'=>'date("Y-m-d H:i:s", strtotime($data->date_created))',
            ),
                        
            array(
                'class'=>'CButtonColumn',
                'template'=>'{update}{delete}',
                'buttons'=>array (
                    'update' => array (
                        'url'=>'Yii::app()->createUrl("admin/order/edit", array("id"=>$data->id))',
                    ),
                    'delete' => array (
                        'url'=>'Yii::app()->createUrl("admin/order/delete", array("id"=>$data->id))',
                        'click'=>'function(){
                            
                        }', 
                    ),
                ),
            ),
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
});
</script>