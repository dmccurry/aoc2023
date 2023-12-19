<?php
$file = file_get_contents("./input");
[$list_workflows, $list_parts] = explode("\n\n", $file);
$workflows = array();
foreach (explode("\n", $list_workflows) as $w) {
    [$key, $workflow] = explode("{", $w);
    $workflow = str_replace("}", "", $workflow);
    $steps = explode(",", $workflow);
    $workflows[$key] = $steps;
}
$start = microtime(true);
$x = [1, 4000];
$m = [1, 4000];
$a = [1, 4000];
$s = [1, 4000];

$all_steps = array();
$all_steps[] = ["in", $x, $m, $a, $s];
$total = 0;
while (count($all_steps)) {
    [$st, $x, $m, $a, $s] = array_pop($all_steps);
    if ($st == "A") {
        $total += ($x[1] - $x[0]+1) * ($m[1] - $m[0]+1) * ($a[1] - $a[0]+1) * ($s[1] - $s[0]+1);
        continue;
    } 
    if ($st == "R" || $x[1] < $x[0] || $m[1] < $m[0] || $a[1] < $a[0] || $s[1] < $s[0]) {
        continue;
    }
    $steps = $workflows[$st];

    foreach($steps as $step) {
        if (stripos($step, ":") > 0) {
            [$condition, $target] = explode(":", $step);
            $var = substr($condition, 0, 1);
            $sign = substr($condition, 1, 1);
            $num = intval(substr($condition, 2));
            $all_steps[] = next_step($target, $num, $sign, $var, $x, $m, $a, $s);
            $new_step = next_step($target, $num, $sign == ">" ? "<=" : ">=", $var, $x, $m, $a, $s);
            [$_, $x, $m, $a, $s] = $new_step;

        } else {
            $all_steps[] = [$step, $x, $m, $a, $s];
        }
    }
}

print "Solution is $total\n";
print "Took " . number_format((microtime(true) - $start) * 1000, 2) . " ms\n";

function get_range($range, $sign, $num) {
    if ($sign == ">") {
        return [max($range[0], $num+1), $range[1]];
    } else if ($sign == "<") {
        return [$range[0], min($num-1, $range[1])];
    } else if ($sign == ">=") {
        return [max($range[0], $num), $range[1]];
    } else if ($sign == "<=") {
        return [$range[0], min($num, $range[1])];
    }
}

function next_step($target, $num, $sign, $var, $x, $m, $a, $s) {
    switch ($var) {
        case "x": 
            $x = get_range($x, $sign, $num);
        break;
        case "m":
            $m = get_range($m, $sign, $num);
        break;
        case "a":
            $a = get_range($a, $sign, $num);
        break;
        case "s":
            $s = get_range($s, $sign, $num);
        break;
    }
    return [$target, $x, $m, $a, $s];
}