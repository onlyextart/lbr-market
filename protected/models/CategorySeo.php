<?php

/**
 * This is the model class for table "category_seo".
 *
 * The followings are the available columns in table 'category_seo':
 * @property integer $id
 * @property integer $category_id
 * @property integer $equipment_id
 * @property string $meta_title
 * @property string $meta_description
 * @property string $top_text
 * @property string $bottom_text
 */
class CategorySeo extends CActiveRecord
{
        public $categoryName, $equipmentMakerName;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'category_seo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('category_id, equipment_id', 'numerical', 'integerOnly'=>true),
			array('meta_title, meta_description, top_text, bottom_text, categoryName, h1, equipmentMakerName', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, category_id, equipment_id, meta_title, meta_description, top_text, bottom_text, categoryName, equipmentMakerName, h1', 'safe', 'on'=>'search'),
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
                    'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
                    'equipmentMaker' => array(self::BELONGS_TO, 'EquipmentMaker', 'equipment_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'category_id' => 'Category',
			'equipment_id' => 'Equipment',
                        'meta_title' => 'meta-title',
			'meta_description' => 'meta-description',
                        'top_text' => 'Верхний блок',
                        'bottom_text' => 'Нижний блок',
                        'categoryName' => 'Название категории',
                        'equipmentMakerName' => 'Название производителя техники',
                        'h1' => 'Заголовок h1',
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
                $criteria->with = array( 'category', 'equipmentMaker' );

		$criteria->compare('id',$this->id);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('equipment_id',$this->equipment_id);
		$criteria->compare('meta_title',$this->meta_title,true);
		$criteria->compare('meta_description',$this->meta_description,true);
		$criteria->compare('top_text',$this->top_text,true);
		$criteria->compare('bottom_text',$this->bottom_text,true);
		$criteria->compare('h1',$this->h1,true);
                
                if($this->prepareSqlite()){
                    $criteria->addCondition('lower(category.name) like lower("%'.$this->categoryName.'%")');
                    $criteria->addCondition('lower(equipmentMaker.name) like lower("%'.$this->equipmentMakerName.'%")');
                }

		return new CActiveDataProvider($this, array(
                    'criteria'=>$criteria,
                    'sort'=>array('attributes'=>array(
                            'categoryName'=>array(
                                'asc' => $expr='category.name',
                                'desc' => $expr.' DESC',
                            ),
                            'equipmentMakerName'=>array(
                                'asc' => $expr='equipmentMaker.name',
                                'desc' => $expr.' DESC',
                            ),
                        )),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CategorySeo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function prepareSqlite()
        {
            function lower($str){
                $return = str_replace(array(")", "(", "'", '"' ), "", $str);
                return mb_strtolower(strip_tags($return), "UTF-8");
            }
            Yii::app()->db->getPdoInstance()->sqliteCreateFunction('lower', 'lower', 1);
            //Yii::app()->db_auth->getPdoInstance()->sqliteCreateFunction('lower', 'lower', 1);
            return true;
        }
}
