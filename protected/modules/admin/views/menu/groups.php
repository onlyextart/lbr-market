<?php
//Groups list/
?>
<h3>Список групп меню</h3>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(
        'name',
        array(
            'class'=>'CButtonColumn',
            'template'=>'{update}{delete}',
            'deleteButtonUrl'=>'"/administrator/menu/deleteGroup/id/".$data->id',
            'updateButtonUrl'=>'"/administrator/menu/updateGroup/id/".$data->id',
            
        ),
    ),
));
?>
<a href="/administrator/menu/createGroup/">Создать группу</a>