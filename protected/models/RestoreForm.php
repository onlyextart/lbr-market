<?php

class RestoreForm extends CFormModel
{
    public $id;
    public $password_new;
    public $password_confirm;
     

    public function rules()
    {
        return array(
            array('password_new, password_confirm', 'required'),
            array('password_confirm','compare','compareAttribute'=>'password_new', 'message'=>'Пароли должны совпадать'),
            
        );
    }
    
      
    public function attributeLabels()
    {
        return array(
            'password_new' => 'Новый пароль',
            'password_confirm' => 'Подтверждение пароля', 
        );
    }
}


