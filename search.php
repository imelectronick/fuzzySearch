#!/usr/bin/php
<?php

$lib = file(__DIR__.'/lib.csv', FILE_IGNORE_NEW_LINES);

if ($argc < 2 || empty($argv[1])) {
    echo "Wrong argument. Syntax: search.php searching_word\n";
    exit;
}
// Get needle word from command-line arguments
$needle = $argv[1];

echo "Looking for {$needle} in ".count($lib), " library.";
$distances = [];

// Calculate distances
foreach ($lib as $key => $word) {
    // ignore the fact function not fully support multibyte strings
    $distances[$key] = levenshtein($needle, $word);
}

asort($distances);

echo "Results:\n";
// Show 3 closest words with it's distances
$i = 0;
foreach($distances as $key => $distance) {
    echo "{$lib[$key]} [{$distance}]\n";
    ++$i >= 3 && exit;
}