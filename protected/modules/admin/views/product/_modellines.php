<?php /*if(!empty($modellines)):?>
<div class="label-static">Отображается в следующих модельных рядах:</div>
<?php foreach($modellines as $modelline): ?>
<div class="row">
    <!--a href="/model/show/id/<?php echo $modelline['id']?>"><?php echo $modelline['name']?></a-->
    <a href="<?php echo $modelline['path']?>"><?php echo $modelline['name']?></a>
</div>
<?php endforeach; ?>
<?php endif; */?>

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
                'name'=>'jjj',
                'type'=>'raw',
                'filter'=>false,
                'value'=>'CHtml::link(CHtml::encode($data->modelLine->name), array(ModelLine::getUrl($data->modelLine->id)))',
            ),
        ),
    ));
?>
</div>

