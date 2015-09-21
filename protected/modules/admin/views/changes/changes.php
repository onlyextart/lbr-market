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
        'filter'=>$model,
        'dataProvider'=>$data,
        'template'=>'{items}{pager}{summary}',
        'summaryText'=>'Элементы {start}—{end} из {count}.',
        'pager' => array(
            'class' => 'LinkPager',
        ),
        'afterAjaxUpdate'=>"function(id,data){ $('.description').dotdotdot({
                                                    ellipsis	: '... ',
                                                    wrap	: 'letter',
                                                });
                                              }",
        'columns' => array(
            array(
                'name'=>'id',
                'type'=>'raw',
                'filter'=>false,
                'value'=>'CHtml::link(CHtml::encode($data->id), array("detail","id"=>$data->id))',
                'htmlOptions'=>array(
                    'id'=>'id-change',
                ),
             ),
            array(
                'name'=>'date',
                //'type'=>'raw',
                //'filter'=>false,
                'value'=>'date("Y-m-d H:i", strtotime($data->date))',
            ),
            array(
                'name'=>'description',
                'type'=>'raw',
                'filter'=>false,
                'value'=>function($data){
                    return '<div class="description">'.htmlspecialchars($data->description).'</div>';
                },
            ),
            /*array(
                'name'=>'user_id',
            ),*/
            array(
                'name'=>'user',
                'header'=> 'Пользователь',
                'filter' => $filter,
                'type'=>'raw',
                'value'=>'Changes::getAuthUser($data->user)',
            ),
        ),
    ));

?>
</div>
<script>
$(document).ready(function(){
    $('.description').dotdotdot({
        ellipsis	: '... ',
        wrap		: 'letter',
    });
});
</script>
