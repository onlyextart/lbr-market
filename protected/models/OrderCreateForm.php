<?php

class OrderCreateForm extends CFormModel
{
    public $user_name;
    public $user_email;
    public $user_phone;
    public $user_address;
    public $user_comment;
    public $delivery_id;

    public function init()
    {
        if(!Yii::app()->user->isGuest && Yii::app()->user->isShop)
        {
            $user = User::model()->findByPk(Yii::app()->user->_id);
            $this->user_name = $user->name;
            $this->user_email = $user->email;
            $this->user_phone = $user->phone;
            $this->user_address = $user->address;
        }
    }

    /**
     * Validation
     * @return array
     */
    public function rules()
    {
        return array(
            array('user_name, user_email, user_address, user_phone', 'required'),
            array('delivery_id', 'required', 'message'=>'Необходимо выбрать способ доставки.'),
            array('user_email', 'email'),
            array('user_email', 'length', 'max'=>'100'),
            array('user_comment', 'length', 'max'=>'500'),
            array('user_address', 'length', 'max'=>'255'),
            array('user_phone', 'length', 'min'=>3, 'max'=>'30'),
            array('user_phone','match','pattern' => '/^\+\d{1,3}\(\d{2,4}\)\d{5,7}$/','message' => 'Некорректный формат телефона (пример корректного: +7(4722)402104)'),
            //array('user_phone','match', 'pattern'=>'/^([\s\d-+]+)$/i', 'message'=>'Поле "{attribute}" должно содержать только следующие символы: 0-9,-,+ и пробел'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'user_name'        => 'Имя',
            'user_email'       => 'Email',
            'user_comment'     => 'Комментарий',
            'user_address'     => 'Адрес доставки',
            'user_phone'       => 'Номер телефона',
            'delivery_id' => 'Способ доставки',
        );
    }
}

