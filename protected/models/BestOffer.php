<?php

/**
 * This is the model class for table "best_offer".
 *
 * The followings are the available columns in table 'best_offer':
 * @property integer $id
 * @property string $name
 * @property string $img
 * @property boolean $published
 * @property integer $level
 * @property string $description
 */
class BestOffer extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'best_offer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		/*return array(
			array('level', 'numerical', 'integerOnly'=>true),
			array('name, img, published', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, img, published, level', 'safe', 'on'=>'search'),
		);*/
                
                return array(
			//array('id', 'required'),
                        array('name', 'required'),
			array('id', 'numerical', 'integerOnly'=>true),
			array('name, img, level, published, description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, img, published, level', 'safe', 'on'=>'search'),
                        array('img','EImageValidator','width'=> 770,'height' => 250,
                                                       'types' => 'gif, jpg, png',
                                                       'allowEmpty'=>'true'),
//                                                       ,'maxsize'=>1024*1024*1, 
//                                                       'sizeError' =>'Файл весит больше 1 MB. Пожалуйста, загрузите файл меньшего размера.'),
                      //  array('img','file','maxSize'=>1024*1024*1, 'tooLarge'=>'Файл весит больше 1 MB. Пожалуйста, загрузите файл меньшего размера.'),
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
			'name' => 'Заголовок',
			'img' => 'Изображение',
			'published' => 'Опубликовать',
			'level' => 'Порядок',
                        'description'=>'Описание'
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
                
		if(Yii::app()->search->prepareSqlite()){
                    $condition_name='lower(name) like lower("%'.$this->name.'%")';    
                    $criteria->addCondition($condition_name);
                }
                else{
                    $criteria->compare('name',$this->name,true);
                }
                
		$criteria->compare('img',$this->img,true);
		$criteria->compare('published',$this->published);
		$criteria->compare('level',$this->level);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BestOffer the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
