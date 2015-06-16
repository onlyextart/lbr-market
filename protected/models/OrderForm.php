<?php

class OrderForm extends CFormModel
{
    public $id;
    public $status_id;
    public $delivery_id;
    public $discount;
    public $user_id;
    public $user_name;
    public $user_email;
    public $user_phone;
    public $user_comment;
    public $admin_comment;

    public function rules()
    {
        return array(
            array('id,status_id,delivery_id,user_id,user_name,user_email,user_phone,user_comment,admin_comment,product_count','safe'),
            array('id', 'numerical', 'integerOnly' => true),
            array('status_id, user_name, user_email', 'required'),
            array('user_email', 'email', 'message'=>'Неправильно заполнено поле «Email»'),
            array ('user_phone','match','pattern'=>'/^[0-9 \+\-\(\)]+$/','message'=>'Поле «Телефон» может содержать только цифры, круглые скобки, символы «+» и «-»')
        );
    }
    
    
    public function attributeLabels()
    {
        return array(
            'id'=>'ID заказа',
            'status_id' => 'Статус',
            'delivery_id' => 'Способ доставки',
            'discount' => 'Скидка',
            'user_name' => 'Имя',
            'user_email' => 'Email',
            'user_phone' => 'Телефон',
            'admin_comment' => 'Комментарий администратора',
            'user_comment' => 'Комментарий пользователя',
            'product_count'  => 'Количество',         
        );
    }
}

