<?php
$name = 'Производители техники в категории';
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
        <?php //echo CHtml::link('Создать производителя', '/admin/categoryseo/create/', array('class' => 'btn-admin')); ?>
    </div>
</span>-->
<h1><?php echo $name; ?></h1>
<div class="grid-wrapper">
<?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'categorySeoListGrid',
        'filter'=>$model,
        'dataProvider'=>$data,
        'template'=>'{items}{pager}{summary}',
        'summaryText'=>'Элементы {start}—{end} из {count}.',
        'pager' => array(
            'class' => 'LinkPager',
            //'header' => false,
        ),
        'columns' => array(//'id', 
            array( 
                'name'=>'categoryName',
                'type'=>'raw',
                'value'=>'CHtml::link(CHtml::encode($data->category->name), array("edit","id"=>$data->id), array("target"=>"_blank"))',
            ),
            array( 
                'name'=>'equipmentMakerName',
                'type'=>'raw',
                'value'=>'CHtml::link(CHtml::encode($data->equipmentMaker->name), array("edit","id"=>$data->id), array("target"=>"_blank"))',
            ),
            
            array(
                'class'=>'CButtonColumn',
                'template'=>'{update}',//'{update}{delete}',
                'buttons'=>array (
                    'update' => array (
                        'url'=>'Yii::app()->createUrl("admin/categoryseo/edit", array("id"=>$data->id))',
                    ),
                    /*'delete' => array (
                        'url'=>'Yii::app()->createUrl("admin/categoryseo/delete", array("id"=>$data->id))',
                        'click'=>'function(){
                            
                        }', 
                    ),*/
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