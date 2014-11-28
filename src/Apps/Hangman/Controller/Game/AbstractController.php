<?php

namespace Apps\Hangman\Controller\Game;

use Symfony\Component\HttpFoundation\Request;
use Framework\Application\ControllerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Apps\Hangman\Model\Game\GameRepositoryInterface;

/**
 * Parent class for game controllers.
 *
 * Implements methods reused by all game controllers,
 * by now, just handles the game repository accessor methods.
 *
 * @author Albert Giménez Morales
 * @package Apps\Hangman
 * @uses Apps\Hangman\Controller\Game\GameControllerInterface
 */
abstract class AbstractController implements GameControllerInterface
{
    /**
     * Game repository used for storage.
     *
     * @var Apps\Hangman\Model\Game\GameRepositoryInterface
     */
    private $game_repository;

    /**
     * Sets the game repository.
     *
     * @param Apps\Hangman\Model\Game\GameRepositoryInterface $game_repository
     * @return Apps\Hangman\Controller\Game\AbstractController Self instance
     */
    public function setGameRepository(GameRepositoryInterface $game_repository)
    {
        $this->game_repository = $game_repository;

        return $this;
    }

    /**
     * Gets the game repository.
     *
     * @return Apps\Hangman\Model\Game\GameRepositoryInterface
     */
    public function getGameRepository()
    {
        return $this->game_repository;
    }
}