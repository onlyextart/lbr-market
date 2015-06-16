<?php

class m150303_130641_fields extends CDbMigration
{
	public function up()
	{
                $transaction = $this->getDbConnection()->beginTransaction();
                try {
                    $this->addColumn('product', 'update_time', 'timestamp');
                    $this->addColumn('category', 'update_time', 'timestamp');
                    $this->addColumn('model_line', 'update_time', 'timestamp');

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
		echo "m150303_130641_fields does not support migration down.\n";
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