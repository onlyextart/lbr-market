<?php

class m150218_131604_add_fields_to_user extends CDbMigration
{
	public function up()
	{
            $transaction = $this->getDbConnection()->beginTransaction();
            try {
                $this->addColumn('user', 'inn', 'text');
                $this->addColumn('user', 'organization_address', 'text');
                $this->addColumn('user', 'organization_type', 'int');
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
		echo "m150218_131604_add_fields_to_user does not support migration down.\n";
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