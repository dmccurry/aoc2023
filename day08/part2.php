<?php
$file = file_get_contents("./input");
[$directions_in, $locations_in] = explode("\n\n", $file);
$directions = str_split(trim($directions_in));

$locations = array();
$nodes = array();
foreach(explode("\n", $locations_in) as $line) {
    [$point, $lr] = explode(" = ", $line);
    $lr = str_replace("(", "", $lr);
    $lr = str_replace(")", "", $lr);
    [$l, $r] = explode(", ", $lr);
    $locations[$point] = array();
    $locations[$point][] = $l;
    $locations[$point][] = $r;
    if (str_split($point)[2] == "A") $nodes[] = $point;
}

$dir_i = 0;
$counts = array();

foreach ($nodes as $node) {
    $dir_i = 0;
    $counts[$node] = 0;
    $curr = $node;
    while (str_split($curr)[2] != "Z") {
        $dir = $directions[$dir_i++];
        if ($dir_i == count($directions)) $dir_i = 0;
        if ($dir == "L") {
            $curr = $locations[$curr][0];
        } else {
            $curr = $locations[$curr][1];
        }
        $counts[$node]++;
    }
}
$nums = array();
foreach ($counts as $key => $value) {
    $nums[] = $value;
}

print "Solution is " . lcm($nums) . "\n";

function lcm($nums) {
    $ret = $nums[0];

    for ($i=0; $i<count($nums); $i++) {
        $ret = ((($nums[$i] * $ret) / gcd($nums[$i], $ret)));
    }
    return $ret;
}

function gcd($a, $b) {
    if ($b == 0) {
        return $a;
    }
    return gcd($b, $a % $b);
}