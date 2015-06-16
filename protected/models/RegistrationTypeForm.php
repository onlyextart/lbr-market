<?php

class RegistrationTypeForm extends CFormModel
{
    public $organization_type;

    public function rules()
    {
        return array(
            array('organization_type', 'required'),
        );
    }
    
    public function attributeLabels()
    {
        return array(
            'organization_type' => 'Тип регистрации',
            
        );
    }
}
