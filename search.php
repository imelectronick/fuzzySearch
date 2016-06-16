#!/usr/bin/php
<?php

/**
 * Config
 */
$ngramLength = 2;
$libraryFile = __DIR__.'/lib.csv';

/**
 * Check input parameters
 */
if ($argc < 2 || empty($argv[1])) {
    echo "Wrong argument. Syntax: search.php searching_word\n";
    exit;
}

require_once __DIR__.'/lib.php';

// Import library from file
$lib = file($libraryFile, FILE_IGNORE_NEW_LINES);

// Init engine
$engine = new ngram($ngramLength);

echo "Loading library with ". count($lib). " words.\n";
// Set library
$engine->setLibrary($lib);

echo "Building N-gram index.\n";
// Build index
$engine->buildIndex();

// Get needle word from command-line arguments
$needle = $argv[1];

echo "Searching for {$needle}...\n";
// Perform search
$distances = $engine->search($needle);

echo "Results:\n";
// Show 5 top matched words with theirs Levenshtein distance
$i = 1;
foreach($distances as $word => $distance) {
    echo "{$word} [{$distance}]\n";
    if (++$i > 5) break;
}