<?php

require "Bot.php";
$rentCar = new Bot('rent_car');

print $rentCar->run();
