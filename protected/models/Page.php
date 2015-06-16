<?php

/**
 * This is the model class for table "page".
 *
 * The followings are the available columns in table 'page':
 * @property integer $id
 * @property string $title
 * @property string $short_description
 * @property string $full_description
 * @property string $url
 * @property string $meta_title
 * @property string $date_edit
 * @property boolean $published
 * @property string $header
 */
class Page extends CActiveRecord
{
        public static $necessaryPages = array(
            'delivery'=>'Доставка',
            'sale'=>'Распродажа и спецпредложения',
            'service'=>'Гарантия и сервис',
            'payment'=>'Условия и оплата',
        );
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'page';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
                    array('title, header', 'required'),
                    array('url', 'match', 'pattern'=>'/^[a-zA-Z0-9]*$/i', 'message'=>'Поле "{attribute}" должно содержать только цифры и латинские буквы.'),
                    //array('url', 'match', 'pattern'=>'/[^(^sale$)|(^delivery$)|(^payment$)|(^service$)]/i', 'message'=>'Поле "{attribute}" не должно содержать значения delivery, payment, sale, service.'),
                    array('title, short_description, full_description, url, meta_title, date_edit, published, header', 'safe'),
                    // The following rule is used by search().
                    // @todo Please remove those attributes that should not be searched.
                    array('id, title, short_description, full_description, url, meta_title, date_edit, published, header', 'safe', 'on'=>'search'),
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
			'title' => 'Название страницы',
                        'header' => 'Заголовок',
			'short_description' => 'Краткое описание',
			'full_description' => 'Полное описание',
			'url' => 'Url',
			'meta_title' => 'Meta Title',
			'date_edit' => 'Дата редактирования',
			'published' => 'Опубликовать',
			
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('short_description',$this->short_description,true);
		$criteria->compare('full_description',$this->full_description,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('meta_title',$this->meta_title,true);
		$criteria->compare('date_edit',$this->date_edit,true);
		$criteria->compare('published',$this->published);
		$criteria->compare('header',$this->header,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Page the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
