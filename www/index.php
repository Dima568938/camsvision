<?php

$yii=dirname(__FILE__).'/Yii/yii.php';
//$config=dirname(__FILE__).'/protected/config/config.php';
$config=dirname(__FILE__).'/protected/config/local_debug_config.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);

// режим отладки при недоступном VK API
//defined('YII_NO_VK') or define('YII_NO_VK',false);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);


require_once($yii);
Yii::createWebApplication($config)->run();

//echo $_SERVER[HTTP_REFERER];
//print_r($_REQUEST);
//print_r($_SERVER);



