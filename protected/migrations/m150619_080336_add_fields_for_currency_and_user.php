<?php

class m150619_080336_add_fields_for_currency_and_user extends CDbMigration
{
	public function up()
	{
            $transaction = $this->getDbConnection()->beginTransaction();
            try {
                $this->addColumn('currency', 'exchange_rate', 'text');
                $this->addColumn('currency', 'update_time', 'timestamp');
                $this->addColumn('user', 'country_id', 'integer');
                
                $this->createTable('user_country', array(
                    'id' => 'pk',
                    'name' => 'text',
                    'label' => 'text',
                ));

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
		echo "m150619_080336_add_fields_for_currency_and_user does not support migration down.\n";
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