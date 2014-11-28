<?php

namespace Apps\Hangman\Controller\Game;

/**
 * UnitTest for the "CreateController" class.
 *
 * @author Albert Giménez Morales
 * @package Apps\Hangman
 * @uses \Apps\Hangman\Controller\Game\CreateController
 * @uses \PHPUnit_Framework_TestCase
 */
class CreateControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * This is the object we're testing.
     *
     * @var Apps\Hangman\Controller\Game\CreateController
     */
    private $controller;

    public function setUp()
    {
        $this->controller = new CreateController();
    }

    public function tearDown()
    {
        $this->controller = null;
    }

    /**
     * Test execute method.
     *
     * This method should create and save a new game from the repository,
     * and return a JsonResponse with that game.
     */
    public function testExecute()
    {
        $game = array(
            'id'         => 23,
            'word'       => '.......',
            'tries_left' => 11,
            'status'      => 'busy',
        );

        $request         = $this->getMock('\Symfony\Component\HttpFoundation\Request');
        $game_repository = $this->getMockedGameRepository($game);
        $this->controller->setGameRepository($game_repository);

        $response = $this->controller->execute($request);

        $expected_result = json_encode($game);
        $this->assertEquals($expected_result, $response->getContent(), 'The response content is not the expected one.');
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Get a mocked game repository.
     *
     * @param array $game Mocked game data to be returned by Game::jsonSerialize
     * @return Apps\Hangman\Model\Game\GameRepositoryInterface
     */
    private function getMockedGameRepository(array $game)
    {
        $mocked_game_repository = $this->getMock('\Apps\Hangman\Model\Game\GameRepositoryInterface');
        $mocked_game = $this->getMock('\Apps\Hangman\Entity\Game');

        $mocked_game
            ->expects($this->once())
            ->method('jsonSerialize')
            ->will($this->returnValue($game))
        ;
        $mocked_game_repository
            ->expects($this->once())
            ->method('createNew')
            ->will($this->returnValue($mocked_game))
        ;
        $mocked_game_repository
            ->expects($this->once())
            ->method('save')
            ->with($mocked_game)
        ;

        return $mocked_game_repository;
    }
}