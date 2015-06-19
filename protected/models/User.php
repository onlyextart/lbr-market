<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $company
 * @property string $name
 * @property string $login
 * @property string $password
 * @property string $email
 * @property string $address
 * @property string $phone
 * @property integer $parent
 * @property integer $type_contact
 * @property integer $status
 * @property string $block_reason
 * @property string $block_date
 * @property string $date_created
 * @property string $date_last_login
 * @property string $inn
 * @property string $organization_address
 * @property integer $organization_type
 * @property integer $filial
 * @property integer $country_id
 *
 * The followings are the available model relations:
 * @property Order[] $orders
 * @property UserCountry $country
 * @property Wishlist[] $wishlists
 */
class User extends CActiveRecord
{
        const USER_NOT_CONFIRMED = 0;
        const USER_ACTIVE = 1;
        const USER_WARNING = 2;
        const USER_TEMPORARY_BLOCKED = 3;
        const USER_BLOCKED = 4;
        const PARENT_BLOCKED = 5;
        const USER_NOT_ACTIVATED = 10;
        
        const INDIVIDUAL=0;
        const LEGAL_PERSON=1;
        
        public static $userStatus = array(
            User::USER_NOT_ACTIVATED=>'Не активировано пользователем',
            User::USER_NOT_CONFIRMED => 'Не подтвержден модератором',
            User::USER_ACTIVE => 'Активен',
            User::USER_WARNING => 'Предупрежден',
            User::USER_TEMPORARY_BLOCKED => 'Временно заблокирован',
            User::USER_BLOCKED => 'Заблокирован',
        );   
        
        public static $userType = array(
            User::LEGAL_PERSON => 'Юридическое лицо (в т.ч. ИП)',
            User::INDIVIDUAL => 'Физическое лицо',
        ); 
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
                        array('login, email', 'required'),
                        array('login','match','pattern'=>'/^[A-Za-z_0-9\-]+$/','message'=>'Логин может содержать символы "_", "-", цифры и латинские буквы'),
			//array('id, parent, type_contact, status', 'numerical', 'integerOnly'=>true),
                    
			array('parent, type_contact, status, organization_type, filial, country_id', 'numerical', 'integerOnly'=>true),
			array('company, name, login, password, email, address, phone, block_reason, block_date, date_created, date_last_login, inn, organization_address', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, company, name, login, password, email, address, phone, parent, type_contact, status, block_reason, block_date, date_created, date_last_login, inn, organization_address, organization_type, filial, country_id', 'safe', 'on'=>'search'),
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
			'orders' => array(self::HAS_MANY, 'Order', 'user_id'),
			'country' => array(self::BELONGS_TO, 'UserCountry', 'country_id'),
			'wishlists' => array(self::HAS_MANY, 'Wishlist', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
                        'id' => 'ID',
                        'company' => 'Название компании',
                        'name' => 'Имя',
                        'login' => 'Логин',
                        'password' => 'Пароль',
                        'email' => 'Email',
                        'address' => 'Адрес',
                        'phone' => 'Телефон',
                        'parent' => 'Parent',
                        'type_contact' => 'Type Contact',
                        'status' => 'Статус',
                        'block_reason' => 'Причина блокировки, предупреждения',
                        'block_date' => 'Блокировать до даты',
                        'date_created' => 'Дата регистрации',
                        'date_last_login' => 'Последний вход',
                        'inn' => 'Идентификационный номер',
                        'organization_address' => 'Адрес организации',
                        'organization_type'=>'Тип учетной записи',
			'country_id' => 'Страна',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('company',$this->company,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('login',$this->login,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('parent',$this->parent);
		$criteria->compare('type_contact',$this->type_contact);
		$criteria->compare('status',$this->status);
		$criteria->compare('block_reason',$this->block_reason,true);
		$criteria->compare('block_date',$this->block_date,true);
		$criteria->compare('date_created',$this->date_created,true);
		$criteria->compare('date_last_login',$this->date_last_login,true);
		$criteria->compare('inn',$this->inn,true);
		$criteria->compare('organization_address',$this->organization_address,true);
		$criteria->compare('organization_type',$this->organization_type);
		$criteria->compare('filial',$this->filial);
		$criteria->compare('country_id',$this->country_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function checkCart($id,$filial)
        {
            $order = Yii::app()->db->createCommand()
                             ->select('o.id order')
                             ->from('order o')
                             ->where('o.status_id=:cart_status and o.user_id=:user_id', array(':cart_status'=>Order::CART, ':user_id'=>$id))
                             ->queryRow();
       
            $current_user=User::model()->findByPk($id);
      
            if(!empty($order)) {
                return false;
            } 
            else{
                return true;
            }
           
        }
        
        public function getAllFilials(){
            $filials= Filial::model()->findAll('level=2');
            $list = CHtml::listData($filials, 'id', 'name');
            return $list;
        } 
        
        //  Метод возвращет $cost значное число для хэширования пароля, где: 
        //  $cost - количество возвращаемых знаков
        public function blowfishSalt($cost = 13)
        {
            if (!is_numeric($cost) || $cost < 4 || $cost > 31) {
                throw new Exception("cost parameter must be between 4 and 31");
            }
            $rand = array();
            for ($i = 0; $i < 8; $i += 1) {
                $rand[] = pack('S', mt_rand(0, 0xffff));
            }
            $rand[] = substr(microtime(), 2, 6);
            $rand = sha1(implode('', $rand), true);
            $salt = '$2a$' . sprintf('%02d', $cost) . '$';
            $salt .= strtr(substr(base64_encode($rand), 0, 22), array('+' => '.'));
            return $salt;
        }
}
