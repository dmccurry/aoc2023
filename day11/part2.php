<?php
$file = file_get_contents("./input");
$grid = array();
foreach(explode("\n", $file) as $line) {
    $grid[] = str_split($line);
}
$start = microtime(true);
$empty_rows = array();
for ($i=0; $i<count($grid); $i++) {
    $row = $grid[$i];
    $row_empty = true;
    for ($j=0; $j<count($row); $j++) {
        if ($row[$j] == "#") $row_empty = false;
    }
    if ($row_empty) $empty_rows[] = $i;
}
$empty_columns = array();
$row_length = count($grid[0]);
for ($i=0; $i<$row_length; $i++) {
    $col_empty = true;
    for ($j=0; $j<count($grid); $j++) {
        if ($grid[$j][$i] == "#") $col_empty = false;
    }
    if ($col_empty) $empty_columns[] = $i;
}

$points = array();
$row_add = 0;
$col_add = 0;
$factor = 1000000;
for ($i=0; $i<count($grid); $i++) {
    if (in_array($i, $empty_rows)) {
        $row_add++;
    }
    $col_add = 0;
    for ($j=0; $j<count($grid[$i]); $j++) {
        if (in_array($j, $empty_columns)) {
            $col_add++;
        }
        if ($grid[$i][$j] == "#") {
            $points[] = [($i + $row_add * ($factor - 1)), ($j + $col_add * ($factor - 1))];
        }
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