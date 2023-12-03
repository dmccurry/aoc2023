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
        if ($chars[$j] == "*") {
            $adjacent_nums = array();
            // immediate left
            if (is_numeric($chars[$j - 1])) {
                $k = $j - 1;
                $num = $chars[$k];
                while ($k > 0 && is_numeric($chars[--$k])) $num .= $chars[$k];
                $adjacent_nums[] = intval(strrev($num));
            }
            // immediate right
            if (is_numeric($chars[$j + 1])) {
                $k = $j + 1;
                $num = $chars[$k];
                while ($k > 0 && $k < count($chars) - 1 && is_numeric($chars[++$k])) $num .= $chars[$k];
                $adjacent_nums[] = intval($num);
            }
            // above
            // so much copy paste...
            if ($i > 0) {
                if (is_numeric($lines[$i-1][$j-1])) {
                    $k = $j - 1;
                    while($k >= 0 && is_numeric($lines[$i-1][--$k])); 
                    $num = "";
                    $lines[$i-1][$k] = ".";
                    while($k < count($lines[$i-1]) && is_numeric($lines[$i-1][++$k])) {
                        $num .= $lines[$i-1][$k];
                        $lines[$i-1][$k] = ".";
                    }
                    $adjacent_nums[] = intval($num);
                }
                if (is_numeric($lines[$i-1][$j])) {
                    $k = $j;
                    while($k >= 0 && is_numeric($lines[$i-1][--$k])); 
                    $num = "";
                    $lines[$i-1][$k] = ".";
                    while($k < count($lines[$i-1]) && is_numeric($lines[$i-1][++$k])) {
                        $num .= $lines[$i-1][$k];
                        $lines[$i-1][$k] = ".";
                    }
                    $adjacent_nums[] = intval($num);
                }
                if (is_numeric($lines[$i-1][$j+1])) {
                    $k = $j + 1;
                    while($k >= 0 && is_numeric($lines[$i-1][--$k])); 
                    $num = "";
                    $lines[$i-1][$k] = ".";
                    while($k < count($lines[$i-1]) - 1 && is_numeric($lines[$i-1][++$k])) {
                        $num .= $lines[$i-1][$k];
                        $lines[$i-1][$k] = ".";
                    }
                    $adjacent_nums[] = intval($num);
                }
            }

            // below
            if ($i < count($lines) - 1) {
                if (is_numeric($lines[$i+1][$j-1])) {
                    $k = $j - 1;
                    while($k >= 0 && is_numeric($lines[$i+1][--$k])); 
                    $num = "";
                    $lines[$i+1][$k] = ".";
                    while($k < count($lines[$i+1]) && is_numeric($lines[$i+1][++$k])) {
                        $num .= $lines[$i+1][$k];
                        $lines[$i+1][$k] = ".";
                    }
                    $adjacent_nums[] = intval($num);
                }
                if (is_numeric($lines[$i+1][$j])) {
                    $k = $j;
                    while($k >= 0 && is_numeric($lines[$i+1][--$k])); 
                    $num = "";
                    $lines[$i+1][$k] = ".";
                    while($k < count($lines[$i+1]) && is_numeric($lines[$i+1][++$k])) {
                        $num .= $lines[$i+1][$k];
                        $lines[$i+1][$k] = ".";
                    }
                    $adjacent_nums[] = intval($num);
                }
                if (is_numeric($lines[$i+1][$j+1])) {
                    $k = $j + 1;
                    while($k >= 0 && is_numeric($lines[$i+1][--$k])); 
                    $num = "";
                    $lines[$i+1][$k] = ".";
                    while($k < count($lines[$i+1]) - 1 && is_numeric($lines[$i+1][++$k])) {
                        $num .= $lines[$i+1][$k];
                        $lines[$i+1][$k] = ".";
                    }
                    $adjacent_nums[] = intval($num);
                }
            }
            if (count($adjacent_nums) == 2) {
                $total += ($adjacent_nums[0] * $adjacent_nums[1]);
            }
        }
    }
}

print "Solution is $total\n";