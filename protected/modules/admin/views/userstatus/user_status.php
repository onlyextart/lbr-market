<?php
$name = 'Статусы пользователей';
$this->breadcrumbs = array(
	'Home'=>$this->createUrl('/admin/'),
	'Пользователи'=>Yii::app()->createUrl(''),
	$name
);
?>
<h1><?php echo $name; ?></h1>
<div class="grid-wrapper">
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'userStatusGrid',
    'dataProvider'=>$data,
    'template'=>'{items}',
    'columns' => array('id', 'name'),
));
?>
</div>