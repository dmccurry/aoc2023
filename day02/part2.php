<?php

$file = file_get_contents("./input");
$lines = explode("\n", $file);
$total = 0;
foreach ($lines as $game) {
    [$game, $cubes] = explode(":", $game);
    $game_num = intval(str_replace("Game ", "", $game));
    $min_red = 0;
    $min_green = 0;
    $min_blue = 0;

    $cube = explode(";", $cubes);
    $possible = true;

    foreach ($cube as $c) {
        $colors = explode(",", $c);
        foreach ($colors as $color) {
            [$num, $single] = explode(" ", trim($color));
            if ($single == "green") {
                if (intval($num) > $min_green) $min_green = intval($num);
            } elseif ($single == "blue") {
                if (intval($num) > $min_blue) $min_blue = intval($num);
            } elseif ($single == "red") {
                if (intval($num) > $min_red) $min_red = intval($num);
            }
        }
    }
    $total += ($min_red * $min_blue * $min_green);
}

print "Solution is " . $total . "\n";