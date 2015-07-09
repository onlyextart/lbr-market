<?php

/**
 * This is the model class for table "product".
 *
 * The followings are the available columns in table 'product':
 * @property integer $id
 * @property string $external_id
 * @property string $name
 * @property string $product_group_id
 * @property string $catalog_number
 * @property string $product_maker_id
 * @property string $image
 * @property string $count
 * @property string $liquidity
 * @property string $min_quantity
 * @property string $additional_info
 * @property boolean $published
 * @property string $update_time
 * @property string $weight
 *
 * The followings are the available model relations:
 * @property Analog[] $analogs
 * @property Analog[] $analogs1
 * @property AttributeValue[] $attributeValues
 * @property OrderProduct[] $orderProducts
 * @property ProductMaker $productMaker
 * @property ProductGroup $productGroup
 * @property ProductInModelLine[] $productInModelLines
 * @property RelatedProduct[] $relatedProducts
 * @property RelatedProduct[] $relatedProducts1
 * @property Wishlist[] $wishlists
 */
class Product extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
        public  $productGroup_name, 
                //$price_value, 
                $productMaker_name,
                //$currency_iso,
                $group;
        
        CONST IN_STOCK = 'есть в наличии';
        CONST IN_STOCK_SHORT = 'в наличии';
        CONST NO_IN_STOCK = 'под заказ';
        
	public function tableName()
	{
		return 'product';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		/*return array(
			array('external_id, name, product_group_id, catalog_number, product_maker_id, image, count, liquidity, min_quantity, additional_info, published, update_time, weight', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, external_id, name, product_group_id, catalog_number, product_maker_id, image, count, liquidity, min_quantity, additional_info, published, update_time, weight', 'safe', 'on'=>'search'),
		);*/
                
                return array(
                        array('name, catalog_number, count', 'required'),
			array('min_quantity', 'numerical', 'integerOnly'=>true, 'message'=>'Поле должно содержать целое число'),
                        array('liquidity','match','pattern'=>'/^[ABCD ]$/','message'=>'Значением поля "Ликвидность" может быть только латинская буква A, B, C или D'),
                        array('external_id, name, weight, update_time, product_group_id, catalog_number, product_maker_id, liquidity, image, additional_info, published', 'safe'),
                        
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, update_time, external_id, name, product_group_id, catalog_number, product_maker_id, count, liquidity, image, min_quantity, additional_info, published, productGroup_name, productMaker_name', 'safe', 'on'=>'search'),
                        array('image','EImageValidator','types' => 'gif, jpg, png','allowEmpty'=>'true'),
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
			'analogs' => array(self::HAS_MANY, 'Analog', 'analog_product_id'),
			'analogs1' => array(self::HAS_MANY, 'Analog', 'product_id'),
			'attributeValues' => array(self::HAS_MANY, 'AttributeValue', 'product_id'),
			'orderProducts' => array(self::HAS_MANY, 'OrderProduct', 'product_id'),
			'productMaker' => array(self::BELONGS_TO, 'ProductMaker', 'product_maker_id'),
			'productGroup' => array(self::BELONGS_TO, 'ProductGroup', 'product_group_id'),
			'productInModelLines' => array(self::HAS_MANY, 'ProductInModelLine', 'product_id'),
			'relatedProducts' => array(self::HAS_MANY, 'RelatedProduct', 'related_product_id'),
			'relatedProducts1' => array(self::HAS_MANY, 'RelatedProduct', 'product_id'),
			'wishlists' => array(self::HAS_MANY, 'Wishlist', 'product_id'),
                        'priceInFilial' => array(self::HAS_MANY, 'PriceInFilial', 'product_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		/*return array(
			'id' => 'ID',
			'external_id' => 'External',
			'name' => 'Name',
			'product_group_id' => 'Product Group',
			'catalog_number' => 'Catalog Number',
			'product_maker_id' => 'Product Maker',
			'image' => 'Image',
			'count' => 'Count',
			'liquidity' => 'Liquidity',
			'min_quantity' => 'Min Quantity',
			'additional_info' => 'Additional Info',
			'published' => 'Published',
			'update_time' => 'Update Time',
			'weight' => 'Weight',
		);*/
            
                return array(
			'id' => 'ID',
			'external_id' => 'External',
			'name' => 'Название',
			'product_group_id' => 'Группа продукта',
                        'group'=>'Группа продукта',
			'catalog_number' => 'Каталожный номер',
			'product_maker_id' => 'Производитель',
			'image' => 'Изображение',
			'count' => 'Количество',
			'liquidity' => 'Ликвидность',
			'min_quantity' => 'Минимальное количество',
			'additional_info' => 'Дополнительная информация',
                        'productGroup_name'=>'Группа', 
                        'productMaker_name'=>'Производитель',
                        //'price_value'=>'Цена',
                        //'currency_iso'=>'Валюта',
			'published' => 'Опубликовать',
                        'update_time' => 'Дата обновления',
                        'weight' => 'Вес',
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
                $criteria->with=array('productMaker','productGroup');
                $criteria->together = true;
		$criteria->compare('t.id',$this->id);
		$criteria->compare('external_id',$this->external_id,true);
		//$criteria->compare('price.value',$this->price_value);
                //$criteria->compare('currency.iso',$this->price_value,true,'OR');
		$criteria->compare('catalog_number',$this->catalog_number,true);
                $criteria->compare('image',$this->image,true);
		$criteria->compare('count',$this->count);
		$criteria->compare('liquidity',$this->liquidity,true);
                $criteria->compare('min_quantity',$this->min_quantity);
		$criteria->compare('published',$this->published);
                $criteria->compare('additional_info',$this->additional_info,true);
                $criteria->compare('update_time',$this->update_time,true);
                
                if(Yii::app()->search->prepareSqlite()){
                    $condition_name='lower(t.name) like lower("%'.$this->name.'%")';    
                    $criteria->addCondition($condition_name);
                    $condition_group='lower(productGroup.name) like lower("%'.$this->productGroup_name.'%")';    
                    $criteria->addCondition($condition_group);
                    $condition_maker='lower(productMaker.name) like lower("%'.$this->productMaker_name.'%")';    
                    $criteria->addCondition($condition_maker);
                }
                else{
                    $criteria->compare('t.name',$this->name,true);
                    $criteria->compare('productGroup.name',$this->productGroup_name,true);
                    $criteria->compare('productMaker.name',$this->productMaker_name,true);
                    $criteria->compare('additional_info',$this->additional_info,true);
                }
                
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'sort'=>array('attributes'=>array(
                            'productGroup_name'=>array(
                                'asc' => $expr='productGroup.name',
                                'desc' => $expr.' DESC',
                            ),
                            /*'price_value'=>array(
                                'asc' => $expr='price.value',
                                'desc' => $expr.' DESC',
                            ),*/
                            'productMaker_name'=>array(
                                'asc' => $expr='productMaker.name',
                                'desc' => $expr.' DESC',
                            ),
                            'id','name', 'catalog_number', 'count', 'liquidity','min_quantity', 'update_time'
                        )),
		));
	}
        
        public function getProductMaker(){
            $model_productMaker=ProductMaker::model()->findAll();
            $list = CHtml::listData($model_productMaker, 'id', 'name');
            return $list;
        }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Product the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
