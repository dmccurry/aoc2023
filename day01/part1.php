<?php

$input = file_get_contents("./input");
$parts = explode("\n", $input);
$total = 0;
foreach ($parts as $part) {
    preg_match_all("/[0-9]/", $part, $matches);
    $nums = $matches[0];
    $num = $nums[0] . $nums[count($nums) - 1];
    $total += intval($num);
}

print "Solution is " . $total;