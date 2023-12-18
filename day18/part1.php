<?php
$file = file_get_contents("./input");
$start = microtime(true);
$current = [0, 0];
$edges = [];
$edgets[] = [0, 0];
$vertices = [];
$vertices[] = [0, 0];
foreach (explode("\n", $file) as $line) {
    [$dir, $num, $hex] = explode(" ", $line);
    for ($i=0; $i<intval($num); $i++) {
        if ($dir == "R") {
            $current[1]++;
        } else if ($dir == "L") {
            $current[1]--;
        } else if ($dir == "U") {
            $current[0]--;
        } else if ($dir == "D") {
            $current[0]++;
        }
        $edges[] = $current;
    }
    $vertices[] = $current;
}
$area = 0;
for ($i = 0; $i < count($edges) - 1; $i++) {
    [$x1, $y1] = $edges[$i];
    [$x2, $y2] = $edges[$i+1];
    $area += $x1*$y2 - $x2*$y1;
}
$area = abs($area) / 2;
$perim = count($edges) / 2;

$int = $area - $perim + 1;
$total = count($edges) + $int;

print "Solution is $total\n";
print "Took " . number_format((microtime(true) - $start) * 1000, 2) . " ms\n";