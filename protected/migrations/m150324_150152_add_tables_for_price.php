<?php

class m150324_150152_add_tables_for_price extends CDbMigration
{
	public function up()
	{
            $transaction = $this->getDbConnection()->beginTransaction();
            try {
                $this->createTable('filial', array(
                    'id' => 'pk',
                    'external_id' => 'text',
                    'name' => 'text',
                    'update_time' => 'timestamp',
                    'rgt' => 'integer',
                    'lft' => 'integer',
                    'parent' => 'integer',
                    'level' => 'integer',
                ));

                $this->createTable('price_in_filial', array(
                    'id' => 'pk',
                    'product_id' => 'integer NOT NULL REFERENCES product(id) ON DELETE CASCADE ON UPDATE CASCADE',
                    'filial_id' => 'integer NOT NULL REFERENCES filial(id) ON DELETE CASCADE ON UPDATE CASCADE',
                    'price' => 'text',
                    'currency_code' => 'text',
                ));
                
                $transaction->commit();
            } catch(Exception $e) {
                echo "Exception: ".$e->getMessage()."\n";
                $transaction->rollback();
                return false;
            }
	}

	public function down()
	{
		echo "m150324_150152_add_tables_for_price does not support migration down.\n";
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