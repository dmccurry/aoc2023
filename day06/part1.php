<?php
$file = file_get_contents("./input");

[$times, $distances] = explode("\n", $file);
$times = trim(str_replace("Time:", "", $times));
$distances = trim(str_replace("Distance:", "", $distances));
$times = explode(" ", $times);
$distances = explode(" ", $distances);
$times_in = array_map(fn($val) => intval($val), array_filter($times, fn($v) => trim($v) != ""));
$distances_in = array_map(fn($val) => intval($val), array_filter($distances, fn($v) => trim($v) != ""));

$times = array();
$distances = array();
foreach($times_in as $time) $times[] = $time;
foreach($distances_in as $distance) $distances[] = $distance;


$total = 1;
for ($i = 0; $i < count($times); $i++) {
    $time = $times[$i];
    $distance = $distances[$i];
    $winners = 0;
    for ($j=0; $j<$time; $j++) {
        $speed = $j;
        $distance_travelled = ($time - $j) * $speed;
        if ($distance_travelled > $distance) {
            $winners++;
        }
    }
    $total *= $winners;
}

print "Solution is $total\n";
