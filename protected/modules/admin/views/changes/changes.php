<?php

$name = 'Журнал редактирования';
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
        'id'=>'changesListGrid',
        'emptyText'=>'Нет изменений',
        //'filter'=>$model,
        'dataProvider'=>$data,
        'template'=>'{items}{pager}{summary}',
        'summaryText'=>'Элементы {start}—{end} из {count}.',
        'pager' => array(
            'class' => 'LinkPager',
            //'header' => false,
        ),
        'columns' => array(
            /*array(
                'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1',
            ),
            array( 
                'name'=>'name',
                'type'=>'raw',
                'value'=>'CHtml::link(CHtml::encode($data->user_id), array("edit","id"=>$data->user_id))',
            ),*/
        ),
    ));
   
     
    /*$this->widget('zii.widgets.CListView', array(
                'dataProvider'=>$data,
                'itemView'=>'/changes/_item', // представление для одной записи
                'ajaxUpdate'=>false, // отключаем ajax поведение
                'emptyText'=>'Нет изменений',
                'template'=>'{summary} {sorter} {items} {pager}',
                'summaryText'=>'Показано {start}-{end} из {count}',
                'sorterHeader'=>'',
                'itemsTagName'=>'ul',
                'sortableAttributes'=>array(
                    'surname'=>'Фамилия',
                    'name'=>'Имя',
                    'secondname'=>'Отчество',
                    'last_edit'=>'Последнее редактирование'
                ),
                'pager'=>array(
                    'class'=>'LinkPager',
                    'header'=>false,
                    'prevPageLabel'=>'<',
                    'nextPageLabel'=>'>', //'<img src="images/pagination/left.png">',
                    'lastPageLabel'=>'В конец >>',
                    'firstPageLabel'=>'<< В начало',
                    'maxButtonCount' => '5'
                ),
            ));*/
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
