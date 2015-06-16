<?php
$name = 'Актуальные предложения';
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
        <?php echo CHtml::link('Создать', '/admin/actualoffer/create/', array('class' => 'btn-admin')); ?>
    </div>
</span>
<h1><?php echo $name; ?></h1>
<div class="grid-wrapper">
<?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'actualOfferListGrid',
        'filter'=>$model,
        'dataProvider'=>$data,
        'template'=>'{items}{pager}{summary}',
        'summaryText'=>'Элементы {start}—{end} из {count}.',
        'columns' => array(
            array(
                'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1',
            ),
            array( 
                'name'=>'Изображение',
                'type'=>'raw',
                'filter'=>false,
                'value'=>'(!empty($data->img)) ? '
                . 'CHtml::link('
                   . 'CHtml::image($data->img, "Изображение", array("style"=>"max-height: 60px")), '
                   . '$data->img, '
                   . 'array("target"=>"_blank", "class"=>"pretty")) : "Нет изображения"',
            ),
            array( 
                'name'=>'name',
                'type'=>'raw',
                'value'=>'CHtml::link(CHtml::encode($data->name), array("edit","id"=>$data->id))',
            ),
            array( 
                'name'=>'published',
                'value' => '$data->published == 0 ? "Нет" : "Да"',
                'filter'=>array('1'=>'Да', '0'=>'Нет'),
            ),
            array( 
                'name'=>'level',
                'filter'=>false,
            ),
            array(
                'class'=>'CButtonColumn',
                'template'=>'{update}{delete}',
                'buttons'=>array (
                    'update' => array (
                        'url'=>'Yii::app()->createUrl("admin/actualoffer/edit", array("id"=>$data->id))',
                    ),
                    'delete' => array (
                        'url'=>'Yii::app()->createUrl("admin/actualoffer/delete", array("id"=>$data->id))',
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

