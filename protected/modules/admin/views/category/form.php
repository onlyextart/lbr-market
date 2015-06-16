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
                'published'=>array(
                    'type'=>'dropdownlist',
                    'items'=>array(
                        1=>'Да',
                        0=>'Нет'
                    ),
                ),
                'path'=>array(
                    'type'=>'text',
                ),
                'alias'=>array(
                    'type'=>'text',
                ),
            ),
        ),
    ),
);

