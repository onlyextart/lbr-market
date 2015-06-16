<?php

class m150527_112043_insert_into_user_status extends CDbMigration
{
	public function up()
	{
            $transaction = $this->getDbConnection()->beginTransaction();
            try {
                $this->insert('user_status', array(
                    'id' => 10,
                    'name' => 'Не активировано пользователем'));
                $this->insert('user_status', array(
                    'id' => 0,
                    'name' => 'Не подтвержден модератором'));
                $transaction->commit();
            }
            
            catch(Exception $e) {
                echo "Exception: ".$e->getMessage()."\n";
                $transaction->rollback();
                return false;
            }
	}

	public function down()
	{
		echo "m150527_112043_insert_into_user_status does not support migration down.\n";
		return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}