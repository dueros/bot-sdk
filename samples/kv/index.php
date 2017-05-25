<?php
$t = microtime(1);
require "Bot.php";
$rentCar = new Bot();

header("Content-Type: application/json");
#print json_encode($rentCar->run(), JSON_UNESCAPED_UNICODE);
print $rentCar->run();
//var_dump(microtime(1) - $t);
