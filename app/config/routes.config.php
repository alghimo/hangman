<?php

return array(
    'game_status' => array(
        'path'        => '@^/games/(?P<id_game>\d+)$@',
        'method'      => 'GET',
        'controller'  => 'Apps\Hangman\Controller\Game\ReadController',
        'path_params' => array('id_game'),
    ),
    'game_guess' => array(
        'path'        => '@^/games/(?P<id_game>\d+)$@',
        'method'      => 'POST',
        'controller'  => 'Apps\Hangman\Controller\Game\GuessController',
        'path_params' => array('id_game'),
    ),
    'game_new' => array(
        'path'        => '@^/games$@',
        'method'      => 'POST',
        'controller'  => 'Apps\Hangman\Controller\Game\CreateController',
    ),
    'game_list' => array(
        'path'        => '@^/games$@',
        'method'      => 'GET',
        'controller'  => 'Apps\Hangman\Controller\Game\ListController',
    ),
);