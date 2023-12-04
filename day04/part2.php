<?php
$file = file_get_contents("./input");

$total = 0;
$lines = explode("\n", $file);
$num_lines = count($lines);
$matching_numbers = array();
$instances = array();

for ($line_num = 0; $line_num < $num_lines; $line_num++) {
    $line = $lines[$line_num];
    [$card, $nums] = explode(":", $line);
    $card_num = intval(trim(str_replace("Card", "", $card)));
    $instances[$card_num] = 1;
    [$winners, $mine] = explode("|", trim($nums));
    $winner_list = explode(" ", trim($winners));
    $mine_list = explode(" ", trim($mine));
    $winner_count = 0;
    foreach ($mine_list as $m) {
        if (trim($m) != "" && in_array($m, $winner_list)) {
            $winner_count++;
        }
    }
    $matching_numbers[$card_num] = $winner_count;
}
    
foreach ($matching_numbers as $card => $winners) {
    for ($i = 0; $i<$winners; $i++) {
        $instances[$card + $i + 1] += $instances[$card];
    }
}
print "Solution is " . array_sum($instances) . "\n";