<?php

class m151116_081857_add_groups_filter extends CDbMigration
{
	public function up()
	{
            $transaction = $this->getDbConnection()->beginTransaction();
            try {
                $this->addColumn('product_group', 'alias', 'text');
                $this->addColumn('product_group', 'use_in_group_filter', 'integer NOT NULL DEFAULT 0');
                $this->createTable('product_group_filter', array(
                    'id' => 'pk',
                    'group_id' => 'integer NOT NULL REFERENCES product_group(id) ON DELETE CASCADE ON UPDATE CASCADE',
                    'name' => 'text',
                    'lft' => 'integer',
                    'rgt' => 'integer',
                    'parent' => 'integer',
                    'level' => 'integer',
                ));

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
		echo "m151116_081857_add_fields_for_groups does not support migration down.\n";
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