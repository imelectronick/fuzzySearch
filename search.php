#!/usr/bin/php
<?php

/**
 * Config block
 */
$ngramLength = 3;
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

// Init enging
$engine = new ngram($ngramLength);

echo "Loading library with ". count($lib). " words.\n";
// Set library
$engine->setLibrary($lib);

echo "Building N-gram index.\n";
// Build index
$engine->build();

// Get needle word from command-line arguments
$needle = $argv[1];

echo "Searching for {$needle}...\n";
// Perform search
$distances = $engine->search($needle);

echo "Results:\n";
// Show matched words with theirs Levenshtein distance
foreach($distances as $word => $distance) {
    echo "{$word} [{$distance}]\n";
}