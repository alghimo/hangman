<?php

namespace Framework\Application;

use Symfony\Component\HttpFoundation\Request;

/**
 * Declares methods to be implemented by all controllers.
 *
 * @author Albert Giménez Morales
 * @package Framework\Application
 */
interface ControllerInterface
{
    /**
     * Execute the controller for the given request.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function execute(Request $request);
}

