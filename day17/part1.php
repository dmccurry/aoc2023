<?php
class Heap extends SplPriorityQueue {
    public function compare($priority1, $priority2): int {
        return parent::compare($priority2, $priority1);
    }
}

$file = file_get_contents("./input");
$grid = array();
foreach(explode("\n", $file) as $line) {
    $grid[] = array_map(fn($v) => intval($v), str_split($line));
}
$start = microtime(true);

$heap = new Heap();
// [x, y, previous x, previous y, dir x, dir y, steps, loss]
$heap->insert([0, 0, -1, -1, 0, 1, 0, 0], INF);
$visited = array();
$moves = array(
    [0, -1],
    [1, 0],
    [0, 1],
    [-1, 0]
);

while ($heap->count() > 0) {
    [$x, $y, $px, $py, $dx, $dy, $steps, $loss] = $heap->extract();
    if ($x == count($grid) - 1 && $y == count($grid[$x]) - 1) {
        print "Solution is $loss\n";
        print "Took " . number_format((microtime(true) - $start) * 1000, 2) . " ms\n";
        return;
    }

    $key = "$x,$y,$dx,$dy,$steps";
    if (array_key_exists($key, $visited)) {
        continue;
    }

    foreach ($moves as [$movex, $movey]) {
        $newx = $x + $movex;
        $newy = $y + $movey;
        // off grid
        if ($newx < 0 || $newx >= count($grid) || $newy < 0 || $newy >= count($grid[0])) {
            continue;
        } 
        // backwards
        if ($newx == $px && $newy == $py) {
            continue;
        }
        $new_loss = $loss + $grid[$newx][$newy];

        if ($dx == $movex && $dy == $movey) {
            if ($steps < 3) { // can go straight in same direction
                $heap->insert([$newx, $newy, $x, $y, $dx, $dy, $steps + 1, $new_loss], $new_loss);
            } 
        } else { // or turn
            $heap->insert([$newx, $newy, $x, $y, $movex, $movey, 1, $new_loss], $new_loss);
        }
    }
    $visited[$key] = 1;
}