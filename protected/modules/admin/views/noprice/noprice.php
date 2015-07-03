<?php
    $name = 'Запчасти, на которые нет цен';
    $this->breadcrumbs = array(
        'Home'=>$this->createUrl('/admin/'),
        'Каталог'=>Yii::app()->createUrl(''),
        $name
    );
?>
<h1><?php echo $name; ?></h1>
<div class="grid-wrapper">
<?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'nopriceListGrid',
        'dataProvider'=>$data,
        'template'=>'{items}{pager}{summary}',
        'columns' => array(
            'id',
            'external_id', 
            array (
                'name'=>'name',
                'type'=>'raw',
                'value'=>'CHtml::link(CHtml::encode($data->id), array("edit","id"=>$data->id))',
            ),
            'price',
            'filial'
        ),
    ));
?>
</div>