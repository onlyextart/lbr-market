<?php

class m150706_115621_table_category_seo extends CDbMigration
{
	public function up()
	{
            $transaction = $this->getDbConnection()->beginTransaction();
            try {
                $this->createTable('category_seo', array(
                    'id' => 'pk',
                    'category_id' => 'integer',
                    'equipment_id' => 'integer',
                    'meta_title' => 'text',
                    'meta_description' => 'text',
                    'top_text' => 'text',
                    'bottom_text' => 'text',
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
		echo "m150706_115621_meta_table does not support migration down.\n";
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