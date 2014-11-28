<?php

namespace Apps\Hangman\Controller\Game;

use Symfony\Component\HttpFoundation\Request;
use Framework\Application\ControllerInterface;
use Apps\Hangman\Model\Game\GameRepositoryInterface;

/**
 * Declares common methods to be implemented by game controllers.
 *
 * By now, all what these controllers share is the usage of a game repository.
 *
 * @author Albert Giménez Morales
 * @package Apps\Hangman
 * @uses Framework\Application\ControllerInterface
 */
interface GameControllerInterface extends ControllerInterface
{
    /**
     * Sets the game repository.
     *
     * @param Apps\Hangman\Model\Game\GameRepositoryInterface $game_repository
     * @return Apps\Hangman\Controller\Game\GameControllerInterface Self instance
     */
    public function setGameRepository(GameRepositoryInterface $game_repository);

    /**
     * Gets the game repository.
     *
     * @return Apps\Hangman\Model\Game\GameRepositoryInterface
     */
    public function getGameRepository();
}