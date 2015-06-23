<?php

class CabinetInfoForm extends CFormModel
{
    //public $id;
 //   public $company;
 //   public $name;
    public $email;
//    public $address;
//    public $country;
//    public $region;
//    public $locality_type;
//    public $locality_name;
    public $phone;
    public $filial;
    //public $parent;
    //public $type_contact;
     

    public function rules()
    {
        return array(
            array('email, phone, filial', 'safe'),
            //array('email, phone', 'safe'),
            array('email, phone, filial', 'required'),
            //array('email, phone', 'required'),
            array('phone','match','pattern' => '/^\+\d{1,3}\(\d{2,4}\)\d{5,7}$/','message' => 'Некорректный формат телефона'),
            array('email','checkUniqueEmail'),
            array('email', 'email', 'message'=>'Неправильно заполнено поле «Email»'),
            
        );
    }
    
  public function checkUniqueEmail()
   {
       $check=true;
       $criteria=new CDbCriteria(); 
       $criteria->select = 'id,email';
       $criteria->condition='email=:email';
       $criteria->params = array(':email'=>$this->email);
       $model_user= User::model()->findAll($criteria);
       if(!empty($model_user)){
           foreach($model_user as $key=>$value){
               if($value->id!==Yii::app()->user->_id){
                   $this->addError($this->email, 'Такой email уже зарегистрирован'); 
                   break;
               }
           }
       }
   }

        
    public function attributeLabels()
    {
        return array(
           //'company' => 'Компания',
           // 'name' => 'ФИО',
            'email' => 'Email',
            'phone' => 'Телефон',
           // 'address'=>'Адрес доставки',
            'filial'=>'Регион отгрузки',
        );
    }
}


