<?php

class RegFormInd extends CFormModel
{
    //public $id;
    public $company;
    public $name;
    public $login;
    public $password;
    public $password_confirm;
    public $email;
    //public $address;
    public $country;
    public $region;
    public $locality_type;
    public $locality_name;
    public $phone;
    public $filial;
    public $verifyCode;
    //public $parent;
    //public $type_contact;
     

    public function rules()
    {
        return array(
            array('company, name, login, email, country, region, locality_type, locality_name, phone,filial', 'safe'),
            //array('company, name, login, email, country, region, locality_type, locality_name, phone', 'safe'),
            array('login','checkUniqueLogin'),
            array('login','match','pattern'=>'/^[A-Za-z_0-9\-]{6,}$/','message'=>'Логин должен содержать не менее 6 символов ("_", "-", цифры и латинские буквы)'),
            array('password','match','pattern'=>'/^[A-Za-z_0-9\-]{6,}$/','message'=>'Пароль должен содержать не менее 6 символов ("_", "-", цифры и латинские буквы)'),
            array('name, login, password,  password_confirm, email, verifyCode,filial,country,region,locality_type,locality_name,phone', 'required'),
            //array('name, login, password,  password_confirm, email, verifyCode,country,region,locality_type,locality_name,phone', 'required'),
            array('password_confirm','compare','compareAttribute'=>'password', 'message'=>'Пароли должны совпадать'),
            array('email','checkUniqueEmail'),
            //array('phone','match','pattern' => '/^[\+\(]?[\d]{1,}[\s]?[\(]?[\d]*[\)]?[\d\s\-]{1,}$/','message' => 'Некорректный формат телефона'),
            array('phone','match','pattern' => '/^\+\d{1,3}\(\d{2,4}\)\d{5,7}$/','message' => 'Некорректный формат телефона'),
            array('email', 'email', 'message'=>'Неправильно заполнено поле «Email»'),
            array('verifyCode','captcha','allowEmpty'=>!Yii::app()->user->isGuest || !CCaptcha::checkRequirements()),
            
        );
    }
    
   public function checkUniqueLogin()
   {
       $model_user= User::model()->findByAttributes(array('login'=>$this->login));
       $model_user_auth = AuthUser::model()->findByAttributes(array('login'=>$this->login));
       if(!empty($model_user)||!empty($model_user_auth)){
           $this->addError($this->login, 'Такой логин уже существует');
       }
   }
   
   public function checkUniqueEmail()
   {
       $model_user= User::model()->findByAttributes(array('email'=>$this->email));
       $model_user_auth = AuthUser::model()->findByAttributes(array('email'=>$this->email));
       if(!empty($model_user)||!empty($model_user_auth)){
           $this->addError($this->email, 'Такой email уже зарегистрирован');
       }
   }

    
    public function attributeLabels()
    {
        return array(
            'company' => 'Компания',
            'name' => 'ФИО',
            'login' => 'Логин',
            'password' => 'Пароль',
            'password_confirm' => 'Подтверждение пароля',
            'email' => 'Email',
            'phone' => 'Телефон',
            'country'=>'Страна',
            'region'=>'Область',
            'locality_type'=>'Населенный пункт',
            'locality_name'=>'Населенный пункт',
            'verifyCode' => 'Код проверки',
            'filial'=>'Регион отгрузки',
            //'address' => 'Адрес',   
        );
    }
}
