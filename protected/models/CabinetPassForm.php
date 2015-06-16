<?php

class CabinetPassForm extends CFormModel
{
    //public $id;
    public $password_old;
    public $password_new;
    public $password_confirm;
     

    public function rules()
    {
        return array(
            array('password_old, password_new, password_confirm', 'required'),
            array('password_confirm','compare','compareAttribute'=>'password_new', 'message'=>'Пароли должны совпадать'),
            array('password_old','checkPass'),
            
        );
    }
    
   public function checkPass()
   {
       $model_user = User::model()->findByPk(Yii::app()->user->_id);
       if ($model_user->password!==crypt($this->password_old, $model_user->password)){
          $this->addError($this->password_old, 'Неверный пароль');
       }
      
   }
   
          
    public function attributeLabels()
    {
        return array(
            'password_old' => 'Текущий пароль',
            'password_new' => 'Новый пароль',
            'password_confirm' => 'Подтверждение пароля',
            //'address' => 'Адрес',   
        );
    }
}


