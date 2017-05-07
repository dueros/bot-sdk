<?php

require "Bot.php";
$bot = new Bot();

header("Content-Type: application/json");
#print json_encode($rentCar->run(), JSON_UNESCAPED_UNICODE);
print $bot->run();
