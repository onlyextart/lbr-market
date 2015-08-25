<?php

/**
 * This is the model class for table "changes".
 *
 * The followings are the available columns in table 'changes':
 * @property integer $id
 * @property string $date
 * @property string $description
 * @property integer $user_id
 */
class Changes extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
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
			array('id, date, description, user_id', 'safe', 'on'=>'search'),
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
			'date' => 'Время изменения',
			'description' => 'Описание изменений',
			'user_id' => 'User',
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
		$criteria->compare('date',$this->date,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('user_id',$this->user_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
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
        
        public static function saveChange($message)
        {//Changes::saveChange($message);
            $change = new Changes();
            //AuthUser
            $change['user_id'] = Yii::app()->user->_id;
            $change['date'] = date('Y-m-d H:i:s');
            $change['description'] = $message;
            $change->save();
            return;
        }
        
        public static function getEditMessage($model,$post_data,$fields_short_info=array(),$file=array(),$foreign_keys=array())
        {
            $number=0;
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
                elseif ($value!=$post_data[$field]&&!is_null($post_data[$field])){
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
            $result['old'] = Yii::app()->db->createCommand($query_old)->query()->readColumn();
            $query_new="SELECT name FROM ".$table." WHERE id=".$id_new;
            $result['new'] = Yii::app()->db->createCommand($query_new)->query()->readColumn();
            
            return $result;
        }
        
}
