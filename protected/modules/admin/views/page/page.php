<?php
$name = 'Страницы';
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
        <?php echo CHtml::link('Создать страницу', '/admin/page/create/', array('class' => 'btn-admin')); ?>
    </div>
</span>
<h1><?php echo $name; ?></h1>
<div class="grid-wrapper">
<?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'pageListGrid',
        'filter'=>$model,
        'dataProvider'=>$data,
        'template'=>'{items}{pager}{summary}',
        'summaryText'=>'Элементы {start}—{end} из {count}.',
        'columns' => array('id',
             array( 
                'name'=>'title',
                'type'=>'raw',
                'value'=>'CHtml::link(CHtml::encode($data->title), array("edit","id"=>$data->id))',
            ),
            'header',
            'url',
            array(
               'name'=>'date_edit', 
               'value'=>'(empty($data->date_edit)) ? "-" : date("Y-m-d H:i:s", strtotime($data->date_edit))',
            ),
            array(
                'class'=>'CButtonColumn',
                'template'=>'{update}{delete}',
                'buttons'=>array (
                    'update' => array (
                        'url'=>'Yii::app()->createUrl("admin/page/edit", array("id"=>$data->id))',
                    ),
                    'delete' => array (
                        'url'=>'Yii::app()->createUrl("admin/page/delete", array("id"=>$data->id))',
                        'visible'=>'(array_key_exists($data->url, Page::$necessaryPages))?false:true', 
                    ),
                ),
            ),
        ),
        'pager'=>array(
            'class'=>'LinkPager',
            'header'=>false,
            'prevPageLabel'=>'<',
            'nextPageLabel'=>'>',
            'lastPageLabel'=>'В конец >>',
            'firstPageLabel'=>'<< В начало',
            'maxButtonCount' => '3'
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