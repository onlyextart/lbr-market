<div class="grid-wrapper">
<?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'noallListGrid',
        'dataProvider'=>$data,
        'template'=>'{items}{pager}{summary}',
        'columns' => array(
            //'id',
            'external_id',
            array (
                'name'=>'name',
                'type'=>'raw',
                'value'=>'CHtml::link(CHtml::encode($data->name), array("product/edit", "id"=>$data->id), array("target"=>"_blank"))',
            ),
            'count',
            'liquidity'
            //'price',
            //'filial'
        ),
    ));
?>
</div>