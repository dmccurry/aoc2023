<?php
$input = file_get_contents("./input");

$lines = explode("\n", $input);
$total = 0;
foreach ($lines as $line) {
    preg_match_all("/(?=([1-9]|one|two|three|four|five|six|seven|eight|nine))/", $line, $matches);
    $nums = $matches[1];
    $num = get_digit($nums[0]) . get_digit($nums[count($nums) - 1]);
    $total += intval($num);
}

print "Solution is " . $total . "\n";

function get_digit($n) {
    if ($n == "one") return "1";
    if ($n == "two") return "2";
    if ($n == "three") return "3";
    if ($n == "four") return "4";
    if ($n == "five") return "5";
    if ($n == "six") return "6";
    if ($n == "seven") return "7";
    if ($n == "eight") return "8";
    if ($n == "nine") return "9";
    return $n;
}