<?php
    $name = 'Способы доставки';
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
        'id'=>'deliveryListGrid',
        'dataProvider'=>$data,
        'template'=>'{items}',
        'columns' => array('name'),
    ));
?>
</div>