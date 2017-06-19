<?php
$t = microtime(1);
require "Bot.php";
$rentCar = new Bot('rent_car');

header("Content-Type: application/json");

//记录整体执行时间
$rentCar->log->markStart('all_t');
$ret = $rentCar->run();
$rentCar->log->markEnd('all_t');

//打印日志
//or 在register_shutdown_function增加一个执行函数
$rentCar->log->notice('rent_car');

print $ret;
