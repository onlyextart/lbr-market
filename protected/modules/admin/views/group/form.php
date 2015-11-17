<?php
return array(
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
                ),
                'use_in_group_filter'=>array(
                    'type'=>'dropdownlist',
                    'options' => array(1 => '1'),
                ),
                'alias'=>array(
                    'type'=>'text',
                ),
            ),
        ),
    ),
);

