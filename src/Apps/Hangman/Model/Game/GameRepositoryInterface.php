<?php

namespace Apps\Hangman\Model\Game;

use Symfony\Component\HttpFoundation\Request;
use Framework\Application\ControllerInterface;
use Apps\Hangman\Model\Game\GameRepositoryInterface;
use Apps\Hangman\Entity\Game;

/**
 * Declares common methods to be implemented by game repositories.
 *
 * @author Albert Giménez Morales
 * @package Apps\Hangman
 */
interface GameRepositoryInterface
{
    /**
     * Creates a new game, already initialized.
     *
     * @return Apps\Hangman\Entity\Game
     */
    public function createNew();

    /**
     * Find a game by id.
     *
     * @param integer $id
     * @return Apps\Hangman\Entity\Game
     * @throws RuntimeException If there is an error loading the game.
     * @throws RuntimeException If no game can be found with the given ID.
     */
    public function findById($id);

    /**
     * Get all games.
     *
     * @return array List of game instances.
     * @throws RuntimeException If there is an error loading the games.
     */
    public function findAll();

    /**
     * Save a game.
     *
     * This method will either insert or update the game, depending on
     * whether the game already has an id.
     * If it's inserted, the generated id will be set in the game.
     *
     * @param Apps\Hangman\Entity\Game $game
     * @return Apps\Hangman\Entity\Game The saved game
     * @throws RuntimeException If there is an error saving the game.
     */
    public function save(Game $game);
}