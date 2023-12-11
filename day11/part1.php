<?php
$file = file_get_contents("./input");
$grid = array();
foreach(explode("\n", $file) as $line) {
    $grid[] = str_split($line);
}
$start = microtime(true);
$expanded = array();
$expanded_i = 0;
for ($i=0; $i<count($grid); $i++) {
    $row = $grid[$i];
    $row_empty = true;
    $expanded[$expanded_i] = array();
    for ($j=0; $j<count($row); $j++) {
        if ($row[$j] == "#") $row_empty = false;
        $expanded[$expanded_i][$j] = $row[$j];
    }
    $expanded_i++;
    if ($row_empty) {
        for ($j=0; $j<count($row); $j++) {
            $expanded[$expanded_i][$j] = $row[$j];
        }
        $expanded_i++;
    }
}
$empty_columns = array();
$row_length = count($expanded[0]);
for ($i=0; $i<$row_length; $i++) {
    $col_empty = true;
    for ($j=0; $j<count($expanded); $j++) {
        if ($expanded[$j][$i] == "#") $col_empty = false;
    }
    if ($col_empty) $empty_columns[] = $i;
}
$final_grid = array();
for ($i=0; $i<count($expanded); $i++) {
    $final_grid[$i] = array();
    for ($j=0; $j<count($expanded[$i]); $j++) {
        if (in_array($j, $empty_columns)) {
            $final_grid[$i][] = ".";
        }
        $final_grid[$i][] = $expanded[$i][$j];
    }
}
$points = array();
for ($i=0; $i<count($final_grid); $i++) {
    for ($j=0; $j<count($final_grid[$i]); $j++) {
        if ($final_grid[$i][$j] == "#") $points[] = [$i, $j];
    }
}

$total = 0;
for ($i=0; $i<count($points); $i++) {
    for ($j=$i+1; $j<count($points); $j++) {
        $a = $points[$i];
        $b = $points[$j];
        $d = abs($a[0] - $b[0]) + abs($a[1] - $b[1]);
        $total += $d;
    }
}

print "Solution is $total\n";
print "Took " . number_format((microtime(true) - $start) * 1000, 2) . " ms\n";