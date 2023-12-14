<?php

$file = file_get_contents("./input");
$grid = array();
foreach (explode("\n", $file) as $line) {
    $grid[] = str_split($line);
}
$start = microtime(true);
$total = 0;
$cycles = 1000000000;
$cycle = 1;
$cycle_diff = 0;

$grids = array();
$grids[get_grid($grid)] = 0;

while ($cycle <= $cycles) {
    $grid = tilt($grid, "N");
    $grid = tilt($grid, "W");
    $grid = tilt($grid, "S");
    $grid = tilt($grid, "E");
    $cycle++;

    $grid_s = get_grid($grid);
    if (array_key_exists($grid_s, $grids)) {
        $cycle_start = $grids[$grid_s];
        $cycle_diff = $cycle - $cycle_start; 
        break;
    } else {
        $grids[$grid_s] = $cycle;
    }
}

$cycles_remaining = ($cycles - $cycle) % $cycle_diff;
for ($i=0; $i<=$cycles_remaining; $i++) {
    $grid = tilt($grid, "N");
    $grid = tilt($grid, "W");
    $grid = tilt($grid, "S");
    $grid = tilt($grid, "E");
}

for ($i=0; $i<count($grid); $i++) {
    for($j=0; $j<count($grid[$i]); $j++) {
        if ($grid[$i][$j] == "O") $total += count($grid) - $i;
    }
}
print "Solution is $total\n";
print "Took " . number_format((microtime(true) - $start) * 1000, 2) . " ms\n";

function tilt($g, $dir) {
    $grid = $g;
    if ($dir == "N") {
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
    } else if ($dir == "S") {
        for ($i=count($grid) - 1; $i>=0; $i--) {
            for ($j=0; $j<count($grid[$i]); $j++) {
                if ($grid[$i][$j] == "O") {
                    $current_row = $i+1;
                    while ($current_row < count($grid) && $grid[$current_row][$j] == ".") {
                        $grid[$current_row-1][$j] = ".";
                        $grid[$current_row][$j] = "O";
                        $current_row++;
                    }
                }
            }
        }
    } else if ($dir == "W") {
        for ($j = 1; $j<count($grid[0]); $j++) {
            for ($i=0; $i<count($grid); $i++) {
                if ($grid[$i][$j] == "O") {
                    $current_col = $j-1;
                    while ($current_col >= 0 && $grid[$i][$current_col] == ".") {
                        $grid[$i][$current_col+1] = ".";
                        $grid[$i][$current_col] = "O";
                        $current_col--;
                    }
                }
            }
        }
    } else if ($dir == "E") {
        for ($j = count($grid[0]) - 1; $j>=0; $j--) {
            for ($i=0; $i<count($grid); $i++) {
                if ($grid[$i][$j] == "O") {
                    $current_col = $j+1;
                    while ($current_col <count($grid[$i]) && $grid[$i][$current_col] == ".") {
                        $grid[$i][$current_col-1] = ".";
                        $grid[$i][$current_col] = "O";
                        $current_col++;
                    }
                }
            }
        }
    }
    return $grid;
}

function print_grid($grid) {
    $out = "";
    foreach ($grid as $g) $out .= implode("", $g) . "\n";
    print $out;
}
function get_grid($grid) {
    $out = "";
    foreach ($grid as $g) $out .= implode("", $g);
    return $out;
}