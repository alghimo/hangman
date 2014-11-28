<?php

namespace Apps\Hangman\Model\Game;

use Apps\Hangman\Model\Word\Generator as WordGenerator;
use Apps\Hangman\Entity\Game;

/**
 * Responsible for creation of new games, using the word generator.
 *
 * @author Albert Giménez Morales
 * @package Apps\Hangman
 */
class Factory
{
    /**
     * Word generator.
     *
     * @var Apps\Hangman\Model\Word\Generator
     */
    private $word_generator;

    /**
     * Default number of tries when creating new games.
     *
     * @var integer
     * @todo Change this integer for and implement some max tries strategy instead.
     */
    private $max_tries;

    /**
     * Class constructor.
     *
     * Injects the word generator and max tries.
     */
    public function __construct(WordGenerator $word_generator, $max_tries)
    {
        $this
            ->setWordGenerator($word_generator)
            ->setMaxTries($max_tries)
        ;
    }

    /**
     * Set the word generator.
     *
     * @param Apps\Hangman\Model\Word\Generator
     * @return Apps\Hangman\Model\Game\Factory Self instance.
     */
    public function setWordGenerator(WordGenerator $word_generator)
    {
        $this->word_generator = $word_generator;

        return $this;
    }

    /**
     * Get the word generator.
     *
     * @return Apps\Hangman\Model\Word\Generator
     */
    public function getWordGenerator()
    {
        return $this->word_generator;
    }

    /**
     * Set the default number of tries for new games.
     *
     * @param integer
     * @return Apps\Hangman\Model\Game\Factory Self instance.
     */
    public function setMaxTries($max_tries)
    {
        $this->max_tries = $max_tries;

        return $this;
    }

    /**
     * Get the default number of tries for new games.
     *
     * @return integer
     */
    public function getMaxTries()
    {
        return $this->max_tries;
    }

    /**
     * Creates a new game, setting the max tries and a word from the generator.
     *
     * @return Apps\Hangman\Entity\Game
     */
    public function createNewGame()
    {
        $word = $this->getWordGenerator()->getWord();
        $game = new Game();
        $game
            ->setFullWord($word)
            ->setMaxTries($this->max_tries)
        ;

        return $game;
    }

    /**
     * Creates an empty game with no initializations.
     *
     * @return Apps\Hangman\Entity\Game
     */
    public function createEmptyGame()
    {
        return new Game();
    }
}