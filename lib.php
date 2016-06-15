<?php

/**
 * Class ngram
 *
 * Represents ngram based search method
 */
class ngram
{
    /** @var array index */
    protected $index = [];

    /** @var array library */
    protected $library;

    /** @var int "N"-gram */
    protected $n;

    /**
     * @param int $n -gram (length of N-gram)
     */
    public function __construct($n = 2)
    {
        $this->n = $n;
    }

    /**
     * Build N-gram index based on library
     */
    public function build()
    {
        foreach ($this->library as $key => $word) {
            $ngrams = $this->buildNgrams($word, $key);
            $this->index = array_merge_recursive($this->index, $ngrams);
        }
    }

    /**
     * Search used combined N-gram/Levenshtein algorithm
     *
     * @param $word
     * @return array
     */
    public function search($word)
    {
        // Search using ngram index
        $ngrams = $this->buildNgrams($word, -1);
        $result = [];
        foreach ($ngrams as $ngram => $null) {
            foreach($this->index[$ngram] as $key) {
                $result[$key]++;
            }
        }
        // Short variant will end here
        // arsort($result);
        // return $result;

        // Add more accuracy using Levenshtein algorithm
        $distances = [];
        foreach($result as $key => $null) {
            $distances[$this->library[$key]] = levenshtein($word, $this->library[$key]);
            // Also we can limit this re-search only on most-accurate words based on n-gram search
        }

        asort($distances);
        return $distances;
    }

    /**
     * @param array $library
     * @return $this
     */
    public function setLibrary($library)
    {
        $this->library = $library;
        return $this;
    }

    /**
     * Parse words to N-grams
     *
     * @param $word
     * @param $key
     * @return array
     */
    private function buildNgrams($word, $key)
    {
        $ngrams = [];
        for($i = 0, $length = mb_strlen($word, 'utf-8')-$this->n; $i <= $length; $i++) {
            $ngram = mb_substr($word, $i, $this->n, 'utf-8');
            $ngrams[$ngram][] = $key;
        }
        return $ngrams;
    }
}