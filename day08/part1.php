<?php
$file = file_get_contents("./input");
[$directions_in, $locations_in] = explode("\n\n", $file);
$directions = str_split(trim($directions_in));

$locations = array();
foreach(explode("\n", $locations_in) as $line) {
    [$point, $lr] = explode(" = ", $line);
    $lr = str_replace("(", "", $lr);
    $lr = str_replace(")", "", $lr);
    [$l, $r] = explode(", ", $lr);
    $locations[$point] = array();
    $locations[$point][] = $l;
    $locations[$point][] = $r;
}

$dir_i = 0;
$steps = 0;
$location = "AAA";
$start = microtime(true);
while ($location != "ZZZ") {
    $dir = $directions[$dir_i++];
    if ($dir_i == count($directions)) $dir_i = 0;
    if ($dir == "L") {
        $location = $locations[$location][0];
    } else {
        $location = $locations[$location][1];
    }
    $steps++;
}
print "Solution is $steps\n";
print "Took " . number_format((microtime(true) - $start) * 1000, 2) . " ms\n";