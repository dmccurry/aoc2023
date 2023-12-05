<?php

$file = file_get_contents("./input");
$file_parts = explode("\n\n", $file);

$seeds_line = array_shift($file_parts);
$seeds_str = explode(": ", $seeds_line);
$seeds_ranges = array_map(fn($value) => intval($value), explode(" ", $seeds_str[1]));

$seeds = array();
// this code to guess
// if you vary what you add to $current you'll be able to 
// get that ballpark for the loop below
// for ($i = 0; $i<count($seeds_ranges); $i+=2) {
//     $start = $seeds_ranges[$i];
//     $end = $seeds_ranges[$i+1] + $seeds_ranges[$i];
//     $current = $start;
//     while ($current < $end) {
//         $seeds[] = $current;
//         $current += 1002;
//     }
// }

// look ^^^^^ to see how to get these
for ($i=3969170000; $i<3969180000; $i++) {
    $seeds[] = $i;
}


$maps = array();
while (count($file_parts)) {
    $map = array();
    $map_file = array_shift($file_parts);
    $map_parts = explode("\n", $map_file);
    array_shift($map_parts);
    for ($i = 0; $i < count($map_parts); $i++) {
        $map[] = array_map(fn($value) => intval($value), explode(" ", $map_parts[$i]));
    }
    $maps[] = $map;
}
$targets = array();
for ($i=0; $i<count($maps); $i++) {
    if ($i == 0) {
        $input = $seeds;
    } else {
        $input = $targets[$i - 1];
    }
    $map = $maps[$i];
    $outputs = array();
    foreach ($input as $seed) {
        $output = $seed;
        foreach($map as $ranges) {
            if ($seed >= $ranges[1] && $seed < $ranges[1] + $ranges[2]) {
                $output = $ranges[0] + $seed - $ranges[1];
            }
        }
        $outputs[] = $output;
    }
    $targets[] = $outputs;
}

$locations = $targets[6];
$min_location_index = 0;
$min_location = $locations[0];
foreach ($locations as $i => $location) {
    if ($location < $min_location) {
        $min_location = $location;
        $min_location_index = $i;
    }
}

print "Solution is $min_location for seed $seeds[$min_location_index]\n";