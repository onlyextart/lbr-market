<div class="grid-wrapper">
<?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'nopriceListGrid',
        'dataProvider'=>$data,
        'template'=>'{items}{pager}{summary}',
        'columns' => array(
            'id',
            //'name',
            /*'external_id', 
            array (
                'name'=>'name',
                'type'=>'raw',
                'value'=>'CHtml::link(CHtml::encode($data->name), array("product/edit","id"=>$data->id))',
            ),*/
            'price',
            'name'
        ),
    ));
?>
</div>