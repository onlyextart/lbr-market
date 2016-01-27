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
class Product extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public $productGroup_name,
            //$price_value, 
            $productMaker_name,
            //$currency_iso,
            $group,
            $price,
            $filial,
            $modelLineId,
            $makersID
    ;

    CONST IN_STOCK = 'есть в наличии';
    CONST IN_STOCK_SHORT = 'в наличии';
    CONST NO_IN_STOCK = 'под заказ';

    public function tableName() {
        return 'product';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        /* return array(
          array('external_id, name, product_group_id, catalog_number, product_maker_id, image, count, liquidity, min_quantity, additional_info, published, update_time, weight', 'safe'),
          // The following rule is used by search().
          // @todo Please remove those attributes that should not be searched.
          array('id, external_id, name, product_group_id, catalog_number, product_maker_id, image, count, liquidity, min_quantity, additional_info, published, update_time, weight', 'safe', 'on'=>'search'),
          ); */

        return array(
            array('name', 'required'),
            array('min_quantity', 'numerical', 'integerOnly' => true, 'message' => 'Поле должно содержать целое число'),
            array('liquidity', 'match', 'pattern' => '/^[ABCD ]$/', 'message' => 'Значением поля "Ликвидность" может быть только латинская буква A, B, C или D'),
            array('external_id, count, name, weight, update_time, product_group_id, catalog_number, product_maker_id, liquidity, image, additional_info, published, problem, units, multiplicity, material, size, date_sale_off, modelLineId, original', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, update_time, external_id, name, product_group_id, catalog_number, product_maker_id, count, liquidity, image, min_quantity, additional_info, published, productGroup_name, productMaker_name, problem, units, multiplicity, material, size, date_sale_off, original', 'safe', 'on' => 'search'),
            array('name, product_group_id, count, model_line_id', 'safe', 'on'=>'searchEvent'),
            array('name, product_group_id, count, model_line_id', 'safe', 'on'=>'searchGroupfilter'),
            array('name, product_maker_id, count', 'safe', 'on'=>'searchEventMaker'),
            array('name, product_maker_id, product_group_id', 'safe', 'on'=>'searchEventSale'),
            array('image', 'EImageValidator', 'types' => 'gif, jpg, png', 'allowEmpty' => 'true'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
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
    public function attributeLabels() {
        /* return array(
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
          ); */

        return array(
            'id' => 'ID',
            'external_id' => 'ID',
            'name' => 'Название',
            'product_group_id' => 'Группа продукта',
            'group' => 'Группа продукта',
            'catalog_number' => 'Каталожный номер',
            'product_maker_id' => 'Производитель',
            'image' => 'Изображение',
            'count' => 'В наличии, шт.',
            'liquidity' => 'Ликвидность',
            'min_quantity' => 'Минимальное количество',
            'additional_info' => 'Дополнительная информация',
            'productGroup_name' => 'Группа',
            'productMaker_name' => 'Производитель',
            //'price_value'=>'Цена',
            //'currency_iso'=>'Валюта',
            'published' => 'Опубликовать',
            'update_time' => 'Дата обновления',
            'weight' => 'Ориентировочный вес',
            'price' => 'Цена',
            'problem' => 'Проблемный',
            'units' => 'Единицы измерения',
            'multiplicity' => 'Кратность',
            'material' => 'Материал изделия',
            'size' => 'Размер изделия',
            'date_sale_off' => 'Дата снятия с продажи',
            'original' => 'Оригинальная запчасть'
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->with = array('productMaker', 'productGroup');
        $criteria->together = true;
        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.external_id', $this->external_id, true);
        //$criteria->compare('price.value',$this->price_value);
        //$criteria->compare('currency.iso',$this->price_value,true,'OR');
        $criteria->compare('catalog_number', $this->catalog_number, true);
        $criteria->compare('t.image', $this->image, true);
        $criteria->compare('count', $this->count);
        $criteria->compare('liquidity', $this->liquidity, true);
        $criteria->compare('min_quantity', $this->min_quantity);
        $criteria->compare('t.published', $this->published);
        $criteria->compare('additional_info', $this->additional_info, true);
        $criteria->compare('t.update_time', $this->update_time, true);
        $criteria->compare('t.original', $this->original, true);

        if (Yii::app()->search->prepareSqlite()) {
            $condition_name = 'lower(t.name) like lower("%' . $this->name . '%")';
            $criteria->addCondition($condition_name);
            $condition_group = 'lower(productGroup.name) like lower("%' . $this->productGroup_name . '%")';
            $criteria->addCondition($condition_group);
            $condition_maker = 'lower(productMaker.name) like lower("%' . $this->productMaker_name . '%")';
            $criteria->addCondition($condition_maker);
        } else {
            $criteria->compare('t.name', $this->name, true);
            $criteria->compare('productGroup.name', $this->productGroup_name, true);
            $criteria->compare('productMaker.name', $this->productMaker_name, true);
            $criteria->compare('additional_info', $this->additional_info, true);
        }

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('attributes' => array(
                    'productGroup_name' => array(
                        'asc' => $expr = 'productGroup.name',
                        'desc' => $expr . ' DESC',
                    ),
                    /* 'price_value'=>array(
                      'asc' => $expr='price.value',
                      'desc' => $expr.' DESC',
                      ), */
                    'productMaker_name' => array(
                        'asc' => $expr = 'productMaker.name',
                        'desc' => $expr . ' DESC',
                    ),
                    'id', 'external_id', 'name', 'catalog_number', 'count', 'liquidity', 'min_quantity', 'update_time'
                )),
        ));
    }

    public function searchEvent() 
    {
        $groups = array();
        $criteria = new CDbCriteria;
        $criteria->join ='JOIN product_in_model_line ON product_in_model_line.product_id = t.id';
        $criteria->condition = 'product_in_model_line.model_line_id=:model_id and t.published = 1';
        $criteria->params = array(":model_id" => $this->modelLineId);
        // !!!
        $criteria->addCondition('original = 1');
        
        if(!empty($this->count)) { // for model-view filter
            if($this->count == 1) { 
                $criteria->addCondition('count > 0');
            } else $criteria->addCondition('count = 0 or count is null');
        }
        
        if(!empty($this->name)) {
            if(Yii::app()->search->prepareSqlite()){ 
                $match = addcslashes($this->name, '%_');
                $criteria->addCondition('lower(name) like lower(:name)');
                $criteria->params[':name'] = "%$match%";
            }
        }
        
        if(!empty($this->product_maker_id)) {
            $criteria->addCondition('product_maker_id = '.$this->product_maker_id);
        }
        
        if(!empty($this->product_group_id)) {
            $groups[] = $this->product_group_id;
            $model = ProductGroup::model()->findByPk($this->product_group_id);

            if(!$model->isLeaf()) {
                $children = $model->children()->findAll();
                foreach($children as $child) {
                    $groups[] = $child->id;
                    if(!$child->isLeaf()) {
                        $subChildren = $child->children()->findAll();
                        foreach($subChildren as $subChild) {
                             $groups[] = $subChild->id;
                        }
                    }
                }
            }

            $criteria->addInCondition('product_group_id', $groups);
        }
        
        $criteria->addCondition('published = 1');
        
        // brand filter        
        $brandCriteria = new CDbCriteria;
        $brandCriteria->distinct = true;
        $brandCriteria->select = 'product.product_maker_id as id';
        $brandCriteria->join ='JOIN product ON product.id = t.product_id';
        $brandCriteria->condition = 't.model_line_id=:model_line_id';
        $brandCriteria->params = array(':model_line_id'=>$this->modelLineId);
        // !!!
        $brandCriteria->addCondition('original = 1');
        
        if(!empty($groups)) {
            $brandCriteria->addInCondition('product.product_group_id', $groups);
        }
        // end brand filter
        
        $dataProvider = new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'name ASC',
                'multiSort' => true,
                'sortVar'  => 'sort',
                'attributes'=>array(
                    'count'=>array(
                        'asc' => 'count ASC',
                        'desc' => 'count DESC',
                        'default' => 'desc'
                    ),
                    'name'=>array(
                        'asc' => 'name ASC',
                        'desc' => 'name DESC',
                        'default' => 'desc'
                    ),
                ),
            ),
            'pagination' => array(
                'pageSize' => 10,
                'pageVar'  => 'page',
            ),
        ));
        
        
        return array(
            'dataProvider' => $dataProvider,
            'brandCriteria' => $brandCriteria
        );
    }
    
    public function searchGroupfilter() 
    {
        $groups = $models = array();
        $criteria = new CDbCriteria;
        $criteria->distinct = true;
        $criteria->join ='JOIN product_in_model_line ON product_in_model_line.product_id = t.id';
        $criteria->condition = 't.published = 1';
        
        //$criteria->condition = 'product_in_model_line.model_line_id=:model_id and t.published = 1';
        //$criteria->params = array(":model_id" => $this->modelLineId);
        //$criteria->addInCondition('product_group_id', $groups);
        
        if(!empty($this->modelLineId)) {
            $modelline = Modelline::model()->findByPk($this->modelLineId);
            if(!empty($modelline) && !$modelline->isLeaf()) {
                $children = $modelline->children()->findAll();
                foreach($children as $child) {
                    $models[] = $child->id;
                }
                
                if(!empty($models)) $criteria->addInCondition('product_in_model_line.model_line_id', $models);
            }
        }
        
        // !!!
        $criteria->addCondition('original = 1');
        
        if(!empty($this->count)) { // for model-view filter
            if($this->count == 1) { 
                $criteria->addCondition('count > 0');
            } else $criteria->addCondition('count = 0 or count is null');
        }
        
        if(!empty($this->name)) {
            if(Yii::app()->search->prepareSqlite()){ 
                $match = addcslashes($this->name, '%_');
                $criteria->addCondition('lower(name) like lower(:name)');
                $criteria->params[':name'] = "%$match%";
            }
        }
        
        if(!empty($this->product_maker_id)) {
            $criteria->addCondition('product_maker_id = '.$this->product_maker_id);
        }
        
        if(!empty($this->product_group_id)) {
            $groups[] = $this->product_group_id;
            $model = ProductGroupFilter::model()->find('group_id = :id', array(':id' => $this->product_group_id));

            if(!empty($model) && !$model->isLeaf()) {
                $children = $model->children()->findAll();
                foreach($children as $child) {
                    $groups[] = $child->group_id;
                    if(!$child->isLeaf()) {
                        $subChildren = $child->children()->findAll();
                        foreach($subChildren as $subChild) {
                             $groups[] = $subChild->group_id;
                        }
                    }
                }
            }

            if(!empty($groups)) $criteria->addInCondition('product_group_id', $groups);
        }
        
        $criteria->addCondition('published = 1');
        
        // brand filter        
        $brandCriteria = new CDbCriteria;
        $brandCriteria->distinct = true;
        $brandCriteria->select = 'product.product_maker_id as id';
        $brandCriteria->join ='JOIN product ON product.id = t.product_id';
        $brandCriteria->condition = 't.model_line_id=:model_line_id';
        $brandCriteria->params = array(':model_line_id'=>$this->modelLineId);
        // !!!
        $brandCriteria->addCondition('original = 1');
        
        if(!empty($groups)) {
            $brandCriteria->addInCondition('product.product_group_id', $groups);
        }
        // end brand filter
        
        $dataProvider = new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'name ASC',
                'multiSort' => true,
                'sortVar'  => 'sort',
                'attributes'=>array(
                    'count'=>array(
                        'asc' => 'count ASC',
                        'desc' => 'count DESC',
                        'default' => 'desc'
                    ),
                    'name'=>array(
                        'asc' => 'name ASC',
                        'desc' => 'name DESC',
                        'default' => 'desc'
                    ),
                ),
            ),
            'pagination' => array(
                'pageSize' => 10,
                'pageVar'  => 'page',
            ),
        ));
        
        
        return array(
            'dataProvider' => $dataProvider,
            'brandCriteria' => $brandCriteria
        );
    }
    
    public function searchEventMaker(){
        $criteria = new CDbCriteria;
        $criteria->join='JOIN bestoffer_makers ON bestoffer_makers.maker_id=t.product_maker_id';
        $criteria->condition = 't.product_maker_id IN'.$this->makersID.' and t.published = 1 and t.date_sale_off IS NULL';
        if(!empty($this->count)) { 
            if($this->count == 1) { 
                $criteria->addCondition('count > 0');
            } else $criteria->addCondition('count = 0 or count is null');
        }
        
        if(!empty($this->name)) {
            if(Yii::app()->search->prepareSqlite()){ 
                $match = addcslashes($this->name, '%_');
                $criteria->addCondition('lower(name) like lower(:name)');
                $criteria->params[':name'] = "%$match%";
            }
        }
        
        if(!empty($this->product_maker_id)){
            $criteria->addCondition('product_maker_id=:maker_id');
            $criteria->params = array(":maker_id" => $this->product_maker_id);
        }
        
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'name ASC',
                'multiSort' => true,
                'sortVar'  => 'sort',
                'attributes'=>array(
                    'count'=>array(
                        'asc' => 'count ASC',
                        'desc' => 'count DESC',
                        'default' => 'desc'
                    ),
                    'name'=>array(
                        'asc' => 'name ASC',
                        'desc' => 'name DESC',
                        'default' => 'desc'
                    ),
                ),
            ),
            'pagination' => array(
                'pageSize' => 10,
                'pageVar'  => 'page',
            ),
        ));
    }
    
    public function searchEventSale(SaleFilterForm $additional_filter){
        $criteria = new CDbCriteria;
        $criteria->select='t.*,productMaker.name';
        $criteria->together="true";
        $criteria->with=array('productMaker');
        $criteria->condition='t.liquidity = "D" and t.count > 0 and t.published = 1 and t.date_sale_off IS NULL';
 
        if(!empty($this->name)) {
            if(Yii::app()->search->prepareSqlite()){ 
                $match = addcslashes($this->name, '%_');
                $criteria->addCondition('lower(t.name) like lower(:name)');
                $criteria->params[':name'] = "%$match%";
            }
            else{
                $criteria->compare('t.name', $this->name,'AND', true);
            }
        }
        
        if(!empty($additional_filter->maker)||!empty($additional_filter->category)){
            $criteria->distinct="true";
            $criteria->join=' JOIN product_in_model_line as piml ON piml.product_id=t.id';
            $criteria->join.=' JOIN model_line as ml ON piml.model_line_id=ml.id';
            $criteria->join.=' JOIN equipment_maker as maker ON maker.id=ml.maker_id';
            $criteria->join.=' JOIN category as cat ON cat.id=ml.category_id';
            if (!empty($additional_filter->maker)){
                $criteria->addCondition('maker.id=:maker_id');
                $criteria->params[":maker_id"] = $additional_filter->maker;
            }
            if (!empty($additional_filter->category)){
                $criteria->addCondition('cat.id=:category_id');
                $criteria->params[":category_id"] = $additional_filter->category;
            }
        }
        $makerFilter=array();
        $product_makers=Product::model()->findAll($criteria);
        foreach($product_makers as $product_maker){
            if (!empty($product_maker->productMaker->name)){
                $makerFilter[$product_maker->product_maker_id]=$product_maker->productMaker->name;
            }
        }
        asort($makerFilter);
        if(!empty($this->product_maker_id)){
            $criteria->addCondition('t.product_maker_id=:product_maker_id');
            $criteria->params[":product_maker_id"] = $this->product_maker_id;
        }
        
        $dataProvider=new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 't.name ASC',
                'multiSort' => true,
                'sortVar'  => 'sort',
                'attributes'=>array(
                    'name'=>array(
                        'asc' => 't.name ASC',
                        'desc' => 't.name DESC',
                        'default' => 'desc'
                    ),
                ),
            ),
            'pagination' => array(
                'pageSize' => 10,
                'pageVar'  => 'page',
            ),
        ));
        
        return array(
            'dataProvider'=>$dataProvider,
            'makerFilter'=>$makerFilter,
        );
    }
    
    public function getProductMaker() {
        $model_productMaker = ProductMaker::model()->findAll();
        $list = CHtml::listData($model_productMaker, 'id', 'name');
        return $list;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Product the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getImage($imgName, $size = 'large') {
        $image = Yii::app()->params['imageNoPhoto'];
        if (!empty($imgName)) {
            switch ($size) {
                case 'm': // medium
                    $temp = 'http://api.lbr.ru/images/shop/spareparts/medium/' . $imgName;
                    break;
                case 's': // small
                    $temp = 'http://api.lbr.ru/images/shop/spareparts/small/' . $imgName;
                    break;
                default:  // large
                    $temp = 'http://api.lbr.ru/images/shop/spareparts/large/' . $imgName;
            }

            $fileHeaders = @get_headers($temp);
            if (!stripos($fileHeaders[0], "404 Not Found") && !(stripos($fileHeaders[0], "302 Found") > 0 && stripos($fileHeaders[7], "404 Not Found") > 0)) {
                $image = $temp;
            }
        }

        return $image;
    }

    public function getDraftImage($imgName, $size = 'large') {
        $image = Yii::app()->params['imageNoPhoto'];
        if (!empty($imgName)) {
            switch ($size) {
                case 'm': // medium
                    $temp = 'http://api.lbr.ru/images/shop/drafts/medium/' . $imgName;
                    break;
                default:  // large
                    $temp = 'http://api.lbr.ru/images/shop/drafts/large/' . $imgName;
            }

            $fileHeaders = @get_headers($temp);
            if (!stripos($fileHeaders[0], "404 Not Found") && !(stripos($fileHeaders[0], "302 Found") > 0 && stripos($fileHeaders[7], "404 Not Found") > 0)) {
                $image = $temp;
            }
        }

        return $image;
    }

}
