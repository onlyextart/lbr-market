<?php

class OrderProductForm extends CFormModel
{
    public $id;
    public $name;
    public $count;
    public $price;
    public $catalog_number;
    

    public function rules()
    {
        return array(
            array('id,name,count,price,catalog_number','safe'),
            array('count', 'numerical', 'integerOnly' => true, 'message'=>'В поле "Количество" должно быть целое число'),
            array('count', 'required'),
        );
    }
    
    
    public function attributeLabels()
    {
        return array(
            'name'=>'Название',
            'count' => 'Количество',
            'price' => 'Цена',
            'catalog_number' => 'Каталожный номер', 
        );
    }
}


