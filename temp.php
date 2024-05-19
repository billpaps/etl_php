<?php

//$time = date("Y-m-d", "1715695200");
//$datetime = new DateTime($time);
$begin = new DateTime('2024-05-13');
$end = new DateTime('2024-05-14');

var_dump($begin);
var_dump($end);

$interval = DateInterval::createFromDateString('1 day');
$period = new DatePeriod($begin, $interval, $end);

foreach ($period as $dt) {
    echo $dt->format("Y-m-d\n");
}
