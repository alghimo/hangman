<?php

namespace Framework\Routing;

use Symfony\Component\HttpFoundation\Request;

/**
 * Handles the application routing.
 *
 * @author Albert Giménez Morales
 * @package Framework\Routing
 * @uses Framework\Routing\RouterInterface
 */
class Router implements RouterInterface
{
    /**
     * List of routes.
     *
     * @var array
     */
    private $routes = array();

    /**
     * Class constructor.
     *
     * It will initialize the routes, if provided.
     *
     * @param array $routes
     */
    public function __construct(array $routes = array())
    {
        $this->routes = $routes;
    }

    /**
     * Get the list of routes.
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Set the list of routes.
     *
     * @param array $routes
     * @return Framework\Routing\Router Self instance
     */
    public function setRoutes(array $routes)
    {
        $this->routes = $routes;

        return $this;
    }

    /**
     * Matches a request against the current list of routes.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     * @return array The route that was matched.
     * @throws DomainException If no route can be matched for the request.
     */
    public function match(Request $request)
    {
        $path   = $request->getPathInfo();
        $method = $request->getMethod();

        foreach ($this->getRoutes() as $route) {
            if (
                $this->matchMethod($route, $method)
                && $this->matchPath($route, $path)
            ) {
                $route['params'] = $this->getPathParams($route, $path);
                return $route;
            }
        }

        throw new \DomainException("No route found for path {$path} and method {$method}");
    }

    /**
     * Checks whether the route method is the same that the provided method.
     *
     * @param array $route Route to check.
     * @param string $method Method to compare to.
     * @return boolean
     */
    private function matchMethod(array $route, $method)
    {
        return ( $route['method'] === $method );
    }

    /**
     * Checks whether the provided path matches the route path regexp.
     *
     * @param array $route Route to check.
     * @param string $path Path to match against the route path regexp.
     * @return boolean
     */
    private function matchPath(array $route, $path)
    {
        return preg_match($route['path'], $path);
    }

    /**
     * If the route should extract params from the path, they'll be extracted and added here.
     *
     * IMPORTANT:
     * If the route had params and we extract params with the same name from the path,
     * that param(s) will be overriden.
     *
     * @param array $route Route to add the params to.
     * @param string $path Path to extract the params.
     * @return array List of params (existing route params + params extracted from the path)
     */
    private function getPathParams(array $route, $path)
    {
        $params = ( isset($route['params']) ) ? $route['params'] : array();
        if (empty($route['path_params'])) {
            return $params;
        }

        preg_match_all($route['path'], $path, $matches);

        foreach ($route['path_params'] as $param_name) {
            $params[$param_name] = $matches[$param_name][0];
        }

        return $params;
    }
}