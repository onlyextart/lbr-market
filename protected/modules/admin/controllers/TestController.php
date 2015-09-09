<?php

class TestController extends Controller
{
    public function actionIndex()
    {
        //ini_set('memory_limit','2048M');
        $memory = (int)ini_get("memory_limit");
        echo ' -> '.$memory;
    }
}
