<?php
$file = file_get_contents("./input");
$hands = explode("\n", $file);
$ranks = array();
$bids = array();
foreach ($hands as $hand_and_bid) {
    [$hand, $bid] = explode(" ", $hand_and_bid);
    $ranks[] = $hand;
    $bids[$hand] = $bid;
}
$start = microtime(true);
usort($ranks, 'hand_sort');
$total = 0;
foreach ($ranks as $key => $value) {
    $total += ($key + 1) * $bids[$value];
}
print "Solution is $total\n";
print "Took " . number_format((microtime(true) - $start) * 1000, 2) . " ms\n";

function hand_sort($a, $b) {
    $a_cards = str_split($a);
    $b_cards = str_split($b);

    $a_counts = array();
    $b_counts = array();
    foreach ($a_cards as $a_card) {
        if (key_exists($a_card, $a_counts)) {
            $a_counts[$a_card]++;
        } else {
            $a_counts[$a_card] = 1;
        }
    }
    foreach ($b_cards as $b_card) {
        if (key_exists($b_card, $b_counts)) {
            $b_counts[$b_card]++;
        } else {
            $b_counts[$b_card] = 1;
        }
    }
    if (array_key_exists("J", $a_counts)) {
        $max_num = 0;
        $max_card = "";
        foreach ($a_counts as $key => $value) {
            if ($key != "J") {
                if ($value > $max_num) {
                    $max_num = $value;
                    $max_card = $key;
                }
            }
        }
        if ($max_card == "") {
            $a_counts["A"] = 5;
        } else {
            $a_counts[$max_card] += $a_counts["J"];
        }
        unset($a_counts["J"]);
    }
    if (array_key_exists("J", $b_counts)) {
        $max_num = 0;
        $max_card = "";
        foreach ($b_counts as $key => $value) {
            if ($key != "J") {
                if ($value > $max_num) {
                    $max_num = $value;
                    $max_card = $key;
                }
            }
        }
        if ($max_card == "") {
            $b_counts["A"] = 5;
        } else {
            $b_counts[$max_card] += $b_counts["J"];
        }
        unset($b_counts["J"]);
    }
    $count_a = count($a_counts);
    $count_b = count($b_counts);

    if ($count_a != $count_b) {
        return $count_b - $count_a;
    }
    if ($count_a == 3) {
        if (in_array(3, $a_counts) && !in_array(3, $b_counts)) {
            return 1;
        }
        if (in_array(3, $b_counts) && !in_array(3, $a_counts)) {
            return -1;
        }
    }
    if ($count_a == 2) {
        if (in_array(4, $a_counts) && !in_array(4, $b_counts)) {
            return 1;
        }
        if (in_array(4, $b_counts) && !in_array(4, $a_counts)) {
            return -1;
        }
    }
    for($i = 0; $i < count($a_cards); $i++) {
        $ac = $a_cards[$i];
        $bc = $b_cards[$i];
        if ($ac == "T") $ac = 11;
        else if ($ac == "J") $ac = 0;
        else if ($ac == "Q") $ac = 12;
        else if ($ac == "K") $ac = 13;
        else if ($ac == "A") $ac = 14;
        else $ac = intval($ac);
        if ($bc == "T") $bc = 11;
        else if ($bc == "J") $bc = 0;
        else if ($bc == "Q") $bc = 12;
        else if ($bc == "K") $bc = 13;
        else if ($bc == "A") $bc = 14;
        else $bc = intval($bc);

        if ($ac > $bc) {
            return 1;
        }
        if ($bc > $ac) {
            return -1;
        }
    }
    return 0;
}