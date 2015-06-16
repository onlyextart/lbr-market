<?php


class Discount extends CActiveRecord
{
	
    
    
    public $product_name,
           $group_name,
           $group_id;
    
    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'discount';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('name, start_date, end_date', 'required'),
                array('id, product_id', 'numerical', 'integerOnly'=>true),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, name, published, sum, start_date, end_date, product_name, group_name', 'safe', 'on'=>'search'),
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
                        'productGroup'=>array(self::HAS_ONE,'ProductGroup',array('product_group_id'=>'id'),'through'=>'product'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Название',
			'published' => 'Опубликовать',
                        'sum'=>'Описание',
                        'start_date'=>'Дата начала',
                        'end_date'=>'Дата окончания',
                        'product_id'=>'Запчасть',
                        'group_name'=>'Группа',
                        'product_name'=>'Запчасть',
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
                $criteria->with=array('product');
		$criteria->compare('t.id',$this->id);
                $criteria->compare('t.published',$this->published);
                $criteria->compare('start_date',$this->start_date,true);
                $criteria->compare('end_date',$this->end_date,true);
                
                if(Yii::app()->search->prepareSqlite()){
                    $condition_name='lower(t.name) like lower("%'.$this->name.'%")';    
                    $criteria->addCondition($condition_name);
                    $condition_product='lower(product.name) like lower("%'.$this->product_name.'%")';    
                    $criteria->addCondition($condition_product);
                    $condition_sum='lower(sum) like lower("%'.$this->sum.'%")';    
                    $criteria->addCondition($condition_sum);
                }
                else{    
                    $criteria->compare('t.name',$this->name,true);
                    $criteria->compare('product.name',$this->product_name,true);
                    $criteria->compare('sum',$this->sum,true);
                }

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'sort' => array(
                            'attributes' => array(
                                'product_name' => array(
                                    'asc' => 'product.name',
                                    'desc' => 'product.name DESC',
                                ),
                                '*',
                            ),
                        ),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Price the static model class
	 */
        
	 public function getProduct($group_id){
            $criteria=new CDbCriteria();
            $criteria->select = 'id,name';
            $criteria->condition='product_group_id=:group_id';
            $criteria->params = array(':group_id'=>$group_id);
            $model_Product = Product::model()->findAll($criteria);
            $list = CHtml::listData($model_Product, 'id', 'name');
            return $list;
        }
        
        public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

