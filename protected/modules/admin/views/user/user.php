<?php
$name = 'Все пользователи';
$this->breadcrumbs = array(
    'Home'=>$this->createUrl('/admin/'),
    'Пользователи'=>Yii::app()->createUrl(''),
    $name
);

$alertMsg = Yii::app()->user->getFlash('message');
$errorMsg = Yii::app()->user->getFlash('error');
?>
<span class="admin-btn-wrapper">
    <div class="admin-btn-one">
        <span class="admin-btn-new-user"></span>
        <?php echo CHtml::link('Создать пользователя', '/admin/user/create/', array('class' => 'btn-admin')); ?>
    </div>
</span>
<h1><?php echo $name; ?></h1>
<div class="grid-wrapper">
<?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'userListGrid',
        'filter'=>$model,
        'dataProvider'=>$data,
        'template'=>'{items}{pager}{summary}',
        'summaryText'=>'Элементы {start}—{end} из {count}.',
        'columns' => array(
            //'id', 
            array(
                'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1',
            ),
            array( 
                'name'=>'login',
                'type'=>'raw',
                'value'=>'CHtml::link(CHtml::encode($data->login), array("edit","id"=>$data->id))',
            ), 
            'email',
            'name',
            array(
                'name'=>'status',
                'filter'=>User::$userStatus,
                'value' => function($data, $row) {
                    return User::$userStatus[$data->status];
                },
            ),
            array(
                'name'=>'block_reason',
                'value'=>'(empty($data->block_reason)) ? "-" : $data->block_reason',
            ),
            array(
               'name'=>'date_created',
               'value'=>'date("Y-m-d H:i:s", strtotime($data->date_created))',
            ),
            array(
               'name'=>'date_last_login', 
               'value'=>'(empty($data->date_last_login)) ? "Не входил" : date("Y-m-d H:i:s", strtotime($data->date_last_login))',
            ),
            array(
                'class'=>'CButtonColumn',
                'template'=>'{update}{delete}',
                'buttons'=>array (
                    'update' => array (
                        'url'=>'Yii::app()->createUrl("admin/user/edit", array("id"=>$data->id))',
                    ),
                    'delete' => array (
                        'url'=>'Yii::app()->createUrl("admin/user/delete", array("id"=>$data->id))',
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