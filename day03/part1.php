<?php
$file = file_get_contents("./input");

$file_lines = explode("\n", $file);
$lines = array();
$total = 0;
foreach ($file_lines as $file_line) {
    $lines[] = str_split($file_line);
}
for ($i = 0; $i < count($lines); $i++) {
    $chars = $lines[$i];

    for ($j = 0; $j < count($chars); $j++) {
        if (is_numeric($chars[$j])) {
            // part number here.
            $is_part = false;
            $num = $chars[$j];
            // check left, above, below, diagonals
            // above
            if ($i > 0 && is_symbol($lines[$i-1][$j])) $is_part = true;
            if ($j > 0 && is_symbol($chars[$j-1])) $is_part = true;
            if ($i < count($lines) - 1 && is_symbol($lines[$i+1][$j])) $is_part = true;
            if ($i > 0 && $j > 0 && is_symbol($lines[$i-1][$j-1])) $is_part = true;
            if ($i < count($lines) - 1 && $j > 0 && is_symbol($lines[$i+1][$j-1])) $is_part = true;
            while ($j < count($chars) - 1 && is_numeric($chars[++$j])) {
                $num .= $chars[$j];
                if ($i > 0 && is_symbol($lines[$i-1][$j])) $is_part = true;
                if ($i < count($lines) - 1 && is_symbol($lines[$i+1][$j])) $is_part = true;
            }
            if ($j < count($chars) - 1 && is_symbol($lines[$i][$j])) $is_part = true;
            if ($j < count($chars) - 1 && $i > 0 && is_symbol($lines[$i-1][$j])) $is_part = true;
            if ($j < count($chars) - 1 && $i < count($lines) - 1 && is_symbol($lines[$i+1][$j])) $is_part = true;
            
            if ($is_part) $total += intval($num);
        }
    }
}

print "Solution is $total\n";

function is_symbol($c) {
    return !is_numeric($c) && $c != ".";
}