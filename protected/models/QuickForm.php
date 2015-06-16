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
    public $adress;
    public $delivery;
    public $region;

    /**
* Declares the validation rules.
*/
public function rules()
{
    return array(            
            array('name, email, phone, region, body,', 'required'),
            array('attachments, name, email, phone, body, organization, region', 'safe'),
            array('email', 'email', 'message'=>'Неправильно заполнено поле «Email»'),
            array('phone', 'numerical', 'integerOnly'=>true, 'min'=>7),
            array('region', 'match', 'pattern'=>'/[а-яА-ЯёЁa-zA-Z]+$/s', 'message'=>'В поле Регион только буквы'),
            array('attachments', 'file', 
                'types'=>'jpg,jpeg,png,doc,docx,pdf,txt,xls,xlsx,',
                'maxSize'=>1024 * 1024 * 4, // 4MB
                'tooLarge'=>'Ваш файл больше 4MB.',
                'allowEmpty'=>1,            
                ),
            array(
                'verifyCode',
                'captcha',
                // авторизованным пользователям код можно не вводить
                'allowEmpty'=>!Yii::app()->user->isGuest || !CCaptcha::checkRequirements(),
            ),
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
        
            'name' => 'ФИО',
            'email' => 'Email',
            'phone' => 'Телефон',
            'adress' => 'Адрес',
            'organization' => 'Организация',
            'delivery' => 'Доставка',  
            'region' => 'Регион',
            'body' => 'Примечание',
            'attachments'=>'Вложения',
            'verifyCode' => 'Код проверки',
            
              
            );
    }
    
}