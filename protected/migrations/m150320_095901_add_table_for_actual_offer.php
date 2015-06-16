<?php

class m150320_095901_add_table_for_actual_offer extends CDbMigration
{
	public function up()
	{
            $transaction = $this->getDbConnection()->beginTransaction();
            try {
                $this->createTable('actual_offer', array(
                    'id' => 'pk',
                    'name' => 'text',
                    'img' => 'text',
                    'level' => 'int',
                    'published' => 'bool',
                ));
                
                $this->addColumn('best_offer', 'published', 'bool');
                $this->addColumn('best_offer', 'level', 'int');

                /*$this->createTable('actual_offer_product', array(
                    'id' => 'pk',
                    'actual_offer_id' => 'integer NOT NULL REFERENCES actual_offer(id) ON DELETE CASCADE ON UPDATE CASCADE',
                    'product_id' => 'integer NOT NULL REFERENCES product(id) ON DELETE CASCADE ON UPDATE CASCADE',
                ));*/
                
                $transaction->commit();
            } catch(Exception $e) {
                echo "Exception: ".$e->getMessage()."\n";
                $transaction->rollback();
                return false;
            }
	}

	public function down()
	{
		echo "m150320_095901_add_table_for_actual_offer does not support migration down.\n";
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