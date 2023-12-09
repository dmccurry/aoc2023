<?php
$file = file_get_contents("./input");
$file_lines = explode("\n", $file);
$lines = array();
foreach ($file_lines as $file_line) {
    $lines[] = array_map(fn($v) => intval($v), explode(" ", $file_line));
}
$total = 0;
foreach ($lines as $line) {
    $total += $line[0] - get_history($line);
}
print "Solution is $total\n";

function get_history($arr) {
    $arr_new = array();
    $all_zeroes = true;
    for ($i = 0; $i < count($arr) - 1; $i++) {
        $new_a = $arr[$i+1] - $arr[$i];
        if ($new_a != 0) $all_zeroes = false;
        $arr_new[] = $new_a;
    }
    if ($all_zeroes) {
        return 0;
    } else {
        return $arr_new[0] - get_history($arr_new);
    }
}