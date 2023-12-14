<?php
$file = file_get_contents("./input");
$patterns = explode("\n\n", $file);
$start = microtime(true);
$output = array();
$total = 0;
foreach ($patterns as $pattern) {
    $lines = explode("\n", $pattern);
    $has_reflection = false;
    // do the row.
    for ($i=1; $i<count($lines); $i++) {
        // a reflection will always start with adjacent lines equal
        if ($lines[$i-1] == $lines[$i]) {
            $reflection = true;
            // now we need to go in both directions until we hit the end
            $prev = $i-2;
            $next = $i+1;
            while ($prev >= 0 && $next < count($lines)) {
                if ($lines[$prev] != $lines[$next]) {
                    $reflection = false;
                }
                $prev--;
                $next++;
            }
            if ($reflection) {
                $has_reflection = true;
                $total = $total + (100 * $i);
            }
        }
    }

    if (!$has_reflection) {
        $rotated  = rotate($pattern);
        $lines = explode("\n", $rotated);
        $has_reflection = false;
        // do the col.
        for ($i=1; $i<count($lines); $i++) {
            // a reflection will always start with adjacent lines equal
            if ($lines[$i-1] == $lines[$i]) {
                $reflection = true;
                // now we need to go in both directions until we hit the end
                $prev = $i-2;
                $next = $i+1;
                while ($prev >= 0 && $next < count($lines)) {
                    if ($lines[$prev] != $lines[$next]) {
                        $reflection = false;
                    }
                    $prev--;
                    $next++;
                }
                if ($reflection) {
                    $has_reflection = true;
                    $total += $i;
                }
            }
        }
    }
}

print "Solution is $total\n";
print "Took " . number_format((microtime(true) - $start) * 1000, 2) . " ms\n";

function rotate($pattern) {
    $in = array();

    $p = explode("\n", $pattern);
    foreach($p as $i) {
        $in[] = str_split($i);
    }
    $rows = count($in[0]);
    $cols = count($in);

    $r = array();

    for ($i=0; $i<$rows; $i++) {
        $r[] = array();
        for ($j=0; $j<$cols; $j++) {
            $r[$i][$j] = $in[$j][$i];
        }
    }
    
    $out = "";
    for ($i=0; $i<count($r) - 1; $i++) {
        $out .= implode("", $r[$i]) . "\n";
    }
    $out .= implode("", $r[count($r) - 1]);
    return $out;
}