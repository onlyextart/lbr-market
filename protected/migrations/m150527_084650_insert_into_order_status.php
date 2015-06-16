<?php

class m150527_084650_insert_into_order_status extends CDbMigration
{
	public function up()
	{
            
            $transaction = $this->getDbConnection()->beginTransaction();
            try {
                $this->insert('order_status', array(
                    'id' => 3,
                    'name' => 'Закрыт'));
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
		echo "m150527_084650_insert_into_order_status does not support migration down.\n";
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