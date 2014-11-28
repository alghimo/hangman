<?php

namespace Framework\Routing;

/**
 * UnitTest for the "Router" class.
 *
 * @author Albert Giménez Morales
 * @package Framework\Routing
 * @uses \Framework\Routing\Router
 * @uses \PHPUnit_Framework_TestCase
 */
class RouterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * List of mocked routes.
     *
     * @var array
     */
    private $routes = array();

    /**
     * This is the object we're testing.
     *
     * @var Framework\Routing\Router
     */
    private $router;

    public function setUp()
    {
        $this->routes = $this->getMockedRoutes();
        $this->router = new Router($this->routes);
    }

    public function tearDown()
    {
        $this->routes = null;
        $this->router = null;
    }

    /**
     * Test that the list of routes is properly injected in the constructor.
     */
    public function testConstructorSetsRoutes()
    {
        $router = new Router($this->routes);

        $this->assertSame($this->routes, $router->getRoutes(), 'Routes were not set in the constructor call.');
    }

    /**
     * Test route accessor methods.
     */
    public function testRouteAccessors()
    {
        $this->router->setRoutes($this->routes);
        $this->assertSame($this->routes, $this->router->getRoutes(), 'The routes were not set properly.');
    }

    /**
     * Test that setRoutes implements fluent interface.
     */
    public function testSetRoutesImplementsFluentInterface()
    {
        $this->routes = $this->getMockedRoutes();

        $result = $this->router->setRoutes($this->routes);

        $this->assertSame($this->router, $result, 'Router::setRoutes does not implement fluent interface.');
    }

    /**
     * Test match method.
     *
     * The match method will take the request, and try to match it against one of the existing routes.
     *
     * @dataProvider matchProvider
     */
    public function testMatch($mocked_path, $mocked_method, $expected_route_name)
    {
        $expected_route = $this->routes[$expected_route_name];
        $mocked_request = $this->getMockedRequest($mocked_path, $mocked_method);

        $matched_route = $this->router->match($mocked_request);

        $this->assertSame($expected_route, $matched_route, "Route '{$expected_route_name}' was not matched.");
    }

    /**
     * Data provider for match.
     *
     * @return array
     */
    public function matchProvider()
    {
        return array(
            'login_get match' => array(
                'mocked_path'         => '/login',
                'mocked_method'       => 'GET',
                'expected_route_name' => 'login_get',
            ),
            'login_post match' => array(
                'mocked_path'         => '/login',
                'mocked_method'       => 'POST',
                'expected_route_name' => 'login_post',
            ),
            'home match' => array(
                'mocked_path'         => '/',
                'mocked_method'       => 'GET',
                'expected_route_name' => 'home',
            ),
        );
    }

    /**
     * Test match method
     */
    public function testMatchRouteWithParams()
    {
        $expected_route_params = array('id_article' => '10');
        $mocked_request        = $this->getMockedRequest('/articles/10', 'GET');

        $matched_route = $this->router->match($mocked_request);

        $this->assertSame($expected_route_params, $matched_route['params'], "Route 'article' was not matched.");
    }

    /**
     * List of mocked routes.
     *
     * @return array
     */
    private function getMockedRoutes()
    {
        return array(
            'login_get' => array(
                'path'        => '@^/login$@',
                'method'      => 'GET',
                'controller'  => 'My\\Login\\GetController',
                'params'      => array(),
            ),
            'login_post' => array(
                'path'        => '@^/login$@',
                'method'      => 'POST',
                'controller'  => 'My\\Login\\PostController',
                'params'      => array(),
            ),
            'article' => array(
                'path'        => '@^/articles/(?P<id_article>\d+)$@',
                'method'      => 'GET',
                'controller'  => 'My\\LoginCheck\\Controller',
                'path_params' => array('id_article'),
                'params'      => array(),
            ),
            'home' => array(
                'path'        => '@^/$@',
                'method'      => 'GET',
                'controller'  => 'My\\Fake\\Controller',
                'params'      => array(),
            ),
        );
    }

    /**
     * Get a mocked request instance.
     *
     * @return Symfony\Component\HttpFoundation\Request
     */
    private function getMockedRequest($path, $method)
    {
        $methods_to_mock = array('getPathInfo', 'getMethod');
        $mocked_request  = $this->getMock('\Symfony\Component\HttpFoundation\Request', $methods_to_mock);

        $mocked_request
            ->expects($this->once())
            ->method('getPathInfo')
            ->will($this->returnValue($path))
        ;
        $mocked_request
            ->expects($this->once())
            ->method('getMethod')
            ->will($this->returnValue($method))
        ;

        return $mocked_request;
    }
}