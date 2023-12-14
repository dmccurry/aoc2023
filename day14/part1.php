<?php

$file = file_get_contents("./input");
$grid = array();
foreach (explode("\n", $file) as $line) {
    $grid[] = str_split($line);
}
$start = microtime(true);
for($i=1; $i<count($grid); $i++) {
    for ($j=0; $j<count($grid[$i]); $j++) {
        if ($grid[$i][$j] == "O") {
            $current_row = $i-1;
            while ($current_row >= 0 && $grid[$current_row][$j] == ".") {
                $grid[$current_row+1][$j] = ".";
                $grid[$current_row][$j] = "O";
                $current_row--;
            }
        }
    }
}
$total = 0;
for ($i=0; $i<count($grid); $i++) {
    for($j=0; $j<count($grid[$i]); $j++) {
        if ($grid[$i][$j] == "O") $total += count($grid) - $i;
    }
}
print "Solution is $total\n";
print "Took " . number_format((microtime(true) - $start) * 1000, 2) . " ms\n";