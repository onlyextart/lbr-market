<?php
class CronCommand extends CConsoleCommand
{
    public function run($args)
    {
        $this->delSparepartWithoutGroups();
    }
    
    public function delSparepartWithoutGroups()
    {
        Product::model()->deleteAll('product_group_id is null');
    }
}