<?php
if($groups) {
    Yii::app()->clientScript->registerScriptFile('/js/back/product.js');
    $node = 'treeNode_0';
    if(!empty($id)) $node = 'treeNode_'.$id;
    
    echo '<div class="wide">';
    $this->widget('ext.yiiext.behaviors.trees.SJsTree', array(
        'id'=>'tree',
        'data'=>$groups,
        'options'=>array(
            'core'=>array('initially_open'=>$node),
            'plugins'=>array('themes', 'html_data', 'ui','search'),
            'cookies'=>array(
                'save_selected'=>false,
            ),
            'ui'=>array(
                'initially_select'=>array('#treeNode_'.(int)$id)
            ),
        ),
    ));
    echo '</div>';
} else echo '<i>Нет данных</i>';