<?php

class m150422_115938_add_field_for_price extends CDbMigration
{
	public function up()
	{
            $transaction = $this->getDbConnection()->beginTransaction();
            try {
                $this->addColumn('price_in_filial', 'update_time', 'timestamp');

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
		echo "m150422_115938_add_field_for_price does not support migration down.\n";
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