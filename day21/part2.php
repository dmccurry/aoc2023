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

$total_steps = 131 + 131 + 65;
$totals = array();
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
        $rows = count($grid);
        $cols = $rows;
        foreach ($possible_steps as [$prow, $pcol]) {
            $grid_row = $prow;
            $grid_col = $pcol;
            if ($grid_row < 0) $grid_row = $rows - abs($grid_row % $rows);
            if ($grid_col < 0) $grid_col = $cols - abs($grid_col % $cols);
            if ($grid_row > $rows - 1) $grid_row = $grid_row % $rows;
            if ($grid_col > $cols - 1) $grid_col = $grid_col % $cols;

            if ($grid[$grid_row][$grid_col] != "#") {
                $new_pos["$prow,$pcol"] = 1;
            }
            
            
        }
    }
    $pos = $new_pos;
    if ($step == 65 || $step == 131+65 || $step == 131+131+65) {
        $totals[] = count($pos);
    }
}
print_r($totals);
$a = $totals[0] / 2 - $totals[1] + $totals[2] / 2;
$b = -3 * ($totals[0] / 2) + 2 * $totals[1] - $totals[2] / 2;
$c = $totals[0];
$p = (26501365 - 65) / 131;
$total = $a * $p * $p + $b * $p + $c;
var_dump($total);

print "Solution is " . $total . "\n";
print "Took " . number_format((microtime(true) - $start) * 1000, 2) . " ms\n";