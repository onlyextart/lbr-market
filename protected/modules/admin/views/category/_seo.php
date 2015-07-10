<?php if(!empty($data)): ?>
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
                'name'=>'Название',
                'type'=>'raw',
                'filter'=>false,
                'value'=>'CHtml::link(CHtml::encode($data->equipmentMaker->name), array("categoryseo/edit","id"=>$data->id), array("target"=>"_blank"))',
            ),
        ),
    ));
?>
</div>
<?php endif; ?>
