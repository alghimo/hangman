<?php

namespace Apps\Hangman\Controller\Game;

/**
 * UnitTest for the "GuessController" class.
 *
 * @author Albert Giménez Morales
 * @package Apps\Hangman
 * @uses \Apps\Hangman\Controller\Game\GuessController
 * @uses \PHPUnit_Framework_TestCase
 */
class GuessControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * This is the object we're testing.
     *
     * @var Apps\Hangman\Controller\Game\GuessController
     */
    private $controller;

    public function setUp()
    {
        $this->controller = new GuessController();
    }

    public function tearDown()
    {
        $this->controller = null;
    }

    /**
     * Test execute method.
     *
     * This method should make a guess over an existing game
     * and return a JsonResponse with that game.
     */
    public function testExecute()
    {
        $game = array(
            'id'         => 23,
            'word'       => '...o.sa.r.s',
            'tries_left' => 11,
            'status'      => 'busy',
        );
        $char_to_play    = "a";
        $request         = $this->getMockedRequest(23, $char_to_play);
        $game_repository = $this->getMockedGameRepository($game, $char_to_play);
        $this->controller->setGameRepository($game_repository);

        $response = $this->controller->execute($request);

        $expected_result = json_encode($game);
        $this->assertEquals($expected_result, $response->getContent(), 'The response content is not the expected one.');
        $this->assertEquals(200, $response->getStatusCode());
    }

    private function getMockedRequest($id_game, $char)
    {
        $mocked_request             = $this->getMock('\Symfony\Component\HttpFoundation\Request');
        $mocked_attributes          = $this->getMock('\Symfony\Component\HttpFoundation\ParameterBag');
        $mocked_request->attributes = $mocked_attributes;
        $mocked_route = array(
            'params'    => array('id_game' => $id_game,),
        );
        $mocked_attributes
            ->expects($this->once())
            ->method('get')
            ->with('_matched_route')
            ->will($this->returnValue($mocked_route))
        ;
        $mocked_request
            ->expects($this->once())
            ->method('get')
            ->with('char')
            ->will($this->returnValue($char))
        ;

        return $mocked_request;
    }

    /**
     * Get a mocked game repository.
     *
     * @param array $game Mocked game data to be returned by Game::jsonSerialize
     * @param string $char Expected char for the call to Game::play.
     * @return Apps\Hangman\Model\Game\GameRepositoryInterface
     */
    private function getMockedGameRepository(array $game, $char)
    {
        $mocked_game_repository = $this->getMock('\Apps\Hangman\Model\Game\GameRepositoryInterface');
        $mocked_game = $this->getMock('\Apps\Hangman\Entity\Game');

        $mocked_game
            ->expects($this->once())
            ->method('jsonSerialize')
            ->will($this->returnValue($game))
        ;
        $mocked_game
            ->expects($this->once())
            ->method('play')
            ->with($char)
        ;
        $mocked_game_repository
            ->expects($this->once())
            ->method('findById')
            ->with($game['id'])
            ->will($this->returnValue($mocked_game))
        ;
        $mocked_game_repository
            ->expects($this->once())
            ->method('save')
            ->with($mocked_game)
            ->will($this->returnValue($mocked_game))
        ;

        return $mocked_game_repository;
    }
}