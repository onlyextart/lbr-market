<?php 
class QuickForm extends CFormModel
{
//public $id;    
    public $name;
    public $email;
    public $phone;
    public $verifyCode;    
    public $subject;
    public $body;
    public $attachments;
    public $organization;
    public $region;

    public function init()
    {
        if(!Yii::app()->user->isGuest && Yii::app()->user->isShop)
        {
            $user = User::model()->findByPk(Yii::app()->user->_id);
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone = $user->phone;
        }
    }
    
    /**
* Declares the validation rules.
     * 
     * 
*/
        public function rules()
{
    return array(            
            array('name, email, phone, body, region', 'required'),
            //array('adress', 'adressValidation'),
            //array('region', 'regionValidation'),
            array('attachments, name, email, phone, body, organization, region', 'safe'),
            array('email', 'email', 'message'=>'Неправильно заполнено поле «Email»'),
            array('phone','match','pattern' => '/^\+\d{1,3}\(\d{2,4}\)\d{5,7}$/','message' => 'Некорректный формат телефона'),
            array('attachments', 'file', 
                'types'=>'jpg,jpeg,png,doc,docx,pdf,txt,xls,xlsx,',
                'maxSize'=>1024 * 1024 * 4, // 4MB
                'tooLarge'=>'Ваш файл больше 4MB.',
                'allowEmpty'=>1,
                ),
            array(
                'verifyCode',
                'captcha',
                'on'=>'insert',
                // авторизованным пользователям код можно не вводить
                'allowEmpty'=>!Yii::app()->user->isGuest || !CCaptcha::checkRequirements(),
            ),
    );
}
/* public function adressValidation($attribute)
    {
       if ($this->delivery != 1) {
          if (empty($this->adress))
             $this->addError("adress", 'Необходимо указать адрес доставки.');
       }
    }
    
    public function regionValidation($attribute)
    {
       if ($this->delivery != 3 && 4 ) {
          if (empty($this->region))
             $this->addError("region", 'Необходимо указать филиал отгрузки.');
       }
    }*/
/**
* Declares customized attribute labels.
* If not declared here, an attribute would have a label that is
* the same as its name with the first letter in upper case.
*/
public function attributeLabels()
    {
    return array(
        
            'name' => 'ФИО',
            'email' => 'Email',
            'phone' => 'Телефон',
            'organization' => 'Организация',            
            'region' => 'Регион',
            'body' => 'Примечание',
            'attachments'=>'Вложения',
            'verifyCode' => 'Код проверки',
            
              
            );
    }
    
//    static function getDeliveryTypes()
//        {
//        
//            $delivery = Delivery::model()->findAll();
//            $types_d = CHtml::listData($delivery,'id','name');
////            $types[QuickForm::DEFAULT_DELIVERY_TYPE] = 'Самовывоз';
////            $types[QuickForm::DISCOUNT_DELIVERY_TYPE] = 'Транспортной компанией';
//            return $types_d;
//        }
//    
//     public function getAllFilials(){
//            $filials= Filial::model()->findAll('level=2');
//            $list = CHtml::listData($filials, 'id', 'name');
//            return $list;
//        } 
}