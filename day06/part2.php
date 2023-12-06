<?php
$file = file_get_contents("./input");

[$times, $distances] = explode("\n", $file);
$times = trim(str_replace("Time:", "", $times));
$distances = trim(str_replace("Distance:", "", $distances));
$times = explode(" ", $times);
$distances = explode(" ", $distances);
$times_in = array_filter($times, fn($v) => trim($v) != "");
$distances_in = array_filter($distances, fn($v) => trim($v) != "");

$time = intval(implode("", $times_in));
$distance = intval(implode("", $distances_in));

$total = 1;
$winners = 0;
for ($j=0; $j<$time; $j++) {
    $speed = $j;
    $distance_travelled = ($time - $j) * $speed;
    if ($distance_travelled > $distance) {
        $winners++;
    }
}
$total *= $winners;

print "Solution is $total\n";
