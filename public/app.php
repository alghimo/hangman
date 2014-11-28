<?php
ini_set('display_errors', true);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/bootstrap.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Framework\Routing\Router;
use Apps\Hangman\Model\Game\Repository as GameRepository;
use Apps\Hangman\Model\Game\Factory as GameFactory;
use Apps\Hangman\Model\Word\Generator as WordGenerator;

try
{
    $request         = Request::createFromGlobals();
    $routes          = require __APP_CONFIG_DIR . DIRECTORY_SEPARATOR . 'routes.config.php';
    $db_config       = require __APP_CONFIG_DIR . DIRECTORY_SEPARATOR . 'db.config.php';
    $game_config     = require __APP_CONFIG_DIR . DIRECTORY_SEPARATOR . 'game.config.php';
    $router          = new Router($routes);
    $db              = new \PDO($db_config['dsn'], $db_config['user'], $db_config['password'], $db_config['options']);
    $word_generator  = new WordGenerator(__APP_RESOURCES_DIR . $game_config['words_list'], $game_config['total_words']);
    $game_factory    = new GameFactory($word_generator, $game_config['max_tries']);
    $game_repository = new GameRepository($db, $game_factory);

    $matched_route = $router->match($request);
    $request->attributes->set('_matched_route', $matched_route);

    $controller      = new $matched_route['controller'];
    $controller->setGameRepository($game_repository);

    $response        = $controller->execute($request);
}
catch(\Exception $exception)
{
    $data     = array( 'error' => $exception->getMessage() );
    $response = new JsonResponse($data, 404);
}

$response->send();