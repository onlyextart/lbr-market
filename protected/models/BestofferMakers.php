<?php

/**
 * This is the model class for table "bestoffer_makers".
 *
 * The followings are the available columns in table 'bestoffer_makers':
 * @property integer $id
 * @property integer $bestoffer_id
 * @property integer $maker_id
 *
 * The followings are the available model relations:
 * @property ProductMaker $maker
 * @property BestOffer $bestoffer
 */
class BestofferMakers extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'bestoffer_makers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('bestoffer_id, maker_id', 'required'),
			array('bestoffer_id, maker_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, bestoffer_id, maker_id', 'safe', 'on'=>'search'),
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
			'maker' => array(self::BELONGS_TO, 'ProductMaker', 'maker_id'),
			'bestoffer' => array(self::BELONGS_TO, 'BestOffer', 'bestoffer_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'bestoffer_id' => 'Bestoffer',
			'maker_id' => 'Maker',
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
		$criteria->compare('bestoffer_id',$this->bestoffer_id);
		$criteria->compare('maker_id',$this->maker_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BestofferMakers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
