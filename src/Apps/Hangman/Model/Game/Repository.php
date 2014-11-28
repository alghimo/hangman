<?php

namespace Apps\Hangman\Model\Game;

use Apps\Hangman\Entity\Game;

/**
 * Declares common methods to be implemented by game repositories.
 *
 * @author Albert Giménez Morales
 * @package Apps\Hangman
 * @uses Apps\Hangman\Model\Game\GameRepositoryInterface
 */
class Repository implements GameRepositoryInterface
{
    /**
     * Db instance.
     *
     * @var \PDO
     */
    private $db = null;

    /**
     * Game factory instance.
     *
     * @var Apps\Hangman\Model\Game\Factory
     */
    private $game_factory;

    /**
     * Class constructor.
     *
     * Injects the db and game factory instances.
     */
    public function __construct(\PDO $db, Factory $game_factory)
    {
        $this->setDb($db);
        $this->setFactory($game_factory);
    }

    /**
     * Get the db.
     *
     * @return \PDO
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * Set the db.
     *
     * @param \PDO $db
     * @return Apps\Hangman\Model\Game\Repository Self instance
     */
    public function setDb(\PDO $db)
    {
        $this->db = $db;

        return $this;
    }

    /**
     * Get the game factory.
     *
     * @return Apps\Hangman\Model\Game\Factory
     */
    public function getFactory()
    {
        return $this->game_factory;
    }

    /**
     * Set the game factory.
     *
     * @param Apps\Hangman\Model\Game\Factory $game_factory
     * @return Apps\Hangman\Model\Game\Repository Self instance
     */
    public function setFactory(Factory $game_factory)
    {
        $this->game_factory = $game_factory;

        return $this;
    }

    /**
     * Creates a new game, already initialized.
     *
     * @return Apps\Hangman\Entity\Game
     */
    public function createNew()
    {
        return $this->getFactory()->createNewGame();
    }

    /**
     * Find a game by id.
     *
     * @param integer $id
     * @return Apps\Hangman\Entity\Game
     * @throws RuntimeException If there is an error loading the game.
     * @throws RuntimeException If no game can be found with the given ID.
     */
    public function findById($id_game)
    {
        $query = <<<QUERY
SELECT
    id,
    full_word,
    word,
    max_tries,
    tries_left
FROM
    games
WHERE
    id = :id_game
QUERY;

        $statement = $this->getDb()->prepare($query);
        $statement->bindValue(':id_game', $id_game, \PDO::PARAM_INT);
        $result = $statement->execute();

        if (false === $result) {
            throw new \RuntimeException("An error happened loading the game with id #{$id_game}");
        }

        $raw_game = $statement->fetch(\PDO::FETCH_ASSOC);

        if (false === $raw_game) {
            throw new \RuntimeException("Could not load game with id #{$id_game}");
        }

        $game = $this->getFactory()->createEmptyGame();

        $game
            ->setId($raw_game['id'])
            ->setFullWord($raw_game['full_word'])
            ->setWord($raw_game['word'])
            ->setMaxTries($raw_game['max_tries'])
            ->setTriesLeft($raw_game['tries_left'])
        ;

        return $game;
    }

    /**
     * Get all games.
     *
     * @return array List of game instances.
     * @throws RuntimeException If there is an error loading the games.
     */
    public function findAll()
    {
        $query = <<<QUERY
SELECT
    id,
    full_word,
    word,
    max_tries,
    tries_left
FROM
    games
QUERY;
        $statement = $this->getDb()->prepare($query);
        $result = $statement->execute();

        if (false === $result) {
            throw new \RuntimeException("An error happened loading the game with id #{$id_game}");
        }

        $raw_games = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $games = array();
        foreach ($raw_games as $raw_game) {
            $game = $this->getFactory()->createEmptyGame();
            $game
                ->setId($raw_game['id'])
                ->setFullWord($raw_game['full_word'])
                ->setWord($raw_game['word'])
                ->setMaxTries($raw_game['max_tries'])
                ->setTriesLeft($raw_game['tries_left'])
            ;
            $games[] = $game;
        }

        return $games;
    }

    /**
     * Save a game.
     *
     * This method will either insert or update the game, depending on
     * whether the game already has an id.
     * If it's inserted, the generated id will be set in the game.
     *
     * @param Apps\Hangman\Entity\Game $game
     * @return Apps\Hangman\Entity\Game The saved game
     */
    public function save(Game $game)
    {
        if (0 < $game->getId()) {
            return $this->update($game);
        }

        return $this->insert($game);
    }

    /**
     * Update a game.
     *
     * @param Apps\Hangman\Entity\Game $game
     * @return Apps\Hangman\Entity\Game The saved game
     * @throws RuntimeException If there is an error saving the game.
     */
    private function update(Game $game)
    {
        $query = <<<QUERY
UPDATE
    games
SET
    full_word = :full_word,
    word = :word,
    max_tries = :max_tries,
    tries_left = :tries_left
WHERE
    id = :id_game
QUERY;
        $statement = $this->getDb()->prepare($query);
        $statement->bindValue(':id_game', $game->getId(), \PDO::PARAM_INT);
        $statement->bindValue(':full_word', $game->getFullWord(), \PDO::PARAM_INT);
        $statement->bindValue(':word', $game->getWord(), \PDO::PARAM_INT);
        $statement->bindValue(':max_tries', $game->getMaxTries(), \PDO::PARAM_INT);
        $statement->bindValue(':tries_left', $game->getTriesLeft(), \PDO::PARAM_INT);

        $result = $statement->execute();

        if (false === $result) {
            throw new \RuntimeException("An error happened updating the game with id #{$game->getId()}");
        }

        return $game;
    }

    /**
     * Insert a new game.
     *
     * @param Apps\Hangman\Entity\Game $game
     * @return Apps\Hangman\Entity\Game The saved game
     * @throws RuntimeException If there is an error saving the game.
     */
    private function insert(Game $game)
    {
        $query = <<<QUERY
INSERT INTO
    games
SET
    full_word = :full_word,
    word = :word,
    max_tries = :max_tries,
    tries_left = :tries_left
QUERY;

        $db        = $this->getDb();
        $statement = $db->prepare($query);
        $statement->bindValue(':full_word', $game->getFullWord(), \PDO::PARAM_INT);
        $statement->bindValue(':word', $game->getWord(), \PDO::PARAM_INT);
        $statement->bindValue(':max_tries', $game->getMaxTries(), \PDO::PARAM_INT);
        $statement->bindValue(':tries_left', $game->getTriesLeft(), \PDO::PARAM_INT);

        $result = $statement->execute();

        if (false === $result) {
            throw new \RuntimeException("An error happened inserting the game");
        }

        $game->setId($db->lastInsertId());

        return $game;
    }
}