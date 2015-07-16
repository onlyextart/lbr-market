<?php

class OrderForm extends CFormModel
{
    public $id;
    public $status_id;
    public $date_created;
    public $delivery_id;
    public $order_filial;
   // public $discount;
    public $user_id;
    public $user_name;
    public $user_email;
    public $user_inn;
    public $user_phone;
    public $user_address;
    public $user_comment;
    public $admin_comment;

    public function rules()
    {
        return array(
            array('id,status_id,delivery_id,date_created,user_id,user_name,user_email,user_inn,user_phone,user_address,user_comment,admin_comment,order_filial,product_count,date_created','safe'),
           // array('id', 'numerical', 'integerOnly' => true),
            array('user_address','checkAddress'),
           // array('status_id, user_name, user_email', 'required'),
           // array('user_email', 'email', 'message'=>'Неправильно заполнено поле «Email»'),
           // array ('user_phone','match','pattern'=>'/^[0-9 \+\-\(\)]+$/','message'=>'Поле «Телефон» может содержать только цифры, круглые скобки, символы «+» и «-»')
        );
    }
    
    public function checkAddress($attribute, $params)
    {
       if($this->delivery_id!=Delivery::DELIVERY_PICKUP){
            $ev = CValidator::createValidator('required', $this, $attribute, $params);
            $ev->validate($this);
       }
    }
    
    public function attributeLabels()
    {
        return array(
            'id'=>'ID заказа',
            'status_id' => 'Статус',
            'date_created'=>'Создан',
            'delivery_id' => 'Способ доставки',
            'order_filial'=>'Регион отгрузки',
           // 'discount' => 'Скидка',
            'user_name' => 'Имя',
            'user_email' => 'Email',
            'user_phone' => 'Телефон',
            'user_address' => 'Адрес доставки',
            'admin_comment' => 'Комментарий администратора',
            'user_comment' => 'Комментарий пользователя',
            'product_count'  => 'Количество',         
        );
    }
    
    
}

