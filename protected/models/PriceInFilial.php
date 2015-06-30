<?php

/**
 * This is the model class for table "price_in_filial".
 *
 * The followings are the available columns in table 'price_in_filial':
 * @property integer $id
 * @property integer $product_id
 * @property integer $filial_id
 * @property string $price
 * @property string $currency_code
 * @property string $update_time
 *
 * The followings are the available model relations:
 * @property Filial $filial
 * @property Product $product
 */
class PriceInFilial extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'price_in_filial';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_id, filial_id', 'required'),
			array('product_id, filial_id', 'numerical', 'integerOnly'=>true),
			array('price, currency_code, update_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, product_id, filial_id, price, currency_code, update_time', 'safe', 'on'=>'search'),
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
			'filial' => array(self::BELONGS_TO, 'Filial', 'filial_id'),
			'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
			'currency' => array(self::BELONGS_TO, 'Currency', 'currency_code'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'product_id' => 'Product',
			'filial_id' => 'Filial',
			'price' => 'Цена (в базе)',
			'price_in_rub' => 'Цена (руб.)',
			'currency_code' => 'Currency Code',
			'update_time' => 'Update Time',
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
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('filial_id',$this->filial_id);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('currency_code',$this->currency_code,true);
		$criteria->compare('update_time',$this->update_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PriceInFilial the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
