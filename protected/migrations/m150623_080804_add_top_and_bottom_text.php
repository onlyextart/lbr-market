<?php

class m150623_080804_add_top_and_bottom_text extends CDbMigration
{
	public function up()
	{
            $transaction = $this->getDbConnection()->beginTransaction();
            try {
                $this->addColumn('category', 'top_text', 'text');
                $this->addColumn('category', 'bottom_text', 'text');
                
                $this->addColumn('model_line', 'top_text', 'text');
                $this->addColumn('model_line', 'bottom_text', 'text');
                
                $this->addColumn('equipment_maker', 'top_text', 'text');
                $this->addColumn('equipment_maker', 'bottom_text', 'text');

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
		echo "m150623_080804_add_top_and_bottom_text does not support migration down.\n";
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