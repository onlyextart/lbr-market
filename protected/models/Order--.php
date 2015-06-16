<?php

/**
 * This is the model class for table "order".
 *
 * The followings are the available columns in table 'order':
 * @property integer $id
 * @property integer $user_id
 * @property integer $delivery_id
 * @property integer $status_id
 * @property string $admin_comment
 * @property string $user_comment
 * @property string $user_name
 * @property string $user_email
 * @property string $user_address
 * @property string $user_phone
 * @property string $date_created
 * @property string $date_updated
 * @property double $total_price
 *
 * The followings are the available model relations:
 * @property OrderStatus $status
 * @property OrderProduct[] $orderProducts
 */
class Order extends CActiveRecord
{
	const CART=0;
	const ORDER_NEW=1;
        const ORDER_DELIVERED=2;
        
        public static $orderStatus=array(
            Order::ORDER_NEW=>'Новый',
            Order::ORDER_DELIVERED=>'Доставлен'
        );
    
        /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, user_id, delivery_id, status_id', 'numerical', 'integerOnly'=>true),
			array('total_price', 'numerical'),
			array('id,user_id,delivery_id,status_id,admin_comment, user_comment, user_name, user_email, user_address, user_phone, date_created, date_updated', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, delivery_id, status_id, admin_comment, user_comment, user_name, user_email, user_address, user_phone, date_created, date_updated, total_price', 'safe','on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'status' => array(self::BELONGS_TO, 'OrderStatus', 'status_id'),
			'orderProducts' => array(self::HAS_MANY, 'OrderProduct', 'order_id'),
                        'user'=>array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}
        
    /**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
                        'status_id'=>'Статус',
			'delivery_id' => 'Способ доставки',
			'status_id' => 'Статус',
			'admin_comment' => 'Комментарий администратора',
			'user_comment' => 'Комментарий пользователя',
			'user_name' => 'Имя',
			'user_email' => 'Email',
			'user_address' => 'Адрес',
			'user_phone' => 'Телефон',
			'date_created' => 'Дата создания',
			'date_updated' => 'Дата обновления',
			'total_price' => 'К оплате (руб.)',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

                $criteria->with=array('user'=>array('select'=>'user.name,user.email,user.phone'));  
                $criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('delivery_id',$this->delivery_id);
		$criteria->compare('status_id',$this->status_id);
		$criteria->compare('admin_comment',$this->admin_comment,true);
		$criteria->compare('user_comment',$this->user_comment,true);
		$criteria->compare('user_name',$this->user_name,true);
                $criteria->compare('user.name',$this->user_name,true,'OR');
		$criteria->compare('user_email',$this->user_email,true);
                $criteria->compare('user.email',$this->user_email,true,'OR');
		$criteria->compare('user_address',$this->user_address,true);
		$criteria->compare('user_phone',$this->user_phone,true);
                $criteria->compare('user.phone',$this->user_phone,true,'OR');
		$criteria->compare('t.date_created',$this->date_created,true);
		$criteria->compare('t.date_updated',$this->date_updated,true);
		$criteria->compare('total_price',$this->total_price);
                      
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        /**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Order the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
