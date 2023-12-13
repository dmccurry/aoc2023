<?php
$file = file_get_contents("./input");
$springs = explode("\n", $file);

$total = 0;
$memo = array();
$start = microtime(true);
foreach ($springs as $spring) {
    [$map, $regions] = explode(" ", $spring);

    $map = "$map?$map?$map?$map?$map";
    $regions = "$regions,$regions,$regions,$regions,$regions";
    $regions = array_map(fn($v) => intval($v), explode(",", $regions));
    $map = str_split($map);
    $t = count_springs($map, $regions);
    $total += $t;
}

print "Solution is $total\n";
print "Took " . number_format((microtime(true) - $start) * 1000, 2) . " ms\n";

function count_springs($map, $regions) {
    global $memo;
    $key = implode(",", $map) . "|" . implode(",", $regions);
    if (array_key_exists($key, $memo)) {
        return $memo[$key];
    }
    if (count($map) == 0) {
        // if there are no regions remaining, it's valid.
        return count($regions) == 0 ? 1 : 0;
    }
    $count = 0;
    if ($map[0] == ".") {
        $new_map = $map;
        $new_regions = $regions;
        array_splice($new_map, 0, 1);
        $count = count_springs($new_map, $new_regions);
    } elseif ($map[0] == "?") {
        $new_map = $map;
        $new_map2 = $map;
        $new_regions = $regions;
        $new_regions2 = $regions;
        $new_map[0] = ".";
        $new_map2[0] = "#";
        $count = count_springs($new_map, $new_regions) 
            + count_springs($new_map2, $new_regions2);
    } else { // #
        if (count($regions) == 0) {
            $count = 0;
        } else {
            $region = $regions[0];
            $current_length = 1;
            for ($i=1; $i<count($map); $i++) {
                if ($map[$i] == ".") break;
                $current_length++;
            }
            if ($region <= count($map) && $current_length >= $region) {
                $new_regions = $regions;
                array_shift($new_regions);
                if ($region == count($map)) {
                    $count = count($new_regions) == 0 ? 1 : 0;
                } else if ($map[$region] == ".") {
                    $new_map = $map;
                    array_splice($new_map, 0, $region + 1);
                    $count = count_springs($new_map, $new_regions);
                } else if ($map[$region] == "?") {
                    $new_map = $map;
                    array_splice($new_map, 0, $region);
                    $new_map[0] = ".";
                    $count = count_springs($new_map, $new_regions);
                } else {
                    $count = 0;
                }
            } else {
                $count = 0;
            }
        }
    }
    $memo[$key] = $count;
    return $count;
}