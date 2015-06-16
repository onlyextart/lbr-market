<?php

class AddContactForm extends CFormModel
{
    public $id;
    public $name;
    public $login;
    public $email;
    public $phone;
    

    public function rules()
    {
        return array(
            array('name, login, password, email, phone', 'safe', 'on'=>'search'),
            array('name, email, login', 'required'),
            array('login','checkUniqueLogin'),
            array('email', 'email', 'message'=>'Неправильно заполнено поле «Email»'),
            array('email','checkUniqueEmail'),
        );
    }
    
    public function checkUniqueLogin()
   {
       $criteria=new CDbCriteria(); 
       $criteria->select = 'login';
       $criteria->condition='login=:login';
       $criteria->params = array(':login'=>$this->login);
       $model_user= User::model()->findAll($criteria);
       if(!empty($model_user)){
           $this->addError($this->login, 'Такой логин уже существует');
       }
   }
   
   public function checkUniqueEmail()
   {
       $criteria=new CDbCriteria(); 
       $criteria->select = 'email';
       $criteria->condition='email=:email';
       $criteria->params = array(':email'=>$this->email);
       $model_user= User::model()->findAll($criteria);
       if(!empty($model_user)){
           $this->addError($this->email, 'Такой email уже зарегистрирован');
       }
   }
   
    public function attributeLabels()
    {
        return array(
            'name' => 'ФИО',
            'login' => 'Логин',
            'email' => 'Email',
            'phone' => 'Телефон',
        );
    }
}
