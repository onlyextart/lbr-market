<?php

class m160412_120225_table_changes_item extends CDbMigration
{
	public function up()
	{
            $transaction = $this->getDbConnection()->beginTransaction();
            try {
                $this->createTable('changes_item', array(
                    'id' => 'pk',
                    'item_name' => 'text',
                ));
                $this->insert('changes_item', array('id' => 1, 'item_name' => 'Спецпредложения'));
                $this->insert('changes_item', array('id' => 2, 'item_name' => 'Категории'));
                $this->insert('changes_item', array('id' => 3, 'item_name' => 'Производители техники в категории'));
                $this->insert('changes_item', array('id' => 4, 'item_name' => 'Валюта'));
                $this->insert('changes_item', array('id' => 5, 'item_name' => 'Производители техники'));
                $this->insert('changes_item', array('id' => 6, 'item_name' => 'Группы товаров'));
                $this->insert('changes_item', array('id' => 7, 'item_name' => 'Модельные ряды'));
                $this->insert('changes_item', array('id' => 8, 'item_name' => 'Заказы'));
                $this->insert('changes_item', array('id' => 9, 'item_name' => 'Страницы'));
                $this->insert('changes_item', array('id' => 10, 'item_name' => 'Запчасти'));
                $this->insert('changes_item', array('id' => 11, 'item_name' => 'Производители запчастей'));
                $this->insert('changes_item', array('id' => 12, 'item_name' => 'Пользователи'));
                $this->insert('changes_item', array('id' => 13, 'item_name' => 'API'));
                $transaction->commit();
            } catch(Exception $e) {
                echo "Exception: ".$e->getMessage()."\n";
                $transaction->rollback();
                return false;
            }
	}

	public function down()
	{
		echo "m160412_120225_table_changes_item does not support migration down.\n";
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