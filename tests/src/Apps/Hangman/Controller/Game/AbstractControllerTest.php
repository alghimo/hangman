<?php

namespace Apps\Hangman\Controller\Game;

/**
 * UnitTest for the "AbstractController" class.
 *
 * @author Albert Giménez Morales
 * @package Apps\Hangman
 * @uses \Apps\Hangman\Controller\Game\AbstractController
 * @uses \PHPUnit_Framework_TestCase
 */
class AbstractControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * This is the object we're testing.
     *
     * @var Apps\Hangman\Controller\Game\AbstractController
     */
    private $controller;

    public function setUp()
    {
        // We have to mock the original class, since it's abstract,
        $this->controller = $this->getMock('\Apps\Hangman\Controller\Game\AbstractController', array('execute'));
    }

    public function tearDown()
    {
        $this->controller = null;
    }

    /**
     * Test game repository accessor methods.
     */
    public function testGameRepositoryAccessors()
    {
        $game_repository = $this->getMock('\Apps\Hangman\Model\Game\GameRepositoryInterface');

        $this->controller->setGameRepository($game_repository);

        $this->assertSame($game_repository, $this->controller->getGameRepository(), 'Game repository was not properly set.');
    }

    /**
     * Test that setGameRepository implements fluent interface.
     */
    public function testSetGameRepositoryImplementsFluentInterface()
    {
        $game_repository = $this->getMock('\Apps\Hangman\Model\Game\GameRepositoryInterface');

        $result = $this->controller->setGameRepository($game_repository);

        $this->assertSame($this->controller, $result, 'setGameRepository does not implement fluent interface.');
    }
}