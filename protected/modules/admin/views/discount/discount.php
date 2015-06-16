<?php

$name = 'Скидки';
$this->breadcrumbs = array(
    'Home'=>$this->createUrl('/admin/'),
    $name
);

$alertMsg = Yii::app()->user->getFlash('message');
$errorMsg = Yii::app()->user->getFlash('error');
?>
<span class="admin-btn-wrapper">
    <div class="admin-btn-one">
        <span class="admin-btn-new-page"></span>
        <?php echo CHtml::link('Создать', '/admin/discount/create/', array('class' => 'btn-admin')); ?>
    </div>
</span>
<h1><?php echo $name; ?></h1>
<div class="grid-wrapper">
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'discountListGrid',
        'filter'=>$model,
        'dataProvider'=>$data,
        'template'=>'{items}{pager}{summary}',
        'summaryText'=>'Элементы {start}—{end} из {count}.',
        'columns' => array('id',
            array (
                'name'=>'name',
                'type'=>'raw',
                'value'=>'CHtml::link(CHtml::encode($data->name), array("edit","id"=>$data->id))',
                ),
            
             array(
                'name'=>'sum',
                'value'=>'(empty($data->sum)) ? "-" : $data->sum',
             ),
            
            array(
                'name'=>'product_name',
                'value'=>'$data->product->name',
            ),
            
               
            array(
               'name'=>'start_date',
               'value'=>'date("Y-m-d H:i:s", strtotime($data->start_date))',
            ),
            
            
            array(
               'name'=>'end_date',
               'value'=>'date("Y-m-d H:i:s", strtotime($data->end_date))', 
            ),
            
            array (
                'name'=>'published',
                'filter'=>array('0'=>'Не опубликовано', '1'=>'Опубликовано'),
                'value'=>'empty($data->published)?("Не опубликовано"):("Опубликовано")',
            ),
            
                       
            array(
                'class'=>'CButtonColumn',
                'template'=>'{update}{delete}',
                'buttons'=>array (
                    'update' => array (
                        'url'=>'Yii::app()->createUrl("admin/discount/edit", array("id"=>$data->id))',
                    ),
                    'delete' => array (
                        'url'=>'Yii::app()->createUrl("admin/discount/delete", array("id"=>$data->id))',
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