<?php

class RestoreEmailForm extends CFormModel
{
    public $email;
     

    public function rules()
    {
        return array(
            array('email', 'required'),
            array('email', 'email', 'message'=>'Неправильно заполнено поле «Email»'),
            array('email','checkEmail')
        );
    }
    
      
    public function attributeLabels()
    {
        return array(
            'email' => 'Email',
        );
    }
    
    public function checkEmail(){
       $criteria=new CDbCriteria(); 
       $criteria->select = 'email';
       $criteria->condition='email=:email';
       $criteria->params = array(':email'=>$this->email);
       $model_user= User::model()->findAll($criteria);
       if(empty($model_user)){
            $this->addError($this->email, 'Такой email не зарегистрирован'); 
       }
    }
}


