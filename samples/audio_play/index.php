<?php
require "Bot.php";
$bot = new Bot();

header("Content-Type: application/json");

$ret = $bot->run();

print $ret;
