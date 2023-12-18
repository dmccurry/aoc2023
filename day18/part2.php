<?php
$file = file_get_contents("./input");
$start = microtime(true);
$current = [0, 0];
$edge_count = 0;
$vertices = [];
$vertices[] = [0, 0];
foreach (explode("\n", $file) as $line) {
    [$_dir, $_num, $hex] = explode(" ", $line);
    $num = hexdec(substr($hex, 2, 5));
    $dir = substr($hex, -2, 1);
    if ($dir == "0") $dir = "R";
    if ($dir == "1") $dir = "D";
    if ($dir == "2") $dir = "L";
    if ($dir == "3") $dir = "U";

    $edge_count += $num;
    if ($dir == "R") {
        $current[1] += $num;
    } else if ($dir == "L") {
        $current[1] -= $num;
    } else if ($dir == "U") {
        $current[0] -= $num;
    } else if ($dir == "D") {
        $current[0] += $num;
    }
    $vertices[] = $current;
}
$area = 0;
for ($i = 0; $i < count($vertices) - 1; $i++) {
    [$x1, $y1] = $vertices[$i];
    [$x2, $y2] = $vertices[$i+1];
    $area += $x1*$y2 - $x2*$y1;
}
$area = abs($area) / 2;
$perim = $edge_count / 2;

$int = $area - $perim + 1;
$total = $edge_count + $int;

print "Solution is $total\n";
print "Took " . number_format((microtime(true) - $start) * 1000, 2) . " ms\n";