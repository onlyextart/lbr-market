<div class="grid-wrapper">
<?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'makerListGrid',
        //'filter'=>$model,
        'dataProvider'=>$data,
        'template'=>'{items}{pager}{summary}',
        'summaryText'=>'Элементы {start}—{end} из {count}.',
        'pager' => array(
            'class' => 'LinkPager',
            //'header' => false,
        ),
        'columns' => array(
            array( 
                'name'=>'',
                'type'=>'raw',
                'filter'=>false,
                'value'=>'$data->filial->name',
            ),
            array( 
                'name'=>'Цена (в базе)',
                'type'=>'raw',
                'filter'=>false,
                'value'=>'$data->price." ".$data->currency->symbol',
            ),
            array( 
                'name'=>'Цена (руб)',
                'type'=>'raw',
                'filter'=>false,
                'value'=>'($data->price*$data->currency->exchange_rate)." руб."',
            ),
        ),
    ));
?>
</div>
