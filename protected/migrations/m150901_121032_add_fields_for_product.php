<?php

class m150901_121032_add_fields_for_product extends CDbMigration
{
	public function up()
	{
            $transaction = $this->getDbConnection()->beginTransaction();
            try {
                $this->addColumn('product', 'problem', 'text');
                $this->addColumn('product', 'units', 'text');
                $this->addColumn('product', 'multiplicity', 'text');
                $this->addColumn('product', 'material', 'text');
                $this->addColumn('product', 'size', 'text');
                $this->addColumn('product', 'date_sale_off', 'timestamp');

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
		echo "m150901_121032_add_fields_for_product does not support migration down.\n";
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