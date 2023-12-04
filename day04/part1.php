<?php
$file = file_get_contents("./input");

$total = 0;
$lines = explode("\n", $file);
$start = microtime(true);

foreach($lines as $line) {
    [$card, $nums] = explode(":", $line);
    [$winners, $mine] = explode("|", trim($nums));
    $winner_list = explode(" ", trim($winners));
    $mine_list = explode(" ", trim($mine));
    $val = 0;
    foreach ($mine_list as $m) {
        if (trim($m) != "" && in_array($m, $winner_list)) {
            if ($val == 0) { 
                $val = 1;
            } else {
                $val = $val * 2;
            }
        }
    }
    $total += $val;
}

print "Solution is $total\n";
print "Took " . number_format((microtime(true) - $start) * 1000, 2) . " ms\n";