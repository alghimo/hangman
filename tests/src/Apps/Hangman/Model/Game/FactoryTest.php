<?php

namespace Apps\Hangman\Model\Game;

/**
 * UnitTest for the "Factory" class.
 *
 * @author Albert Giménez Morales
 * @package Apps\Hangman
 * @uses \Apps\Hangman\Model\Game\Factory
 * @uses \PHPUnit_Framework_TestCase
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test creating a new initialized game.
     *
     * This method will use the word generator to get a new word.
     */
    public function testCreateNewGame()
    {
        $max_tries = 10;
        $word      = "foo";
        $generator = $this
                        ->getMockBuilder('\Apps\Hangman\Model\Word\Generator')
                        ->disableOriginalConstructor()
                        ->getMock()
        ;
        $generator
            ->expects($this->once())
            ->method('getWord')
            ->will($this->returnValue($word))
        ;

        $factory = new Factory($generator, $max_tries);

        $game = $factory->createNewGame();

        $this->assertInstanceOf('\Apps\Hangman\Entity\Game', $game, "createNewGame did not return a Game instance.");
        $this->assertEquals($word, $game->getFullWord(), 'The expected word was not set in the game.');
        $this->assertEquals($max_tries, $game->getMaxTries(), 'The expected max tries were not set in the game.');
    }

    /**
     * Test creating a new empty game.
     */
    public function testCreateEmptyGame()
    {
        $max_tries = 10;
        $generator = $this
                        ->getMockBuilder('\Apps\Hangman\Model\Word\Generator')
                        ->disableOriginalConstructor()
                        ->getMock()
        ;

        $factory = new Factory($generator, $max_tries);

        $game = $factory->createEmptyGame();

        $this->assertInstanceOf('\Apps\Hangman\Entity\Game', $game, "createEmptyGame did not return a Game instance.");
    }

    /**
     * Test word generator accessor methods.
     */
    public function testWordGeneratorAccessors()
    {
        $max_tries = 10;
        $generator = $this
                        ->getMockBuilder('\Apps\Hangman\Model\Word\Generator')
                        ->disableOriginalConstructor()
                        ->getMock()
        ;

        $factory = new Factory($generator, $max_tries);

        $factory->setWordGenerator($generator);

        $this->assertSame($generator, $factory->getWordGenerator(), 'The Generator instance was not properly set.');
    }

    /**
     * Test that setWordGenerator implements fluent interface.
     */
    public function testSetWordGeneratorImplementsFluentInterface()
    {
        $max_tries = 10;
        $generator = $this
                        ->getMockBuilder('\Apps\Hangman\Model\Word\Generator')
                        ->disableOriginalConstructor()
                        ->getMock()
        ;

        $factory = new Factory($generator, $max_tries);

        $result = $factory->setWordGenerator($generator);

        $this->assertSame($factory, $result, 'setWordGenerator does not implement fluent interface.');
    }

    /**
     * Test max_tries accessor methods.
     */
    public function testMaxTriesAccessors()
    {
        $max_tries = 10;
        $generator = $this
                        ->getMockBuilder('\Apps\Hangman\Model\Word\Generator')
                        ->disableOriginalConstructor()
                        ->getMock()
        ;

        $factory = new Factory($generator, $max_tries);

        $factory->setMaxTries($max_tries);

        $this->assertEquals($max_tries, $factory->getMaxTries(), 'max_tries was not properly set.');
    }

    /**
     * Test that setMaxTries implements fluent interface.
     */
    public function testSetMaxTriesImplementsFluentInterface()
    {
        $max_tries = 10;
        $generator = $this
                        ->getMockBuilder('\Apps\Hangman\Model\Word\Generator')
                        ->disableOriginalConstructor()
                        ->getMock()
        ;

        $factory = new Factory($generator, $max_tries);

        $result = $factory->setMaxTries($max_tries);

        $this->assertSame($factory, $result, 'setMaxTries does not implement fluent interface.');
    }
}
