<?php
$file = file_get_contents("./input");
$steps = explode(",", $file);
$start = microtime(true);
$total = 0;
foreach ($steps as $step) {
    $value = 0;
    $parts = str_split($step);
    foreach ($parts as $p) {
        $value += ord($p);
        $value = 17 * $value;
        $value =  $value % 256;
    }
    $total += $value;
}
print "Solution is $total\n";
print "Took " . number_format((microtime(true) - $start) * 1000, 2) . " ms\n";