<?php
$file = file_get_contents("./input");
$start = microtime(true);
$bricks = array();
foreach (explode("\n", $file) as $i => $line) {
    $brick = new stdClass();
    $brick->id = $i+1;
    $brick->a = array_map(fn($v) => intval($v), explode(",", explode("~", $line)[0]));
    $brick->b = array_map(fn($v) => intval($v), explode(",", explode("~", $line)[1]));
    $brick->under = array();
    $brick->over = array();
    $bricks[$i+1] = $brick;
}

// sort the bricks by z
uasort($bricks, fn($a, $b) => min($a->a[2], $a->b[2]) <=> min($b->a[2], $b->b[2]));

// now move all the bricks to the final position.
$settled_bricks = array();
foreach ($bricks as $id => $brick) {
    $z = min($brick->a[2], $brick->b[2]);
    if ($z == 1) {
        $settled_bricks[$brick->id] = $brick;
    } else {
        while (can_move($brick, $settled_bricks)) {
            $brick->a[2]--;
            $brick->b[2]--;
        }
        $settled_bricks[$brick->id] = $brick;
    }
}
$bricks = $settled_bricks;
uasort($bricks, fn($a, $b) => min($a->a[2], $a->b[2]) <=> min($b->a[2], $b->b[2]));
foreach ($bricks as $key => $value) {
    foreach ($bricks as $otherkey => $othervalue) {
        if ($key != $otherkey) {
            if (is_over($value, $othervalue)) {
                $value->over[] = $othervalue->id;
                $othervalue->under[] = $value->id;
            }
        }
    }
}
$total = 0;
foreach ($bricks as $brick) {
    if (count($brick->under) == 0) $total++;
    else {
        $can_disentegrate = true;
        foreach ($brick->under as $o) {
            if (count($bricks[$o]->over) == 1) $can_disentegrate = false;
        }
        if ($can_disentegrate) $total++;
    }
}
print "Solution is $total\n";
print "Took " . number_format((microtime(true) - $start) * 1000, 2) . " ms\n";

function can_move($brick, $bricks) {
    if (min($brick->a[2], $brick->b[2]) == 1) return false;
    foreach ($bricks as $b) {
        if (is_over($brick, $b)) {
            return false;
        }
    }
    return true;
}
function is_over($over, $under) {
    $bottom = min($over->a[2], $over->b[2]);
    $top = max($under->a[2], $under->b[2]);
    if ($top + 1 == $bottom) {

        $ox1 = $over->a[0]; $oy1 = $over->a[1];
        $ox2 = $over->b[0]; $oy2 = $over->b[1];
        $ux1 = $under->a[0]; $uy1 = $under->a[1];
        $ux2 = $under->b[0]; $uy2 = $under->b[1];

        // check for overlap of both x and y
        $overlapx = ($ux1 >= $ox1 && $ux1 <= $ox2) || ($ux2 >= $ox1 && $ux2 <= $ox2) || ($ox1 >= $ux1 && $ox1 <= $ux2) || ($ox2 >= $ux1 && $ox2 <= $ux2);
        $overlapy = ($uy1 >= $oy1 && $uy1 <= $oy2) || ($uy2 >= $oy1 && $uy2 <= $oy2) || ($oy1 >= $uy1 && $oy1 <= $uy2) || ($oy2 >= $uy1 && $oy2 <= $uy2);

        return $overlapx && $overlapy;
    }
    return false;
}