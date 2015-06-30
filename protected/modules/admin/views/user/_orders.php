<div class="grid-wrapper">
<?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'makerListGrid',
        'filter'=>$model,
        'dataProvider'=>$data,
        'template'=>'{items}{pager}{summary}',
        'summaryText'=>'Элементы {start}—{end} из {count}.',
        'pager' => array(
            'class' => 'LinkPager',
            //'header' => false,
        ),
        'columns' => array(
            array( 
                'name'=>'id',
                'type'=>'raw',
                'filter'=>false,
                'value'=>'CHtml::link(CHtml::encode($data->id), array("order/edit","id"=>$data->id))',
            ),
            array(
                'name'=>'status_id',
                'filter'=>false,
                'value'=>function($data, $row){
                    return Order::$orderStatus[$data->status_id];
                },
            ),
            array( 
                'name'=>'date_created',
                'type'=>'raw',
                'filter'=>false,
            ),
        ),
    ));
?>
</div>