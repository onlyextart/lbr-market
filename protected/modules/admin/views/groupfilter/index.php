<?php
$name = 'Фильтр по группам товаров';
$this->breadcrumbs = array(
    'Home'=>$this->createUrl('/admin/'),
    'Сайт'=>Yii::app()->createUrl(''),
    $name
);
?>
<div class="total">
    <div class="left">
        <h1><?php echo $name; ?></h1>
        <?php
            if($groups){
                echo '<div class="wide wide-wrapper">';
                $this->widget('ext.yiiext.behaviors.trees.SJsTreeGroups', array(
                    'id'=>'tree',
                    'data'=>$groups,
                    'options'=>array(
                        'core'=>array('initially_open'=>'treeNode_1'),
                        'plugins'=>array('themes','html_data','ui','crrm', 'search'),
                        'cookies'=>array(
                            'save_selected'=>false,
                        ),
                    ),
                ));
                echo '</div>';
            } else echo '<i>Нет данных</i>';
        ?>
    </div>
    <div class="right">
    </div>
</div>