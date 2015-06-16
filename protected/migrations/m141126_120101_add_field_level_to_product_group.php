<?php

class m141126_120101_add_field_level_to_product_group extends CDbMigration
{
	public function up()
	{
            $transaction = $this->getDbConnection()->beginTransaction();
            try {
                $this->addColumn('product_group', 'level', 'int');
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
		echo "m141126_120101_add_field_level_to_product_group does not support migration down.\n";
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