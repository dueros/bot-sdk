<?php
/**
 * @desc 入口文件
 */
require '../../../../../vendor/autoload.php';
require (__DIR__.'/src/Bot.php');

if($_SERVER['REQUEST_METHOD'] == 'HEAD'){
    header('HTTP/1.1 204 No Content');
    exit;
}
header("Content-Type:application/json");
//记录整体执行时间
$demo = new Bot();

$demo->log->markStart('all_t');
$ret = $demo->run();
$demo->log->markEnd('all_t');

//打印日志
$demo->log->notice($demo->log->getField('url_t'));
$demo->log->notice();

print $ret;
