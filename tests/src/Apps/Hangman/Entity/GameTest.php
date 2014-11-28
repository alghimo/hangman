<?php

namespace Apps\Hangman\Entity;

/**
 * UnitTest for the "Game" class.
 *
 * @author Albert Giménez Morales
 * @package Apps\Hangman
 * @uses \Apps\Hangman\Entity\Game
 * @uses \PHPUnit_Framework_TestCase
 */
class GameTest extends \PHPUnit_Framework_TestCase
{
    /**
     * This is the object we're testing.
     *
     * @var Apps\Hangman\Entity\Game
     */
    private $game;

    public function setUp()
    {
        $this->game = new Game();
    }

    public function tearDown()
    {
        $this->game = null;
    }

    /**
     * Test that setFullWord also initializes the guessed word.
     */
    public function testSetFullWordInitializesGuessedWord()
    {
        $full_word = "dinosaurus";
        $word      = "..........";

        $this->game->setFullWord($full_word);

        $this->assertEquals($word, $this->game->getWord(), "The guessed word was not initialized.");
    }

    /**
     * Test that setFullWord also initializes the guessed word.
     */
    public function testSetFullWordImplementsFluentInterface()
    {
        $full_word = "dinosaurus";

        $result = $this->game->setFullWord($full_word);

        $this->assertEquals($this->game, $result, "The game instance was not returned by setFullWord.");
    }

    /**
     * Test that setMaxTries also initializes the tries left.
     */
    public function testSetMaxTriesInitializesTriesLeft()
    {
        $max_tries = 12;

        $this->game->setMaxTries($max_tries);

        $this->assertEquals($max_tries, $this->game->getTriesLeft(), "Tries left was not initialized.");
    }

    /**
     * Test that setFullWord also initializes the guessed word.
     */
    public function testSetMaxTriesImplementsFluentInterface()
    {
        $max_tries = 4;

        $result = $this->game->setMaxTries($max_tries);

        $this->assertEquals($this->game, $result, "The game instance was not returned by setMaxTries.");
    }

    /**
     * Test that "canPlay" returns true if we have tries left.
     */
    public function testCanPlayWithTriesLeft()
    {
        $this->game->setMaxTries(1);

        $this->assertTrue($this->game->canPlay(), 'canPlay should return true.');
    }

    /**
     * Test that "canPlay" returns true if we have no tries left.
     */
    public function testCanPlayWithNoTriesLeft()
    {
        $this->game->setMaxTries(0);

        $this->assertFalse($this->game->canPlay(), 'canPlay should return false.');
    }

    /**
     * Test play method when we have no tries left.
     *
     * @expectedException \RuntimeException
     */
    public function testPlayWithNoTriesLeft()
    {
        $this->game->setMaxTries(0);

        $this->game->play("a");
    }

    /**
     * Test play method.
     *
     * @param string $full_word Word to be guessed.
     * @param integer $max_tries Maximum number of tries
     * @param array $tries Data for different calls to "play"
     * @param array $expected_result Expectations over the final game state.
     * @dataProvider playProvider
     */
    public function testPlay($full_word, $max_tries, array $tries, array $expected_result)
    {
        $this->game
            ->setFullWord($full_word)
            ->setMaxTries($max_tries)
        ;

        foreach ($tries as $play) {
            $result = $this->game->play($play['char']);
            $this->assertEquals($play['result'], $result, "Result from play for char '{$play['char']}' was not the expected.");
            $this->assertEquals($play['word'], $this->game->getWord(), "Guessed word was not the expected.");
        }

        $this->assertEquals($expected_result['tries_left'], $this->game->getTriesLeft(), "Tries left don't have the expected value.");
        $this->assertEquals($expected_result['word'], $this->game->getWord(), "Guessed word doesn't have the expected value.");
        $this->assertEquals($expected_result['status'], $this->game->getStatus(), "Incorrect game status.");
    }

    /**
     * Data provider for play method.
     *
     * @return array Data set for testing
     */
    public function playProvider()
    {
        return array(
            'game not finished'    => array(
                'full_word' => 'dinosaurus',
                'max_tries' => 5,
                'tries'     => array(
                    array(
                        'char'   => 'a',
                        'result' => true,
                        'word'   => '.....a....',
                    ),
                    array(
                        'char'   => 'z',
                        'result' => false,
                        'word'   => '.....a....',
                    ),
                    array(
                        'char'   => 'u',
                        'result' => true,
                        'word'   => '.....au.u.',
                    ),
                    array(
                        'char'   => 't',
                        'result' => false,
                        'word'   => '.....au.u.',
                    ),
                    array(
                        'char'   => 's',
                        'result' => true,
                        'word'   => '....sau.us',
                    ),
                ),
                'expected_result' => array(
                    'tries_left' => 3,
                    'word'       => '....sau.us',
                    'status'     => 'busy',
                ),
            ),
            'game failed'    => array(
                'full_word' => 'dinosaurus',
                'max_tries' => 1,
                'tries'     => array(
                    array(
                        'char'   => 'a',
                        'result' => true,
                        'word'   => '.....a....',
                    ),
                    array(
                        'char'   => 'z',
                        'result' => false,
                        'word'   => '.....a....',
                    ),
                ),
                'expected_result' => array(
                    'tries_left' => 0,
                    'word'       => '.....a....',
                    'status'     => 'fail',
                ),
            ),
            'game success'    => array(
                'full_word' => 'hi',
                'max_tries' => 1,
                'tries'     => array(
                    array(
                        'char'   => 'h',
                        'result' => true,
                        'word'   => 'h.',
                    ),
                    array(
                        'char'   => 'i',
                        'result' => true,
                        'word'   => 'hi',
                    ),
                ),
                'expected_result' => array(
                    'tries_left' => 1,
                    'word'       => 'hi',
                    'status'     => 'success',
                ),
            ),
        );
    }

    /**
     * Test jsonSerialize method.
     */
    public function testJsonSerialize()
    {
        $this->game
            ->setId(321)
            ->setFullWord('someword')
            ->setMaxTries(10)
        ;

        $expected_result = array(
            'id'         => 321,
            'word'       => '........',
            'tries_left' => 10,
            'status'     => 'busy',
        );

        $this->assertEquals($expected_result, $this->game->jsonSerialize(), 'The returned array was not the expected.');
    }

    /**
     * Test jsonSerialize method when we've made some plays on the game.
     */
    public function testJsonSerializeWithTries()
    {
        $this->game
            ->setId(432)
            ->setFullWord('someword')
            ->setMaxTries(10)
        ;

        $this->game->play('o');
        $this->game->play('x');
        $this->game->play('s');

        $expected_result = array(
            'id'         => 432,
            'word'       => 'so...o..',
            'tries_left' => 9,
            'status'     => 'busy',
        );

        $this->assertEquals($expected_result, $this->game->jsonSerialize(), 'The returned array was not the expected.');
    }

    /**
     * Test jsonSerialize method when the game is failed.
     */
    public function testJsonSerializeWithGameFailed()
    {
        $this->game
            ->setId(1)
            ->setFullWord('someword')
            ->setMaxTries(1)
        ;

        $this->game->play('m');
        $this->game->play('z');

        $expected_result = array(
            'id'         => 1,
            'word'       => '..m.....',
            'tries_left' => 0,
            'status'     => 'fail',
        );

        $this->assertEquals($expected_result, $this->game->jsonSerialize(), 'The returned array was not the expected.');
    }

    /**
     * Test jsonSerialize method when the game is finished.
     */
    public function testToArrayWithGameSuccess()
    {
        $this->game
            ->setId(2)
            ->setFullWord('cat')
            ->setMaxTries(2)
        ;

        $this->game->play('c');
        $this->game->play('b');
        $this->game->play('a');
        $this->game->play('t');

        $expected_result = array(
            'id'         => 2,
            'word'       => 'cat',
            'tries_left' => 1,
            'status'     => 'success',
        );

        $this->assertEquals($expected_result, $this->game->jsonSerialize(), 'The returned array was not the expected.');
    }
}
