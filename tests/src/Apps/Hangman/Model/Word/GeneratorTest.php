<?php

namespace Apps\Hangman\Model\Word;

/**
 * UnitTest for the "Generator" class.
 *
 * @author Albert Giménez Morales
 * @package Apps\Hangman
 * @uses \Apps\Hangman\Model\Word\Generator
 * @uses \PHPUnit_Framework_TestCase
 */
class GeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * This is the object we're testing.
     *
     * @var Apps\Hangman\Model\Word\Generator
     */
    private $generator;

    /**
     * Path to the mocked words file.
     *
     * @var string
     */
    private $words_file;

    public function setUp()
    {
        /**
         * @tricky:
         * This should be properly mocked either via some kind of file system helper,
         * or some libraries such as vfsStream.
         * This UT is actually depending on the file system now...
         *
         * @see https://github.com/mikey179/vfsStream
         */
        list($this->words_file, $num_words) = $this->createMockedWordsFile();
        $this->generator = new Generator($this->words_file, $num_words);
    }

    public function tearDown()
    {
        $this->generator = null;
        unlink($this->words_file);
    }

    /**
     * Test getWordMethod.
     *
     * This method should return a random word from the list.
     */
    public function testGetWord()
    {
        $word = $this->generator->getWord();

        $this->assertContains($word, $this->getMockedWords(), "Word '{$word}' is not un the list of words.");
    }

    /**
     * Creates a mocked file in the temporary sys temp directory.
     *
     * This is dirty and should be fixed.
     * return array Containing the path to the words list file and the total number of words.
     */
    private function createMockedWordsFile()
    {
        $words_file     = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'words_list.txt';
        $words_resource = fopen($words_file, "w");
        $words          = $this->getMockedWords();

        foreach ($words as $word) {
            fwrite($words_resource, $word . "\n");
        }

        fclose($words_resource);

        return array($words_file, count($words));
    }

    /**
     * Get a list of mocked words.
     *
     * return array List of words.
     */
    private function getMockedWords()
    {
        return array(
            'foo',
            'bar',
            'baz',
        );
    }
}