<?php

/**
 * This is the model class for table "changes".
 *
 * The followings are the available columns in table 'changes':
 * @property integer $id
 * @property string $date
 * @property string $description
 * @property integer $user_id
 * @property string $user
 * @property integer $item_id
 */
class Changes extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
        
        const ITEM_BESTOFFER=1;
        const ITEM_CATEGORY=2;
        const ITEM_CATEGORY_SEO=3;
        const ITEM_CURRENCY=4;
        const ITEM_EQUIPMENT_MAKER=5;
        const ITEM_GROUP=6;
        const ITEM_MODELLINE=7;
        const ITEM_ORDER=8;
        const ITEM_PAGE=9;
        const ITEM_PRODUCT=10;
        const ITEM_PRODUCT_MAKER=11;
        const ITEM_USER=12;
        
        public $user_name;
        
        public function tableName()
	{
		return 'changes';
	}
        
    /**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('date, description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, date, description, user_id, user, user_name, item_id', 'safe', 'on'=>'search'),
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
                    'changes_item'=>array(self::BELONGS_TO, 'ChangesItem', 'item_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'date' => 'Дата и время изменения',
			'description' => 'Описание изменений',
			'user_id' => 'ID пользователя',
                        'user_name'=>'Логин пользователя',
                        'user' => 'ID пользователя',
                        'item_id'=>'Раздел'
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

		$criteria = new CDbCriteria;
                
                if(!empty($this->user)) {
                    if(is_numeric($this->user)) {
                        $user = Yii::app()->db_auth->createCommand()
                            ->select('login')
                            ->from('user')
                            ->where('id = '.trim($this->user))
                            ->queryRow()
                        ;
                        $criteria->compare('user', $this->user);
                        $criteria->addCondition('user like "'.$user['login'].'%"', 'OR');
                    } else {
                        $user = Yii::app()->db_auth->createCommand()
                            ->select('id')
                            ->from('user')
                            ->where('login like "'.$this->user.'%"')
                            ->queryRow()
                        ;
                        $criteria->addCondition('user like "'.$this->user.'%"');
                        $criteria->addCondition('user = '.$user['id'], 'OR');
                    }
                } else $criteria->compare('user', $this->user);
                
                $criteria->compare('id',$this->id);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('description',$this->description,true);
                $criteria->compare('item_id',$this->item_id,false);

		return new CActiveDataProvider($this, array(
                    'criteria'=>$criteria,
                    'sort' => array(
                        'defaultOrder' => 'date DESC',
                    ),
                ));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Changes the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public static function getAuthUser($id){
            if (isset($id)) {
                
                if(is_numeric($id)) {
                    $sql = "SELECT surname, name, secondname FROM user WHERE id=".$id.";";   
                } else {
                    $sql = "SELECT surname, name, secondname FROM user WHERE login = '".trim($id)."';";
                }
                
                $result = Yii::app()->db_auth->createCommand($sql)->queryRow();
                if(!$result) $userName = $id;
                else $userName = $result['surname'].' '.$result['name'].' '.$result['secondname'];
                
                return $userName;
            } else {
                return false;
            }
        }


        public static function saveChange($message,$item=null)
        {//Changes::saveChange($message,$item);
            $change = new Changes();
            //AuthUser
            $change['user_id'] = Yii::app()->user->_id;
            $change['user'] = Yii::app()->user->_id;
            $change['date'] = date('Y-m-d H:i:s');
            $change['description'] = $message;
            $change['item_id']=$item;
            $change->save();
            return;
        }
        
        public static function getEditMessage($model,$post_data,$fields_short_info=array(),$file=array(),$foreign_keys=array())
        {
            $number=0;
            $message = '';
            
            foreach($model as $field=>$value){
                // если передается файл
                if(in_array($field,$file)){
                    if(!is_null(CUploadedFile::getInstance($model, $field))){
                        $number++;
                        $message.=' '.$number.') поле "'.$model->getAttributeLabel($field).'"';
                    }
                    else{
                        continue;
                    }
                }
                // если значение изменилось
                elseif (!empty($post_data[$field]) && $value!=$post_data[$field]){
                    $number++;
                    $message.=' '.$number.') поле "'.$model->getAttributeLabel($field).'"';
                    if(!in_array($field,$fields_short_info)){
                        if(!array_key_exists($field, $foreign_keys)){
                            $message.=' c "'.$value.'" на "'.$post_data[$field].'"'; 
                        }
                        else{
                            $values=Changes::getNamesById($foreign_keys[$field], $value, $post_data[$field]);;
                            $message.=' c "'.$values['old'].'" на "'.$values['new'].'"'; 
                        }
                    }
                }
            }
            if(!empty($message)){
                $message='изменены следующие поля:'.$message;
            }
            return $message;
        }
        
        public static function getNamesById($table,$id_old,$id_new){
            $result=array();
            $query_old="SELECT name FROM ".$table." WHERE id=".$id_old;
            //$result['old'] = Yii::app()->db->createCommand($query_old)->query()->readColumn();
            $result['old'] = Yii::app()->db->createCommand($query_old)->queryScalar();
            
            $query_new="SELECT name FROM ".$table." WHERE id=".$id_new;
            //$result['new'] = Yii::app()->db->createCommand($query_new)->query()->readColumn();
            $result['new'] = Yii::app()->db->createCommand($query_new)->queryScalar();
            
            return $result;
        }
        
}
