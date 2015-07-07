<?php

class OrderProductForm extends CFormModel
{
    public $id;
    public $name;
    public $count;
    public $total_price;
    public $price;
    public $catalog_number;
    public $currency;
    public $currency_code;
    public $currency_symbol;
    

    public function rules()
    {
        return array(
            array('id,name,count,price,catalog_number,currency,currency_code, total_price, currency_symbol','safe'),
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
            'total_price' => 'Общая сумма',
            'currency' => 'Курс валюты',
	    'currency_code' => 'Код валюты',
	    'currency_symbol' => 'Символ валюты',
        );
    }
}


