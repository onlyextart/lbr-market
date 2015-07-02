<?php

/**
 * This is the model class for table "price".
 *
 * The followings are the available columns in table 'price':
 * @property integer $id
 * @property string $external_id
 * @property integer $price_area_id
 * @property double $value
 * @property integer $currency_id
 *
 * The followings are the available model relations:
 * @property Currency $currency
 * @property PriceArea $priceArea
 * @property Product[] $products
 */
class Price extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'price';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('id, price_area_id, currency_id', 'numerical', 'integerOnly'=>true),
                array('value', 'numerical'),
                array('external_id', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, external_id, price_area_id, value, currency_id', 'safe', 'on'=>'search'),
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
			'currency' => array(self::BELONGS_TO, 'Currency', 'currency_id'),
			'priceArea' => array(self::BELONGS_TO, 'PriceArea', 'price_area_id'),
			'products' => array(self::HAS_MANY, 'Product', 'price_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'external_id' => 'External',
			'price_area_id' => 'Price Area',
			'value' => 'Value',
			'currency_id' => 'Currency',
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
		$criteria->compare('external_id',$this->external_id,true);
		$criteria->compare('price_area_id',$this->price_area_id);
		$criteria->compare('value',$this->value);
		$criteria->compare('currency_id',$this->currency_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Price the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function getPrice($productId)
        {
            $priceLabel = '';
            if(Yii::app()->params['showPrices']) {
                // logged user
                if (!Yii::app()->user->isGuest && !empty(Yii::app()->user->isShop)) {
                   $user = User::model()->findByPk(Yii::app()->user->_id);   
                   $filialId = $user->filial;
                   $priceLabel = Price::model()->getPriceInFilial($productId, $filialId);
                } else if(!empty(Yii::app()->request->cookies['lbrfilial']->value)) { //guest or admin
                   $filialId = Yii::app()->request->cookies['lbrfilial']->value;
                   $priceLabel = Price::model()->getPriceInFilial($productId, $filialId);
                }
            } else if(!Yii::app()->user->isGuest && empty(Yii::app()->user->isShop) && Yii::app()->params['showPricesForAdmin']) { // admin
                $filialId = Yii::app()->request->cookies['lbrfilial']->value;
                $priceLabel = Price::model()->getPriceInFilial($productId, $filialId);
            }

            return $priceLabel;
        }

        public function getPriceInFilial($productId, $filialId)
        {
            $priceLabel = '';

            $priceInFilial = PriceInFilial::model()->find('product_id = :id and filial_id = :filial', array('id'=>$productId, 'filial'=>$filialId));
            if(!empty($priceInFilial)) {
                $currency = Currency::model()->findByPk($priceInFilial->currency_code);
                if(!empty($currency)) {
                    $priceLabel = ($priceInFilial->price*$currency->exchange_rate).' руб.';
                }
            }

            return $priceLabel;
        }
}
