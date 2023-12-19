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
$parts = array();
foreach (explode("\n", $list_parts) as $p) {
    $p = str_replace("{", "", $p);
    $p = str_replace("}", "", $p);
    [$x, $m, $a, $s] = explode(",", $p);
    $parts[] = [
        intval(str_replace("x=", "", $x)),
        intval(str_replace("m=", "", $m)),
        intval(str_replace("a=", "", $a)),
        intval(str_replace("s=", "", $s))
    ];
}
$start = microtime(true);
$accepted = array();
$rejected = array();

foreach ($parts as $part) {
    [$x, $m, $a, $s] = $part;
    $step = "in";
    while ($step != "A" && $step != "R") {
        $step = get_next_step($workflows[$step], $x, $m, $a, $s);
    }
    if ($step == "A") {
        $accepted[] = $part;
    } else {
        $rejected[] = $part;
    }
}

$total = 0;
foreach ($accepted as [$x, $m, $a, $s]) {
    $total += $x;
    $total += $m;
    $total += $a;
    $total += $s;
}
print "Solution is $total\n";
print "Took " . number_format((microtime(true) - $start) * 1000, 2) . " ms\n";
function get_next_step($steps, $x, $m, $a, $s) {
    foreach($steps as $step) {
        if (stripos($step, ":") > 0) {
            [$condition, $target] = explode(":", $step);
            $var = substr($condition, 0, 1);
            $sign = substr($condition, 1, 1);
            $num = intval(substr($condition, 2));
            $val = $s;
            if ($var == "x") {
                $val = $x;
            } else if ($var == "m") {
                $val = $m;
            } else if ($var == "a") {
                $val = $a;
            }

            if ($sign == ">") {
                if ($val > $num) return $target;
            }
            if ($sign == "<") {
                if ($val < $num) return $target;
            }
        } else {
            return $step;
        }
    }
}