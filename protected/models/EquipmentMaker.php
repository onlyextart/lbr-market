<?php

/**
 * This is the model class for table "equipment_maker".
 *
 * The followings are the available columns in table 'equipment_maker':
 * @property integer $id
 * @property string $external_id
 * @property string $name
 * @property string $description
 * @property string $logo
 * @property boolean $published
 * @property string $path
 * @property string $meta_title
 * @property string $meta_description
 * @property timestamp $update_time
 *
 * The followings are the available model relations:
 * @property ModelLine[] $modelLines
 */
class EquipmentMaker extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'equipment_maker';
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
                        array('logo', 'file', 'types'=>'jpg, jpeg, JPG, JPEG, gif, png', 'allowEmpty'=>true,'maxSize'=>1024*30, 'tooLarge'=>'Файл весит больше 30Кб. Пожалуйста, загрузите файл меньшего размера.','allowEmpty'=>'true'),
			array('id', 'numerical', 'integerOnly'=>true),
			array('external_id, name, description, logo, published, path, meta_title, meta_description, top_text, bottom_text, update_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, external_id, name, description, logo, published, path, meta_title, meta_description, top_text, bottom_text, update_time', 'safe', 'on'=>'search'),
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
	            'modelLines' => array(self::HAS_MANY, 'ModelLine', 'maker_id'),
                    'categories' => array(self::HAS_MANY, 'Category', 'maker_id'),
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
                        'description' => 'Описание',
                        'logo' => 'Логотип',
                        'published' => 'Опубликовать',
			'path' => 'Path',
			'meta_title' => 'meta-title',
			'meta_description' => 'meta-description',
                        'top_text' => 'Верхний блок',
                        'bottom_text' => 'Нижний блок',
                        'update_time' => 'Время обновления'
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
		$criteria->compare('description',$this->description,true);
		$criteria->compare('logo',$this->logo,true);
		$criteria->compare('published',$this->published);
		$criteria->compare('path',$this->path,true);
		$criteria->compare('meta_title',$this->meta_title,true);
		$criteria->compare('meta_description',$this->meta_description,true);
                $criteria->compare('top_text',$this->top_text,true);
		$criteria->compare('bottom_text',$this->bottom_text,true);
		$criteria->compare('update_time',$this->update_time,true);

                if(Yii::app()->search->prepareSqlite()){
                    $condition_name='lower(name) like lower("%'.$this->name.'%")';    
                    $criteria->addCondition($condition_name);
                }
                else{
                    $criteria->compare('name',$this->name,true);  
                }
                
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EquipmentMaker the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function getAllMakers()
        {
            $makers = array();
            $count = 10;
            $elements = Yii::app()->db->createCommand()
                ->select('id')
                ->from('equipment_maker')
                ->where('published=:flag and logo IS NOT NULL', array(':flag'=>true))
                ->queryColumn()
            ;
            $max = count($elements);
            if ($max > 0) {
                if ($max >= $count) {
                    $randomElements = array_rand($elements, $count);
                } else {
                    $randomElements = array_rand($elements, $max);
                }
                
                $randomCount = count($randomElements);
                $query = "SELECT * from equipment_maker where id in (";
                for ($i = 0; $i < $randomCount; $i++) {
                    if ($i != 0) {
                        $query.=',';
                    }
                    $query.=$elements[$randomElements[$i]];
                }
                $query.=");";
                $result = Yii::app()->db->createCommand($query)->query();
                $makers = $result->readAll();
            }
            return $makers;
        }
}
