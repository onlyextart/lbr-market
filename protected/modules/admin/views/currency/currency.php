<?php
    $name = 'Валюта';
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
        'id'=>'currencyListGrid',
        'dataProvider'=>$data,
        'template'=>'{items}',
        'columns' => array('name', 'exchange_rate', 'update_time', 'iso', 'symbol'),
    ));
?>
</div>