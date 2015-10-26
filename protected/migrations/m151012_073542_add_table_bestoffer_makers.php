<?php

class m151012_073542_add_table_bestoffer_makers extends CDbMigration
{
	public function up()
	{
            $transaction = $this->getDbConnection()->beginTransaction();
            try {
                $this->createTable('bestoffer_makers', array(
                    'id' => 'pk',
                    'bestoffer_id' => 'integer NOT NULL REFERENCES best_offer(id) ON DELETE CASCADE ON UPDATE CASCADE',
                    'maker_id' => 'integer NOT NULL REFERENCES product_maker(id) ON DELETE CASCADE ON UPDATE CASCADE',
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
		echo "m151012_073542_add_table_bestoffer_makers does not support migration down.\n";
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