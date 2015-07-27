<?php

class m150727_113623_add_table_analitics extends CDbMigration
{
	public function up()
	{
            $transaction=$this->getDbConnection()->beginTransaction();
            try
            {
                $this->createTable('analitics', array(
                    'id' => 'pk',
                    'customer_id' => 'text',
                    'subscription_id' => 'text',
                    'time' => 'text',
                    'link_id' => 'text',
                    'url' => 'text',
                    'date_created' => 'timestamp',
                ));
                
                $transaction->commit();
            }
            catch(Exception $e)
            {
                echo "Exception: ".$e->getMessage()."\n";
                $transaction->rollback();
                return false;
            }
	}

	public function down()
	{
		echo "m150727_113623_add_table_analitics does not support migration down.\n";
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