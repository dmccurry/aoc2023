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
    if (count($a_counts) != count($b_counts)) {
        return count($b_counts) - count($a_counts);
    }
    if (count($a_counts) == 3) {
        if (in_array(3, $a_counts) && !in_array(3, $b_counts)) {
            return 1;
        }
        if (in_array(3, $b_counts) && !in_array(3, $a_counts)) {
            return -1;
        }
    }
    if (count($a_counts) == 2) {
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
        if ($ac == "T") $ac = 10;
        else if ($ac == "J") $ac = 11;
        else if ($ac == "Q") $ac = 12;
        else if ($ac == "K") $ac = 13;
        else if ($ac == "A") $ac = 14;
        else $ac = intval($ac);
        if ($bc == "T") $bc = 10;
        else if ($bc == "J") $bc = 11;
        else if ($bc == "Q") $bc = 12;
        else if ($bc == "K") $bc = 13;
        else if ($bc == "A") $bc = 14;
        else $bc = intval($bc);
        if ($ac > $bc) {
            return 100;
        }
        if ($bc > $ac) {
            return -100;
        }
    }
    return 0;
}