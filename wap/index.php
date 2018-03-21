<?php
// $Id$ 
/**
 * 应用程序启动脚本
 */
ini_set("display_errors", "1");
error_reporting(E_ALL);

global $g_boot_time;
$g_boot_time = microtime(true);

date_default_timezone_set("Asia/Shanghai");
$app_config = require(dirname(__FILE__) . '/_code/config/boot.php');
require $app_config['QEEPHP_DIR'] . '/library/q.php';
require $app_config['APP_DIR'] . '/myapp.php';

//把sims中的model加入class路径
Q::import(realpath($app_config['ROOT_DIR'].'/../sims/_code/app/model'));

$ret = MyApp::instance($app_config)->dispatching();
if (is_string($ret)) echo $ret;
 
return $ret;
