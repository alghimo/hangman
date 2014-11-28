<?php

namespace Apps\Hangman\Controller\Game;

/**
 * UnitTest for the "ReadController" class.
 *
 * @author Albert Giménez Morales
 * @package Apps\Hangman
 * @uses \Apps\Hangman\Controller\Game\ReadController
 * @uses \PHPUnit_Framework_TestCase
 */
class ReadControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * This is the object we're testing.
     *
     * @var Apps\Hangman\Controller\Game\ReadController
     */
    private $controller;

    public function setUp()
    {
        $this->controller = new ReadController();
    }

    public function tearDown()
    {
        $this->controller = null;
    }

    /**
     * Test execute method.
     *
     * This method will get the game id from the request, and load it using the game repository.
     */
    public function testExecute()
    {
        $game = array(
            'id'         => 23,
            'word'       => '...o.sa.r.s',
            'tries_left' => 11,
            'status'      => 'busy',
        );

        $request = $this->getMockedRequest(23);
        $game_repository = $this->getMockedGameRepository($game);
        $this->controller->setGameRepository($game_repository);

        $response = $this->controller->execute($request);

        $expected_result = json_encode($game);
        $this->assertEquals($expected_result, $response->getContent(), 'The response content is not the expected one.');
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Get a mocked request.
     *
     * @param integer $id_game Game id to set in the request attributes.
     * @return Symfony\Component\HttpFoundation\Request
     */
    private function getMockedRequest($id_game)
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

        return $mocked_request;
    }

    /**
     * Get a mocked game repository.
     *
     * @param array $game Mocked game data to be returned by Game::jsonSerialize
     * @return Apps\Hangman\Model\Game\GameRepositoryInterface
     */
    private function getMockedGameRepository($game)
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
            ->method('findById')
            ->with($game['id'])
            ->will($this->returnValue($mocked_game))
        ;

        return $mocked_game_repository;
    }
}