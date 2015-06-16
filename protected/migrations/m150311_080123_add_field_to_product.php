<?php

class m150311_080123_add_field_to_product extends CDbMigration
{
	public function up()
	{
            $transaction = $this->getDbConnection()->beginTransaction();
            try {
                $this->addColumn('product', 'weight', 'real');

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
		echo "m150311_080123_add_field_to_product does not support migration down.\n";
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