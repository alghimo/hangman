<?php

namespace Apps\Hangman\Controller\Game;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Handles requests to list a single game by id.
 *
 * @author Albert Giménez Morales
 * @package Apps\Hangman
 * @uses Apps\Hangman\Controller\Game\AbstractController
 */
class ReadController extends AbstractController
{
    /**
     * Execute the controller for the current request.
     *
     * This will take the game id from the request and load it.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function execute(Request $request)
    {
        $id_game = $request->attributes->get('_matched_route')['params']['id_game'];
        $game    = $this->getGameRepository()->findById($id_game);

        return new JsonResponse($game);
    }
}