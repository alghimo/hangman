<?php

namespace Framework\Routing;

use Symfony\Component\HttpFoundation\Request;

/**
 * Declares common method to be implemented by router instances.
 *
 * @author Albert Giménez Morales
 * @package Framework\Routing
 */
interface RouterInterface
{
    /**
     * Sets all the routes to be handled.
     *
     * @param array $routes
     * @return RoutingInterface Self instance.
     */
    public function setRoutes(array $routes);

    /**
     * Gets all the routes.
     *
     * @return array
     */
    public function getRoutes();

    /**
     * Tries to match the current request with one of the existing routes.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     * @return array Matched route.
     * @throws DomainException If no route can be matched for the current request.
     */
    public function match(Request $request);
}

