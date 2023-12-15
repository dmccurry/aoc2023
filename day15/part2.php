<?php
$file = file_get_contents("./input");
$steps = explode(",", $file);
$start = microtime(true);
$boxes = array();
for ($i=0; $i<256; $i++) {
    $boxes[$i] = array();       
}
$total = 0;
foreach ($steps as $step) {
    $hash = get_hash($step);
    if (str_contains($step, "-")) {
        $parts = explode("-", $step);
        $label = $parts[0];
        foreach ($boxes[$hash] as $key => $lens) {
            $lens_label = explode(" ", $lens)[0];
            if ($lens_label == $label) {
                unset($boxes[$hash][$key]);
            }
        }
    } else {
        [$label, $fp] = explode("=", $step);
        $exists = false;
        foreach ($boxes[$hash] as $key => $lens) {
            $lens_label = explode(" ", $lens)[0];
            if ($lens_label == $label) {
                $exists = true;
                $boxes[$hash][$key] = $label . " " . $fp;
            }
        }
        if (!$exists) {
            $boxes[$hash][] = $label . " " . $fp;
        }
    }
}

$total = 0;
for ($i=0; $i<256; $i++) {
    $box = $boxes[$i];
    $j = 0;
    if (count($box) > 0) {
        foreach ($box as $lens) {
            $fp = intval(explode(" ", $lens)[1]);
            $total += ($i+1) * ($j+1) * $fp;
            $j++;
        }
    }
}
print "Solution is $total\n";
print "Took " . number_format((microtime(true) - $start) * 1000, 2) . " ms\n";

function get_hash($step) {
    if (str_contains($step, "-")) {
        $p = explode("-", $step);
    } else {
        $p = explode("=", $step);
    }
    $parts = str_split($p[0]);
    $value = 0;
    foreach ($parts as $p) {
        $value += ord($p);
        $value = 17 * $value;
        $value =  $value % 256;
    }
    return $value;
}