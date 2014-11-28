<?php

namespace Apps\Hangman\Entity;

/**
 * Represents a game.
 *
 * @author Albert Giménez Morales
 * @package Apps\Hangman
 * @uses \JsonSerialize This class is serialized in json responses.
 */
class Game implements \JsonSerializable
{
    /**
     * Represents the "success" game status.
     *
     * @var string
     */
    const STATUS_SUCCESS = 'success';

    /**
     * Represents the "fail" game status.
     *
     * @var string
     */
    const STATUS_FAIL = 'fail';

    /**
     * Represents the "busy" game status.
     *
     * @var string
     */
    const STATUS_BUSY = 'busy';

    /**
     * Char used for hidden letters.
     *
     * @var string
     */
    const HIDDEN_LETTER_CHAR = '.';

    /**
     * Game ID.
     *
     * @var integer
     */
    private $id;

    /**
     * Full word.
     *
     * @var string
     */
    private $full_word;

    /**
     * Maximum number of tries.
     *
     * @var integer
     */
    private $max_tries;

    /**
     * Word that's being guessed.
     *
     * @var string
     */
    private $word;

    /**
     * Word that's being guessed.
     *
     * @var string
     */
    private $tries_left;

    /**
     * Get the game ID.
     *
     * @return integer|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the game ID.
     *
     * @param integer $id
     * @return Apps\Hangman\Entity\Game Self instance
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the full word (the word we want to guess).
     *
     * @return string
     */
    public function getFullWord()
    {
        return $this->full_word;
    }

    /**
     * Set the full word (the word to be guessed).
     *
     * This method will also automatically initialize the word being guessed.
     * For example, if we set the full word "cat", the word to be guessed will become "...".
     *
     * @param string $full_word
     * @return Apps\Hangman\Entity\Game Self instance
     */
    public function setFullWord($full_word)
    {
        $this->full_word = $full_word;
        $this->word      = str_pad("", strlen($full_word), self::HIDDEN_LETTER_CHAR);

        return $this;
    }

    /**
     * Get the max number of tries for this game.
     *
     * @return integer
     */
    public function getMaxTries()
    {
        return $this->max_tries;
    }

    /**
     * Set the max number of tries for this game.
     *
     * This method will also automatically initialize number of tries left, to the exact same value.
     * For example, if we set the max tries to 10, the number of tries left will be 10, too.
     *
     * @param integer $max_tries
     * @return Apps\Hangman\Entity\Game Self instance
     */
    public function setMaxTries($max_tries)
    {
        $this->max_tries  = $max_tries;
        $this->tries_left = $max_tries;

        return $this;
    }

    /**
     * Get the word being guessed.
     *
     * @return string
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * Set the word being guessed.
     *
     * @param string $word
     * @return Apps\Hangman\Entity\Game Self instance
     */
    public function setWord($word)
    {
        $this->word = $word;

        return $this;
    }

    /**
     * Get the number of tries left.
     *
     * @return integer
     */
    public function getTriesLeft()
    {
        return $this->tries_left;
    }

    /**
     * Set the number of tries left.
     *
     * @param integer $tries_left
     * @return Apps\Hangman\Entity\Game Self instance
     */
    public function setTriesLeft($tries_left)
    {
        $this->tries_left = $tries_left;

        return $this;
    }

    /**
     * Check whether the game can still be played.
     *
     * @return boolean
     */
    public function canPlay()
    {
        return ( $this->getTriesLeft() > 0 );
    }

    /**
     * Play the game, trying to guess a new char.
     *
     * @param string $char
     * @return boolean True if the char was guessed, false otherwise.
     * @throws RuntimeException If there are no more tries left.
     */
    public function play($char)
    {
        if (!$this->canPlay()) {
            throw new \RuntimeException("No more tries left");
        }

        if ($this->guess($char)) {
            return true;
        }

        $this->tries_left--;

        return false;
    }

    /**
     * Get the current game status.
     *
     * The result will be one of the STATUS_XXX constants:
     * STATUS_BUSY: Game in progress
     * STATUS_SUCCESS: The word was guessed
     * STATUS_FAIL: No more tries left, and the word was not guessed.
     *
     * @return string
     */
    public function getStatus()
    {
        $status = self::STATUS_BUSY;

        if ($this->getFullWord() === $this->getWord()) {
            $status = self::STATUS_SUCCESS;
        } elseif (!$this->canPlay()) {
            $status = self::STATUS_FAIL;
        }

        return $status;
    }

    /**
     * Try to guess a character.
     *
     * If the provided char exists in the full word, it will replace
     * the "HIDDEN_LETTER_CHAR" by the real letters in the word being guessed.
     * For example, assuming that the full word is "cat", the word being guessed "..." and the char is "a",
     * the resulting word after playing will be ".a.".
     *
     *
     * @return boolean True if the char exists in the full word, false otherwise.
     */
    private function guess($char)
    {
        $guessed   = false;
        $full_word = $this->getFullWord();
        $offset    = strpos($full_word, $char);

        if (false !== $offset) {
            $word    = $this->getWord();
            $guessed = true;

            do {
                $word[$offset] = $char;
                $offset        = strpos($full_word, $char, $offset + 1);
            } while ($offset !== false);

            $this->word = $word;
        }

        return $guessed;
    }

    /**
     * Returns an array with data to be passed when json-encoding this entity.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array(
            'id'         => $this->getId(),
            'word'       => $this->getWord(),
            'tries_left' => $this->getTriesLeft(),
            'status'     => $this->getStatus(),
        );
    }
}