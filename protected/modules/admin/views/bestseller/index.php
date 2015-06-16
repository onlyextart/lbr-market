<?php
$name = 'Хиты продаж';
$this->breadcrumbs = array(
    'Home'=>$this->createUrl('/admin/'),
    'Сайт'=>Yii::app()->createUrl(''),
    $name
);

$alertMsg = Yii::app()->user->getFlash('message');
$errorMsg = Yii::app()->user->getFlash('error');
?>
<span class="admin-btn-wrapper">
    <div class="admin-btn-one">
        <span class="admin-btn-new-page"></span>
        <?php echo CHtml::link('Создать', '/admin/bestseller/create/', array('class' => 'btn-admin')); ?>
    </div>
</span>
<h1><?php echo $name; ?></h1>
<div class="grid-wrapper">
<?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'bestsellerListGrid',
        'filter'=>$model,
        'dataProvider'=>$data,
        'template'=>'{items}{pager}{summary}',
        'summaryText'=>'Элементы {start}—{end} из {count}.',
        'columns' => array('id', 
            array( 
                'name'=>'name',
                'type'=>'raw',
                'value'=>'CHtml::link(CHtml::encode($data->name), array("edit","id"=>$data->id))',
            ),
            array( 
                'name'=>'logo',
                'type'=>'raw',
                'filter'=>false,
                'value'=>'(!empty($data->img)) ? '
                . 'CHtml::link('
                   . 'CHtml::image($data->img, "Изображение", array("style"=>"max-height: 60px")), '
                   . '$data->img, '
                   . 'array("target"=>"_blank", "class"=>"pretty")) : "Нет изображения"',
            ),
            array(
                'class'=>'CButtonColumn',
                'template'=>'{update}{delete}',
                'buttons'=>array (
                    'update' => array (
                        'url'=>'Yii::app()->createUrl("admin/bestseller/edit", array("id"=>$data->id))',
                    ),
                    'delete' => array (
                        'url'=>'Yii::app()->createUrl("admin/bestseller/delete", array("id"=>$data->id))',
                        'click'=>'function(){
                            
                        }', 
                    ),
                ),
            ),
        ),
    ));
?>
</div>
<script>
$(function(){
    alertify.set({ delay: 6000 });
    <?php if ($alertMsg) :?>
        alertify.success('<?php echo $alertMsg; ?>');
    <?php elseif ($errorMsg): ?>
        alertify.error('<?php echo $errorMsg; ?>');
    <?php endif; ?>
});
</script>
<?php
// Fancybox ext
$this->widget('application.extensions.fancybox.EFancyBox', array(
	'target'=>'a.pretty',
	'config'=>array(),
));