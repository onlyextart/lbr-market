<?php

/**
 * This is the model class for table "analitics".
 *
 * The followings are the available columns in table 'analitics':
 * @property integer $id
 * @property string $customer_id
 * @property string $subscription_id
 * @property string $time
 * @property string $link_id
 * @property string $url
 * @property string $date_created
 * @property boolean $push_1C
 * @property string $url_mark
 */
class Analitics extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'analitics';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, subscription_id, time, link_id, url, date_created, push_1C, url_mark', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customer_id, subscription_id, time, link_id, url, date_created, push_1C, url_mark', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'customer_id' => 'Customer',
			'subscription_id' => 'Subscription',
			'time' => 'Time',
			'link_id' => 'Link',
			'url' => 'Url',
			'date_created' => 'Date Created',
			'push_1C' => 'Push 1 C',
			'url_mark' => 'Url Mark',
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
		$criteria->compare('customer_id',$this->customer_id,true);
		$criteria->compare('subscription_id',$this->subscription_id,true);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('link_id',$this->link_id,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('date_created',$this->date_created,true);
		$criteria->compare('push_1C',$this->push_1C);
		$criteria->compare('url_mark',$this->url_mark,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Analitics the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
