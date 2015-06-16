<?php

class UserForm extends CFormModel
{
    public $id;
    public $company;
    public $name;
    public $login;
    public $password;
    //public $password_confirm;
    public $email;
    public $address;
    public $phone;
    public $parent;
    public $type_contact;
    public $status;
    public $block_date;
    public $block_reason;
    public $date_created;
    public $date_last_login;
    public $organization_type;
    public $filial;


    public function rules()
    {
        return array(
            array('id, company, name, login, password, email, address, phone, parent, type_contact, status, block_reason, block_date, date_created, date_last_login', 'safe', 'on'=>'search'),
            array('id, company, name, login, password, email, address, phone, parent, type_contact, status, block_reason, block_date, date_created, date_last_login, organization_type,filial', 'safe'),
            array('id, parent, type_contact, status', 'numerical', 'integerOnly' => true),
            array('email, login, name,password,filial', 'required'),
            array('email', 'email', 'message'=>'Неправильно заполнено поле «Email»'),
            array('login','checkUniqueLogin'),
            array('email','checkUniqueEmail'),
            array('block_reason', 'customReasonValidation'), 
            array('status','in', 'range'=>array(User::USER_ACTIVE,  User::USER_BLOCKED, User::USER_TEMPORARY_BLOCKED,  User::USER_WARNING), 'message'=>'Выбран некорректный статус'),
            array('block_date', 'customDateValidation'), 
            array('password', 'customPasswordValidation'),  
        );
    }
    
    public function customPasswordValidation($attribute, $params)
    { 
        if (empty($this->id)) {
            $ev = CValidator::createValidator('required', $this, $attribute, $params);
            $ev->validate($this);
        }
    }
    
    public function customReasonValidation($attribute, $params)
    { 
        if ($this->status != User::USER_ACTIVE && $this->status != User::USER_NOT_CONFIRMED) {
            $ev = CValidator::createValidator('required', $this, $attribute, $params);
            $ev->validate($this);
        }
    }
    
    public function customDateValidation($attribute, $params)
    { 
        if ($this->status != User::USER_ACTIVE && $this->status != User::USER_WARNING && $this->status != User::USER_NOT_CONFIRMED) {
            $ev = CValidator::createValidator('required', $this, $attribute, $params);
            $ev->validate($this);
        }
    }
    
    public function checkUniqueLogin($attribute, $params)
    {
       $model_user= User::model()->findByAttributes(array('login'=>$this->login));
       $model_user_auth = AuthUser::model()->findByAttributes(array('login'=>$this->login));
       if(empty($this->id)){
        if(!empty($model_user)||!empty($model_user_auth)){
           $this->addError($this->login, 'Такой логин уже существует');
        }
       }
    }
   
   public function checkUniqueEmail($attribute, $params)
   {
       $model_user= User::model()->findByAttributes(array('email'=>$this->email));
       $model_user_auth = AuthUser::model()->findByAttributes(array('email'=>$this->email));
       if(empty($this->id)){
        if(!empty($model_user)||!empty($model_user_auth)){
           $this->addError($this->email, 'Такой email уже зарегистрирован');
        }
       }
   }
      
    public function attributeLabels()
    {
        return array(
            'company' => 'Компания',
            'name' => 'Имя',
            'login' => 'Логин',
            'password' => 'Пароль',
            //'password_confirm' => 'Пароль',
            'email' => 'Email',
            'phone' => 'Телефон',
            'address' => 'Адрес',
            'status' => 'Статус',
            'block_date'  => 'Блокировать до',
            'block_reason'  => 'Причина блокировки',            
            'date_created'  => 'Дата создания',            
            'date_last_login'  => 'Последний вход',      
            'organization_type'=>'Тип учетной записи',
            'filial'=>'Регион отгрузки',
        );
    }
}
