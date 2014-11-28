<?php

namespace Apps\Hangman\Model\Word;

/**
 * Generates (gets) random words from a words list (file).
 *
 * @author Albert Giménez Morales
 * @package Apps\Hangman
 */
class Generator
{
    /**
     * Path to the words list resource.
     *
     * @var string
     */
    private $words_list_path;

    /**
     * Total words in file.
     *
     * @var integer
     * @tricky This is just a workaround. In a real app, I'd move this to a DB or similar, not to a file.
     */
    private $total_words;

    /**
     * Class constructor.
     *
     * Injects the path to the words file, and the total number of words in that file.
     */
    public function __construct($words_list_path, $total_words)
    {
        $this->words_list_path = $words_list_path;
        $this->total_words     = $total_words;
    }

    /**
     * Get a new word from the words file.
     *
     * @return string
     * @throws RuntimeException If no word can be loaded.
     */
    public function getWord()
    {
        $word_line      = rand(1, $this->total_words);
        $words_resource = $this->getWordsResource();
        $current_line   = 0;

        /**
         * This is not as clean as loading the whole file in memory
         * and just getting the line we want, but it does save
         * a HUGE amount of memory if we used big files,
         * because we'll never have more than one line of the
         * file in memory on every iteration.
         */
        do {
            $word = fgets($words_resource);
            $current_line++;
        } while(false !== $word && $current_line !== $word_line);

        fclose($words_resource);

        if (empty($word)) {
            throw new \RuntimeException("Could not load a word.");
        }

        return rtrim($word, "\n");
    }

    /**
     * Open the words list file.
     *
     * @return resource
     * @throws RuntimeException If the words file can not be opened.
     */
    private function getWordsResource()
    {
        $resource = fopen($this->words_list_path, "r");
        if (false === $resource) {
            throw new \RuntimeException("Could not open words file for reading: {$this->words_list_path}");
        }

        return $resource;
    }
}
