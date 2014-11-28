<?php

namespace Apps\Hangman\Controller\Game;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Handles requests to play the game.
 *
 * "play" means trying to guess a letter from the word.
 *
 * @author Albert Giménez Morales
 * @package Apps\Hangman
 * @uses Apps\Hangman\Controller\Game\AbstractController
 */
class GuessController extends AbstractController
{
    /**
     * Allowed character pattern.
     *
     * @var string
     */
    const ALLOWED_CHARS_REGEXP = '@^[a-z]$@';

    /**
     * Execute the controller for the current request.
     *
     * This will get the char to play from the request, "play" and return the updated game.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function execute(Request $request)
    {
        $id_game = $request->attributes->get('_matched_route')['params']['id_game'];
        $game    = $this->getGameRepository()->findById($id_game);
        $char    = $this->getCharFromRequest($request);

        $game->play($char);
        $this->getGameRepository()->save($game);

        return new JsonResponse($game);
    }

    /**
     * Gets the char to play from the request.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     * @return string
     * @throws DomainException If the char is not valid (i.e.: if it doesn't match the ALLOWED_CHARS_REGEXP pattern)
     * @todo Move this to a model / separate class.
     */
    private function getCharFromRequest(Request $request)
    {
        $char = $request->get('char');

        if (!preg_match(self::ALLOWED_CHARS_REGEXP, $char)) {
            throw new \DomainException("Invalid char provided: '{$char}'");
        }

        return $char;
    }

}