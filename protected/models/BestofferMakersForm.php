<?php

class BestofferMakersForm extends CFormModel
{
    public $id;
    public $maker_id;
    public $published;
    public $maker_name;
    

    public function rules()
    {
        return array(
            array('id, maker_id, published, maker_name','safe'),
        );
    }
    
    
    public function attributeLabels()
    {
        return array(
            'published'=>'Выбрать',
            'maker_name'=>'Производитель',
        );
    }
}


