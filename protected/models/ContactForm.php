<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class ContactForm extends CFormModel
{
	public $name;
	public $email;
	public $subject;
        public $company;
	public $body;
	public $phone;
	public $verifyCode;
        public $mailTo;
        public $region;
        public $flagCommonContacts = false;
        public static $mailToArray = array(
            //'mail1@lbr.ru' => 'Тестирование',
            'mail2@lbr.ru' => 'Техника',
            'mail3@lbr.ru' => 'Запчасти',
            'mail4@lbr.ru' => 'Логистика, таможня, сертификация',
            'mail5@lbr.ru' => 'Реклама',
            'mail6@lbr.ru' => 'Бухгалтерия'
        );
        
        public static $realMails = array(
            //'mail1@lbr.ru' => 'teterukova@lbr.ru', // test mail
            'mail2@lbr.ru' => 'pl@lbr.ru',
            'mail3@lbr.ru' => 'parts@lbr.ru',
            'mail4@lbr.ru' => 'log@lbr.ru',
            'mail5@lbr.ru' => 'marketing@lbr.ru',
            'mail6@lbr.ru' => 'uchet_upr@lbr.ru'
        );

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
            return array(
                array('phone, name, email, body, mailTo', 'safe'),
                // name, email, subject and body are required
                array('name, email, body, phone, company, region', 'required'),
                // email has to be a valid email address
                array('email', 'email'),
                array('verifyCode', 'required', 'on'=>'insert'),
                array('verifyCode',  // must be after required rule
                    'captcha',
                    'captchaAction'=>'contacts/captcha',
                    'on'=>'insert',
                    'skipOnError'=>true, // Important: Only validate captcha if 'required' had no error (a.k.a. "if not empty")
                    //'allowEmpty'=>!CCaptcha::checkRequirements(),
                ),
                array('mailTo', 'mailValidation'),
                array('phone','match','pattern' => '/^\+\d{1,3}\(\d{2,5}\)\d{5,7}$/','message' => 'Некорректный формат телефона'),
            );
	}
        
        public function mailValidation($attribute, $params)
        {
            if ($this->flagCommonContacts) {
                $ev = CValidator::createValidator('required', $this, $attribute, $params);
                $ev->validate($this);
            }
        }

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
            return array(
                'name' => 'Имя',
                'company' => 'Организация',
                'email' => 'E-mail',
                'phone' => 'Телефон',
                'region' => 'Регион',
                'mailTo' => 'Служба ЛБР',
                'verifyCode' => 'Код проверки',
                'body' => 'Текст сообщения',
            );
	}
}