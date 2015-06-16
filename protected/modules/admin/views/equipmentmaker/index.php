<?php
$name = 'Производители техники';
$this->breadcrumbs = array(
    'Home'=>$this->createUrl('/admin/'),
    'Каталог'=>Yii::app()->createUrl(''),
    $name
);

$alertMsg = Yii::app()->user->getFlash('message');
$errorMsg = Yii::app()->user->getFlash('error');
?>
<!--<span class="admin-btn-wrapper">
    <div class="admin-btn-one">
        <span class="admin-btn-new-page"></span>
        <?php //echo CHtml::link('Создать производителя', '/admin/equipmentmaker/create/', array('class' => 'btn-admin')); ?>
    </div>
</span>-->
<h1><?php echo $name; ?></h1>
<div class="grid-wrapper">
<?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'makerListGrid',
        'filter'=>$model,
        'dataProvider'=>$data,
        'template'=>'{items}{pager}{summary}',
        'summaryText'=>'Элементы {start}—{end} из {count}.',
        'pager' => array(
            'class' => 'LinkPager',
            //'header' => false,
        ),
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
                'value'=>'(!empty($data->logo)) ? '
                . 'CHtml::link('
                   . 'CHtml::image($data->logo, "Логотип", array("style"=>"max-height: 40px")), '
                   . '$data->logo, '
                   . 'array("target"=>"_blank", "class"=>"pretty")) : "Нет изображения"',
            ),
            array(
                'class'=>'CButtonColumn',
                'template'=>'{update}{delete}',
                'buttons'=>array (
                    'update' => array (
                        'url'=>'Yii::app()->createUrl("admin/equipmentmaker/edit", array("id"=>$data->id))',
                    ),
                    'delete' => array (
                        'url'=>'Yii::app()->createUrl("admin/equipmentmaker/delete", array("id"=>$data->id))',
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