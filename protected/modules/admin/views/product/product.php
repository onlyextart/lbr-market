<?php

$name = 'Запчасти';
$this->breadcrumbs = array(
    'Home'=>$this->createUrl('/admin/'),
    'Каталог'=>Yii::app()->createUrl(''),
    $name
);

$alertMsg = Yii::app()->user->getFlash('message');
$errorMsg = Yii::app()->user->getFlash('error');
?>

<h1><?php echo $name; ?></h1>
<div class="grid-wrapper">
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'productListGrid',
        'filter'=>$model,
        'dataProvider'=>$data,
        'template'=>'{items}{pager}{summary}',
        'summaryText'=>'Элементы {start}—{end} из {count}.',
        'pager' => array(
            'class' => 'LinkPager',
            //'header' => false,
        ),
        'columns' => array('id', 
            array (
                'name'=>'name',
                'type'=>'raw',
                'value'=>'CHtml::link(CHtml::encode($data->name), array("edit","id"=>$data->id))',
                ),
            
            array(
                'name'=>'productGroup_name',
                'value'=>'$data->productGroup->name',
             ),
            
            /*array(
                'name'=>'price_value',
                //'value'=>'(int)$data->price->value." ".$data->currency->iso',
                'value'=>'(int)$data->price->value',
            ),*/
            
            array(
                'name'=>'catalog_number',
                'value'=>'$data->catalog_number',
            ),
            
            array(
                'name'=>'productMaker_name',
                'value'=>'$data->productMaker->name',
            ),
            
            array(
                'name'=>'count',
                'value'=>'$data->count',
            ),
            
            array(
                'name'=>'liquidity',
                'value'=>'$data->liquidity',
            ),
            
            array(
                'name'=>'min_quantity',
                'value'=>'$data->min_quantity',
            ),
            
                        
            array(
                'class'=>'CButtonColumn',
                'template'=>'{update}{delete}',
                'buttons'=>array (
                    'update' => array (
                        'url'=>'Yii::app()->createUrl("admin/product/edit", array("id"=>$data->id))',
                    ),
                    'delete' => array (
                        'url'=>'Yii::app()->createUrl("admin/product/delete", array("id"=>$data->id))',
                        'click'=>'function(){
                            
                        }', 
                    ),
                ),
            ),
        ),
    ));
//Yii::app()->clientScript->registerScript('re-install-date-picker', "
//function reinstallDatePicker(id, data) {
//    jQuery('#date').datepicker(jQuery.extend(jQuery.datepicker.regional['ru'],{
//                                            'showAnim':'fold',
//                                            'dateFormat':'yy-mm-dd',
//                                            'changeMonth':'true',
//                                            'changeYear':'true'}));
//}
//");
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
