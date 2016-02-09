<?php

$start = microtime(true);
// change the following paths if necessary
$yii=dirname(__FILE__).'/../yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

/* Debug options */

error_reporting(-1);
ini_set('display_errors', true);

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);


/* end Debug options */

require_once($yii);

//require_once(dirname(__FILE__).'/../../../../../../usr/share/pear/Mail.php');
//require_once('/usr/share/pear/Mail/mime.php');  
Yii::createWebApplication($config)->run();
