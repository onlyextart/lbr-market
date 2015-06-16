<?php

class m150324_113422_add_tables_for_draft extends CDbMigration
{
	public function up()
	{
            $transaction = $this->getDbConnection()->beginTransaction();
            try {
                $this->createTable('draft', array(
                    'id' => 'pk',
                    'external_id' => 'text',
                    'name' => 'text',
                    'image' => 'text',
                ));

                $this->createTable('product_in_draft', array(
                    'id' => 'pk',
                    'draft_id' => 'integer NOT NULL REFERENCES draft(id) ON DELETE CASCADE ON UPDATE CASCADE',
                    'product_id' => 'integer NOT NULL REFERENCES product(id) ON DELETE CASCADE ON UPDATE CASCADE',
                    'level' => 'text'
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
		echo "m150324_113422_add_tables_for_draft does not support migration down.\n";
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