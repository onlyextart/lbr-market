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
                'meta_title'=>array(
                    'type'=>'SRichTextarea',
                ),
                'meta_description'=>array(
                    'type'=>'SRichTextarea',
                ),
                /*'alias'=>array(
                    'type'=>'text',
                ),*/
            ),
        ),
    ),
);

