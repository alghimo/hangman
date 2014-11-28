<?php

namespace Apps\Hangman\Controller\Game;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Handles requests to list all games.
 *
 * @author Albert Giménez Morales
 * @package Apps\Hangman
 * @uses Apps\Hangman\Controller\Game\AbstractController
 */
class ListController extends AbstractController
{
    /**
     * Execute the controller for the current request.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function execute(Request $request)
    {
        $games = $this->getGameRepository()->findAll();

        return new JsonResponse($games);
    }
}