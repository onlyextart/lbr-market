<?php
if($groups) {
    Yii::app()->clientScript->registerScriptFile('/js/back/group.js');
    $node = 'treeNode_0';
    if(!empty($rootId)) $node = 'treeNode_'.$rootId;
    
    echo '<div class="wide">';
    $this->widget('ext.yiiext.behaviors.trees.SJsTree', array(
        'id'=>'tree',
        'data'=>$groups,
        'options'=>array(
            'core'=>array('initially_open'=>$node),
            'plugins'=>array('themes', 'html_data', 'ui', 'dnd', 'crrm', 'search'),
            'cookies'=>array(
                'save_selected'=>false,
            ),
            'crrm'=>array(
                'move'=>array('check_move'=>'js: function(m){
                    // Disallow categories without parent.
                    // At least each category must have `root` category as parent.
                    var p = this._get_parent(m.r);
                    if (p == -1) return false;
                    return true;
                }')
             ),
            'dnd'=>array(
                'drag_finish'=>'js:function(data){
                    //alert(data);
                }',
            ),
            'ui'=>array(
                'initially_select'=>array('#treeNode_'.(int)Yii::app()->request->getParam('id'))
            ),
        ),
    ));
    echo '</div>';
} else echo '<i>Нет данных</i>';

