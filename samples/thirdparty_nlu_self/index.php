<?php
require "Bot.php";
$bot = new Bot();

header("Content-Type: application/json");

//记录整体执行时间
$bot->log->markStart('all_t');
$ret = $bot->run();
$bot->log->markEnd('all_t');

//打印日志
//or 在register_shutdown_function增加一个执行函数
$bot->log->notice('bot');

print $ret;
