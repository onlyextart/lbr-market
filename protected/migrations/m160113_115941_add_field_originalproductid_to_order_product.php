<?php

class m160113_115941_add_field_originalproductid_to_order_product extends CDbMigration
{
	public function up()
	{
                $transaction = $this->getDbConnection()->beginTransaction();
                try {
                    $this->addColumn('order_product', 'original_product_id', 'integer');

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
		echo "m160113_115941_add_field_analog_to_order_product does not support migration down.\n";
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