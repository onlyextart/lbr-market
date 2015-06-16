<?php

/**
 * This is the model class for table "product_group".
 *
 * The followings are the available columns in table 'product_group':
 * @property integer $id
 * @property string $external_id
 * @property string $name
 * @property integer $lft
 * @property integer $rgt
 * @property integer $parent
 * @property integer $level
 *
 * The followings are the available model relations:
 * @property Attribute[] $attributes
 * @property GroupInModelLine[] $groupInModelLines
 * @property Product[] $products
 */
class ProductGroup extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'product_group';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
                        array('name', 'required'),
			array('lft, rgt, parent, level', 'numerical', 'integerOnly'=>true),
			array('external_id, name', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, external_id, name, lft, rgt, parent, level', 'safe', 'on'=>'search'),
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
			'attributes' => array(self::HAS_MANY, 'Attribute', 'product_group_id'),
			'groupInModelLines' => array(self::HAS_MANY, 'GroupInModelLine', 'group_id'),
			'products' => array(self::HAS_MANY, 'Product', 'product_group_id'),
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
			'name' => 'Название',
			'lft' => 'Lft',
			'rgt' => 'Rgt',
			'parent' => 'Parent',
			'level' => 'Level',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('lft',$this->lft);
		$criteria->compare('rgt',$this->rgt);
		$criteria->compare('parent',$this->parent);
		$criteria->compare('level',$this->level);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ProductGroup the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function behaviors()
        {
            return array(
                'nestedSetBehavior'=>array(
                    'class'=>'ext.yiiext.behaviors.trees.NestedSetBehavior',
                    'leftAttribute'=>'lft',
                    'rightAttribute'=>'rgt',
                    'levelAttribute'=>'level',
                    'rootAttribute'=>'parent',
                    'hasManyRoots'=>true,
                ),
            );
        }
}
