<?php
return array(
    'id'=>'EqupmentMaker',
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
                'description'=>array(
                    'type'=>'SRichTextarea',
                ),
                'country'=>array(
                    'type'=>'text',
                ),
            ),
        ),
    ),
);

