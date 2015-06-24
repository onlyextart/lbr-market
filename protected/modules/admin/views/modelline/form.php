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
                'meta_title'=>array(
                    'type'=>'text',
                ),
                'meta_description'=>array(
                    'type'=>'text',
                ),
                'top_text'=>array(
                    'type'=>'SRichTextarea',
                ),
                'bottom_text'=>array(
                    'type'=>'SRichTextarea',
                ),
            ),
        ),
    ),
);

