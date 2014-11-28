<?php

namespace Apps\Hangman\Controller\Game;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Handles requests to create new games.
 *
 * @author Albert Giménez Morales
 * @package Apps\Hangman
 * @uses Apps\Hangman\Controller\Game\AbstractController
 */
class CreateController extends AbstractController
{
    /**
     * Execute the controller for the current request.
     *
     * This will simply create and save a new game, and return a JsonResponse with game data.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function execute(Request $request)
    {
        $game = $this->getGameRepository()->createNew();
        $this->getGameRepository()->save($game);

        return new JsonResponse($game);
    }
}