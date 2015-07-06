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
                'name'=>'id',
                'type'=>'raw',
                'value'=>''
            )
        ),
    ));
?>
</div>
