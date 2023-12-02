<?php

$file = file_get_contents("./input");
$lines = explode("\n", $file);
$total = 0;
foreach ($lines as $game) {
    [$game, $cubes] = explode(":", $game);
    $game_num = intval(str_replace("Game ", "", $game));

    $cube = explode(";", $cubes);
    $possible = true;

    foreach ($cube as $c) {
        $colors = explode(",", $c);
        foreach ($colors as $color) {
            [$num, $single] = explode(" ", trim($color));
            if ($single == "green") {
                if (intval($num) > 13) $possible = false;
            } elseif ($single == "blue") {
                if (intval($num) > 14) $possible = false;
            } elseif ($single == "red") {
                if (intval($num) > 12) $possible = false;
            }
        }
    }
    if ($possible) {
        $total += $game_num;
    }
}

print "Solution is " . $total . "\n";