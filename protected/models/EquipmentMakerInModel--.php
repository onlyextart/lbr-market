<?php

/**
 * This is the model class for table "equipment_maker_in_model".
 *
 * The followings are the available columns in table 'equipment_maker_in_model':
 * @property integer $id
 * @property integer $maker_id
 * @property integer $model_id
 *
 * The followings are the available model relations:
 * @property ModelLine $model
 * @property EquipmentMaker $maker
 */
class EquipmentMakerInModel extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'equipment_maker_in_model';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('maker_id, model_id', 'required'),
			array('maker_id, model_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, maker_id, model_id', 'safe', 'on'=>'search'),
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
			'model' => array(self::BELONGS_TO, 'ModelLine', 'model_id'),
			'maker' => array(self::BELONGS_TO, 'EquipmentMaker', 'maker_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'maker_id' => 'Maker',
			'model_id' => 'Model',
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
		$criteria->compare('maker_id',$this->maker_id);
		$criteria->compare('model_id',$this->model_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EquipmentMakerInModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
