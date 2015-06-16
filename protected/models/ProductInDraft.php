<?php

/**
 * This is the model class for table "product_in_draft".
 *
 * The followings are the available columns in table 'product_in_draft':
 * @property integer $id
 * @property integer $draft_id
 * @property integer $product_id
 * @property string $level
 * @property string $count
 * @property string $note
 *
 * The followings are the available model relations:
 * @property Product $product
 * @property Draft $draft
 */
class ProductInDraft extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'product_in_draft';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('draft_id, product_id', 'required'),
			array('draft_id, product_id', 'numerical', 'integerOnly'=>true),
			array('level, count, note', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, draft_id, product_id, level, count, note', 'safe', 'on'=>'search'),
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
			'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
			'draft' => array(self::BELONGS_TO, 'Draft', 'draft_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'draft_id' => 'Draft',
			'product_id' => 'Product',
			'level' => 'Level',
			'count' => 'Count',
			'note' => 'Note',
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
		$criteria->compare('draft_id',$this->draft_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('level',$this->level,true);
		$criteria->compare('count',$this->count,true);
		$criteria->compare('note',$this->note,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ProductInDraft the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
