<?php
$file = file_get_contents("./input");
$grid = array();
foreach (explode("\n", $file) as $lines) {
    $grid[] = str_split($lines);
}
$start = microtime(true);
$max = 0;
for ($i=0; $i<count($grid[0]); $i++) {
    $beams = array();
    $beams[] = create_beam(-1, $i, "D");
    $visited = get_visited($beams, $grid);
    if (count($visited) - 1 > $max) {
        $max = count($visited) - 1;
    }
}

for ($i=0; $i<count($grid[0]); $i++) {
    $beams = array();
    $beams[] = create_beam(count($grid), $i, "U");
    $visited = get_visited($beams, $grid);
    if (count($visited) - 1 > $max) {
        $max = count($visited) - 1;
    }
}

for ($i=0; $i<count($grid); $i++) {
    $beams = array();
    $beams[] = create_beam($i, -1, "R");
    $visited = get_visited($beams, $grid);
    if (count($visited) - 1 > $max) {
        $max = count($visited) - 1;
    }
}

for ($i=0; $i<count($grid); $i++) {
    $beams = array();
    $beams[] = create_beam($i, count($grid[$i]), "L");
    $visited = get_visited($beams, $grid);
    if (count($visited) - 1 > $max) {
        $max = count($visited) - 1;
    }
}

print "Solution is $max\n";
print "Took " . number_format((microtime(true) - $start) * 1000, 2) . " ms\n";

function get_visited($beams, $grid) {
    $done = false;
    $visited = array();
    while (!$done) {
        foreach($beams as $beam) {
            if ($beam->active) {
                $key = "$beam->row,$beam->col";
                if (!array_key_exists($key, $visited)) {
                    $visited[$key] = array();
                    $visited[$key][] = $beam->dir;
                } else {
                    $dirs = $visited[$key];
                    if (in_array($beam->dir, $dirs)) {
                        $beam->active = false;
                    } else {
                        $visited[$key][] = $beam->dir;
                    }
                }
                

                switch($beam->dir) {
                    case "R":
                        $beam->col++;
                        break;
                    case "L":
                        $beam->col--;
                        break;
                    case "D":
                        $beam->row++;
                        break;
                    case "U":
                        $beam->row--;
                        break;
                    default:
                        die("trying to go in a bad direction");
                }
                // check to make sure we haven't gone off grid
                if ($beam->col < 0 || $beam->row < 0 || $beam->col == count($grid[0]) || $beam->row == count($grid)) {
                    $beam->active = false;
                    break;
                }

                $current = $grid[$beam->row][$beam->col];
                if ($current == "/") {
                    switch($beam->dir) {
                        case "U":
                            $beam->dir = "R";
                            break;
                        case "D":
                            $beam->dir = "L";
                            break;
                        case "L":
                            $beam->dir = "D";
                            break;
                        case "R":
                            $beam->dir = "U";
                            break;
                        default: 
                            die("impossible direction when beam hit mirror");
                    }
                } else if ($current == "\\") {
                    switch($beam->dir) {
                        case "U":
                            $beam->dir = "L";
                            break;
                        case "D":
                            $beam->dir = "R";
                            break;
                        case "L":
                            $beam->dir = "U";
                            break;
                        case "R":
                            $beam->dir = "D";
                            break;
                        default: 
                            die("impossible direction when beam hit mirror");
                    }
                } else if ($current == "-" && ($beam->dir == "U" || $beam->dir == "D")) {
                    $beam->dir = "L";
                    $beams[] = create_beam($beam->row, $beam->col, "R");
                } else if ($current == "|" && ($beam->dir == "L" || $beam->dir =="R")) {
                    $beam->dir = "U";
                    $beams[] = create_beam($beam->row, $beam->col, "D");
                }
            }
        }

        $done = !has_active_beams($beams);
    }
    return $visited;
}

function create_beam($row, $col, $dir) {
    $beam = new stdClass();
    $beam->row = $row;
    $beam->col = $col;
    $beam->dir = $dir;
    $beam->active = true;
    return $beam;
}

function has_active_beams($beams) {
    foreach ($beams as $beam) {
        if ($beam->active == true) return true;
    }
    return false;
}