<?php

$output = array(
    //'id'=>'Category',
    'showErrorSummary'=>true,
    'enctype'=>'multipart/form-data',
    'elements'=>array(
        'content'=>array(
            'type'=>'form',
            'title'=>'Общая информация',
            'elements'=>array(
                'name'=>array(
                    'type'=>'text',
                    'disabled'=>'disabled',
                ),
//                'use_in_group_filter'=>array(
//                    'type'=>'dropdownlist',
//                    'items'=>array('0'=>'Нет', '1'=>'Да')
//                ),
                'alias'=>array(
                    'type'=>'text',
                )
            )
        )
    )
);

if($this->model->isLeaf()) {
    $output['elements']['content']['elements']['use_in_group_filter'] = array(
        'type'=>'dropdownlist',
        'items'=>array('0'=>'Нет', '1'=>'Да')
    );
}

return $output;

