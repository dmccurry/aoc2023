<?php
$file = file_get_contents("./input");
$patterns = explode("\n\n", $file);
$start = microtime(true);
$output = array();
$total = 0;
foreach ($patterns as $pattern) {
    $lines = explode("\n", $pattern);
    $has_reflection = false;
    $flip = false;
    // do the row.
    // 32718 too high
    for ($i=1; $i<count($lines); $i++) {
        $flip = false;
        // a reflection will always start with adjacent lines equal
        if ($lines[$i-1] == $lines[$i] || match_lines($lines[$i-1], $lines[$i])) {
            if ($lines[$i-1] != $lines[$i]) $flip = true;
            $reflection = true;
            // now we need to go in both directions until we hit the end
            $prev = $i-2;
            $next = $i+1;
            while ($prev >= 0 && $next < count($lines)) {
                if ($lines[$prev] == $lines[$next]) { // match
                    // good
                } else if (!$flip) {
                    if (match_lines($lines[$prev], $lines[$next])) {
                        $flip = true;
                    } else {
                        $reflection = false;
                    }
                } else {
                    $reflection = false;
                }
                $prev--;
                $next++;
            }
            if ($reflection && $flip) {
                $has_reflection = true;
                $total = $total + (100 * $i);
                break;
            }
        }
    }

    if (!$has_reflection) {
        $rotated  = rotate($pattern);
        $lines = explode("\n", $rotated);
        $has_reflection = false;
        $flip = false;
        // do the col.
        for ($i=1; $i<count($lines); $i++) {
            $flip = false;
            if ($lines[$i-1] == $lines[$i] || match_lines($lines[$i-1], $lines[$i])) {
                if ($lines[$i-1] != $lines[$i]) $flip = true;
                $reflection = true;
                // now we need to go in both directions until we hit the end
                $prev = $i-2;
                $next = $i+1;
                while ($prev >= 0 && $next < count($lines)) {
                    if ($lines[$prev] == $lines[$next]) { // match
                        // good
                    } else if (!$flip) {
                        if (match_lines($lines[$prev], $lines[$next])) {
                            $flip = true;
                        } else {
                            $reflection = false;
                        }
                    } else {
                        $reflection = false;
                    }
                    $prev--;
                    $next++;
                }
                if ($reflection && $flip) {
                    $has_reflection = true;
                    $total += $i;
                    break;
                }
            }
        }
    }
}

print "Solution is $total\n";
print "Took " . number_format((microtime(true) - $start) * 1000, 2) . " ms\n";

function match_lines($a, $b) {
    $as = str_split($a);
    $bs = str_split($b);
    $m = 0;
    for ($i=0; $i<count($as); $i++) {
        if ($as[$i] == $bs[$i]) $m++;
    }
    return $m == count($as) - 1 ? true : false;
}

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