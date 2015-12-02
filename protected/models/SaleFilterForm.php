<?php 
class SaleFilterForm extends CFormModel
{  
    public $maker;
    public $category;

    /**
    * Declares the validation rules.
    * 
    * 
    */
    public function rules()
    {
        return array(            
            array('maker,category', 'safe'),
        );
    }
 
    /**
    * Declares customized attribute labels.
    * If not declared here, an attribute would have a label that is
    * the same as its name with the first letter in upper case.
    */
    public function attributeLabels()
    {
        return array(
            'maker' => 'Производитель техники',
            'category' => 'Тип техники',
            );
    }
    
}