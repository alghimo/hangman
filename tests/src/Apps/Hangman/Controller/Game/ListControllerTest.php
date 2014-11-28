<?php

namespace Apps\Hangman\Controller\Game;

/**
 * UnitTest for the "ListController" class.
 *
 * @author Albert Giménez Morales
 * @package Apps\Hangman
 * @uses \Apps\Hangman\Controller\Game\ListController
 * @uses \PHPUnit_Framework_TestCase
 */
class ListControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * This is the object we're testing.
     *
     * @var Apps\Hangman\Controller\Game\ListController
     */
    private $controller;

    public function setUp()
    {
        $this->controller = new ListController();
    }

    public function tearDown()
    {
        $this->controller = null;
    }

    /**
     * Test execute method.
     *
     * This method should get a list of all games from the repository, and return them in a JsonResponse.
     */
    public function testExecute()
    {
        $games = array(
            array(
                'id'         => 23,
                'word'       => '...o.sa.r.s',
                'tries_left' => 11,
                'status'     => 'busy',
            ),
            array(
                'id'         => 2,
                'word'       => 'house',
                'tries_left' => 5,
                'status'     => 'success',
            ),
            array(
                'id'         => 3,
                'word'       => 'c.ild..n',
                'tries_left' => 0,
                'status'     => 'fail',
            ),
        );

        $mocked_request  = $this->getMock('\Symfony\Component\HttpFoundation\Request');
        $game_repository = $this->getMockedGameRepository($games);
        $this->controller->setGameRepository($game_repository);

        $response = $this->controller->execute($mocked_request);

        $expected_result = json_encode($games);
        $this->assertEquals($expected_result, $response->getContent(), 'The response content is not the expected one.');
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Get a mocked game repository.
     *
     * @param array $games List of mocked games.
     * @return Apps\Hangman\Model\Game\GameRepositoryInterface
     */
    private function getMockedGameRepository($games)
    {
        $mocked_game_repository = $this->getMock('\Apps\Hangman\Model\Game\GameRepositoryInterface');
        $mocked_games           = array();

        foreach ($games as $game) {
            $mocked_game = $this->getMock('\Apps\Hangman\Entity\Game');
            $mocked_game
                ->expects($this->once())
                ->method('jsonSerialize')
                ->will($this->returnValue($game))
            ;
            $mocked_games[] = $mocked_game;
        }

        $mocked_game_repository
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue($mocked_games))
        ;

        return $mocked_game_repository;
    }
}