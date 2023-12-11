<?php
$file = file_get_contents("./input");
$grid = array();
$grid_original = array(); // original (part 1) paths.
$file_lines = explode("\n", $file);
foreach ($file_lines as $line) {
    $grid[] = str_split($line);
    $grid_original[] = str_split($line);
}
$start = microtime(true);
$start_row = -1;
$start_col = -1;
for ($row=0; $row<count($grid); $row++) {
    for ($col=0; $col<count($grid[$row]); $col++) {
        if ($grid[$row][$col] == "S") {
            $start_row = $row;
            $start_col = $col;
        }
    }
}

// BLOCK
// This figures out what we should have as a pipe in start.
$start_l = ".";
$start_r = ".";
$start_u = ".";
$start_d = ".";
if ($start_col > 0) $start_l = $grid[$start_row][$start_col - 1];
if ($start_col < count($grid[$start_row]) - 1) $start_r = $grid[$start_row][$start_col + 1];
if ($start_row > 0) $start_u = $grid[$start_row - 1][$start_col];
if ($start_row < count($grid)) $start_d = $grid[$start_row+1][$start_col];

$start_pipe = ".";
$valid_d = ($start_d == "J" || $start_d == "L" || $start_d == "|");
$valid_l = ($start_l == "F" || $start_l == "L" || $start_l == "-");
$valid_r = ($start_r == "7" || $start_r == "J" || $start_r == "-");
$valid_u = ($start_u == "F" || $start_u == "7" || $start_u == "|");
// -
if ($valid_l && $valid_r) $start_pipe = "-";
// F
if ($valid_r && $valid_d) $start_pipe = "F";
// L
if ($valid_r && $valid_u) $start_pipe = "L";
// 7
if ($valid_l && $valid_d) $start_pipe = "7";
// J
if ($valid_l && $valid_u) $start_pipe = "J";
// |
if ($valid_u && $valid_d) $start_pipe = "|";
$grid[$start_row][$start_col] = $start_pipe;
$grid_original[$start_row][$start_col] = $start_pipe;


// Now to travel.
$visited = array();
$path = array();
$current = [$start_row,$start_col];
$visited[] = "($start_row,$start_col)";
$end = false;
while(!$end) {
    [$next_row,$next_col] = get_next($current, $visited, $grid_original, [$start_row, $start_col]);
    $visited[] = "($next_row,$next_col)";
    $current = [$next_row,$next_col];
    $path[] = $current;
    $grid[$next_row][$next_col] = "X"; // this means its a pipe.
    if ($next_row == $start_row && $next_col == $start_col) $end = true;
}
$total = 0;
for ($row = 0; $row < count($grid); $row++) {
    for ($col=0; $col < count($grid[$row]); $col++) {
        $cross = 0;
        if ($grid[$row][$col] != "X") {
            for ($ncol = $col + 1; $ncol < count($grid[$row]); $ncol++) { // go right from the pipe
                if ($grid[$row][$ncol] == "X") {
                    if ($grid_original[$row][$ncol] == "|" || $grid_original[$row][$ncol] == "J" || $grid_original[$row][$ncol] == "L") {
                        $cross++;
                    }
                }
            }
        }
        // odd number of intersections is inside the polygon.
        if ($cross % 2 == 1) $total++;
    }
}
print "Solution is $total\n";
print "Took " . number_format((microtime(true) - $start) * 1000, 2) . " ms\n";


function get_next($current, $visited, $grid, $start) {
    $current_point = $grid[$current[0]][$current[1]];

    // can move right
    $point_r = ".";
    if ($current_point == "-" || $current_point == "F" || $current_point == "L") {
        $point_row = $current[0];
        $point_col = $current[1] + 1;
        if ($point_col < count($grid[$point_row]) && !in_array("($point_row,$point_col)", $visited)) {
            $point = $grid[$point_row][$point_col];
            if ($point == "-" || $point == "7" || $point == "J") {
                return [$point_row, $point_col];
            }
        }
    }

    // can move left
    $point_l = ".";
    if ($current_point == "-" || $current_point == "7" || $current_point == "J") {
        $point_row = $current[0];
        $point_col = $current[1] - 1;
        if ($point_col >= 0 && !in_array("($point_row,$point_col)", $visited)) {
            $point = $grid[$point_row][$point_col];
            if ($point == "-" || $point == "L" || $point == "F") {
                return [$point_row, $point_col];
            }
        }
    }

    // can move up
    if ($current_point == "|" || $current_point == "L" || $current_point == "J") {
        $point_row = $current[0] - 1;
        $point_col = $current[1];
        if ($point_row >= 0 && !in_array("($point_row,$point_col)", $visited)) {
            $point = $grid[$point_row][$point_col];
            if ($point == "|" || $point == "7" || $point == "F") {
                return [$point_row, $point_col];
            }
        }
    }

    // can move down
    $point_d = ".";
    if ($current_point == "|" || $current_point == "7" || $current_point == "F") {
        $point_row = $current[0] + 1;
        $point_col = $current[1];
        if ($point_row < count($grid) && !in_array("($point_row,$point_col)", $visited)) {
            $point = $grid[$point_row][$point_col];
            if ($point == "|" || $point == "J" || $point == "L") {
                return [$point_row, $point_col];
            }
        }
    }
    return $start;
}