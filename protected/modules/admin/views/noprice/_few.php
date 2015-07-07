<div class="grid-wrapper">
<?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'nopriceListGrid',
        'dataProvider'=>$data,
        'template'=>'{items}{pager}{summary}',
        'columns' => array(
            array(
                'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1',
            ),
            //'id',
            array (
                'name'=>'product_external_id',
                'type'=>'raw',
                'value'=>'Product::model()->findByPk($data->id)->external_id',
            ),
            array (
                'name'=>'product_name',
                'type'=>'raw',
                'value'=>'CHtml::link(CHtml::encode(Product::model()->findByPk($data->id)->name), array("product/edit","id"=>$data->id), array("target"=>"_blank"))',
            ),
            //'price',
            array (
                'name'=>'price',
                'type'=>'raw',
                'value'=>'$data->price." ".Currency::model()->findByPk($data->currency_code)->symbol',
            ),
            'name'
        ),
    ));
?>
</div>