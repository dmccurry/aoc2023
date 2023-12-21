<?php
$file = file_get_contents("./input");
$grid = array();
foreach (explode("\n", $file) as $line) {
    $grid[] = str_split($line);
}
$start = microtime(true);

$pos = array();
for ($i=0; $i<count($grid); $i++) {
    for ($j=0; $j<count($grid[$i]); $j++) {
        if ($grid[$i][$j] == "S") {
            $pos["$i,$j"] = 1;
        }
    }
}

$total_steps = 64;
$step = 0;
while ($step++ < $total_steps) {
    $new_pos = array();

    foreach ($pos as $key => $value) {
        [$row, $col] = explode(",", $key);
        $row = intval($row);
        $col = intval($col);
        $possible_steps = [
            [$row-1, $col],
            [$row+1, $col],
            [$row, $col-1],
            [$row, $col+1]
        ];

        foreach ($possible_steps as [$prow, $pcol]) {
            if ($prow >= 0 && $prow < count($grid) && $pcol >= 0 && $pcol < count($grid[0])) {
                if ($grid[$prow][$pcol] != "#") {
                    $new_pos["$prow,$pcol"] = 1;
                }
                
            }
        }
    }
    $pos = $new_pos;
}
print "Solution is " . count($pos) . "\n";
print "Took " . number_format((microtime(true) - $start) * 1000, 2) . " ms\n";