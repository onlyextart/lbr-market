<?php

class m150423_071904_alias extends CDbMigration
{
	public function up()
	{
            $this->addColumn('category', 'alias', 'text');
	}

	public function down()
	{
		echo "m150423_071904_alias does not support migration down.\n";
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