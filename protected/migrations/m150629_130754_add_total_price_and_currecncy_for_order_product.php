<?php

class m150629_130754_add_total_price_and_currecncy_for_order_product extends CDbMigration
{
	public function up()
	{
            $transaction = $this->getDbConnection()->beginTransaction();
            try {
                $this->addColumn('order_product', 'total_price', 'text');
                $this->addColumn('order_product', 'currency', 'text');
                $this->addColumn('order_product', 'currency_code', 'text');

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
		echo "m150629_130754_add_total_price_and_currecncy_for_order_product does not support migration down.\n";
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