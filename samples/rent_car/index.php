<?php

require "Bot.php";
$rentCar = new Bot('rent_car');

header("Content-Type: application/json");
#print json_encode($rentCar->run(), JSON_UNESCAPED_UNICODE);
print $rentCar->run();
