<?php
$file = file_get_contents("./input");

$nodes = array();

foreach (explode("\n", $file) as $line) {
    [$in, $out] = explode(" -> ", $line);
    $node = get_node($in, $out);
    $nodes[$node->name] = $node;
}
$start = microtime(true);
foreach ($nodes as $node) {
    foreach ($node->outputs as $output) {
        if ($output != "rx" && $nodes[$output]->type == "&") {
            $nodes[$output]->inputs[$node->name] = 0;
        }
    }
}

$highs = 0;
$lows = 0;
$press = 0;

// you gotta get these from your input.
// no way I'm doing this dynamically.
$cycles = ["vd", "ns", "bh", "dl"];
$iterations = [];

while (true) {
    $pulses = array();
    $pulses[] = [0, "broadcaster", ""];
    $press++;

    if ($press % 10000 == 0) print "Did $press iterations\n";

    while (count($pulses)) {
        $next_pulse = array_shift($pulses);
        $type = $next_pulse[0];
        $target = $next_pulse[1];
        $source = $next_pulse[2];

        if ($target == "rx" && $type == 0) {
            break;
        }
    
        if ($type == 0) $lows++;
        else $highs++;
    
        if ($target == "rx") continue;
        $target_node = $nodes[$target];
        if ($target_node == null) continue;
        if ($target_node->type == "broadcaster") {
            foreach ($target_node->outputs as $output) {
                $pulses[] = [0, $output, $target];
            }
        } else if ($target_node->type == "%" && $type == 0) {
            if ($target_node->state == 0) {
                $target_node->state = 1;
            } else {
                $target_node->state = 0;
            }
            foreach ($target_node->outputs as $output) {
                $pulses[] = [$target_node->state, $output, $target];
            }
        } else if ($target_node->type == "&") {
            $target_node->inputs[$source] = $type;
            $out_type = 0;
            foreach ($target_node->inputs as $key => $value) {
                if ($value == 0) $out_type = 1;
            }

            if (in_array($target, $cycles) && $out_type == 1) {
                $iterations[$target] = $press;
            }

            foreach ($target_node->outputs as $output) {
                $pulses[] = [$out_type, $output, $target];
            }
        }
    }

    if (count($iterations) == 4) {
        break;
    }
}


$nums = array();
foreach ($iterations as $key => $value) {
    $nums[] = $value;
}



print "Solution is " . lcm($nums) . "\n";
print "Took " . number_format((microtime(true) - $start) * 1000, 2) . " ms\n";

function lcm($nums) {
    $ret = $nums[0];

    for ($i=0; $i<count($nums); $i++) {
        $ret = ((($nums[$i] * $ret) / gcd($nums[$i], $ret)));
    }
    return $ret;
}

function gcd($a, $b) {
    if ($b == 0) {
        return $a;
    }
    return gcd($b, $a % $b);
}

function get_node($in, $out) {
    $node = new stdClass();
    if ($in != "broadcaster") {
        $type = substr($in, 0, 1);
        $name = substr($in, 1);
    } else {
        $type = $in;
        $name = $in;
    }

    $node->type = $type;
    $node->name = $name;
    $node->outputs = explode(", ", $out);
    $node->state = 0;
    $node->inputs = array();

    return $node;
}