<?php

class m160205_110847_add_table_category_makers_top extends CDbMigration
{
	public function up()
	{
            $transaction = $this->getDbConnection()->beginTransaction();
            try {
                $this->createTable('category_makers_top', array(
                    'id' => 'pk',
                    'category_id' => 'integer NOT NULL REFERENCES category(id) ON DELETE CASCADE ON UPDATE CASCADE',
                    'maker_id' => 'integer NOT NULL REFERENCES equipment_maker(id) ON DELETE CASCADE ON UPDATE CASCADE',
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
		echo "m160205_110847_add_table_category_makers_top does not support migration down.\n";
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