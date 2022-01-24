<?php
/**
 * User: TheCodeholic
 * Date: 7/7/2020
 * Time: 10:01 AM
 */

namespace thecodeholic\phpmvc;

use thecodeholic\phpmvc\exception\MethodNotAllowedException;
use thecodeholic\phpmvc\exception\NotFoundException;

/**
 * Class Router
 *
 * @author  Zura Sekhniashvili <zurasekhniashvili@gmail.com>
 * @package thecodeholic\mvc
 */
class Router
{
    private Request $request;
    private Response $response;
    private array $routeMap = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get(string $url, $callback)
    {
        $this->routeMap['get'][$url] = $callback;
    }

    public function post(string $url, $callback)
    {
        $this->routeMap['post'][$url] = $callback;
    }

    /**
     * Find a route with the given request URI.
     *
     * @param string $uri the request URI
     * @param string|null $givenMethod the method (optional)
     * @return array|null
     * @author Ar Rakin <rakinar2@gmail.com>
     */
    public function findRoute(string $uri, string $givenMethod = null)
    {
        foreach ($this->routeMap as $method => $routes) {
            if ((isset($routes[$uri]) && $givenMethod === null) || (isset($routes[$uri]) && $method === $givenMethod)) {
                return [
                    "method" => $method,
                    "route" => $uri,
                    "action" => $routes[$uri],
                ];
            }
        }

        return null;
    }

    public function resolve()
    {
        $method = $this->request->getMethod();
        $url = $this->request->getUrl();

        $routeAny = $this->findRoute($url);
        $route = $this->findRoute($url, $method);

        if ($route === null && $routeAny !== null) {
            throw new MethodNotAllowedException();
        }

        $callback = $route["action"] ?? false;

        if (!$callback) {
            throw new NotFoundException();
        }
        if (is_string($callback)) {
            return $this->renderView($callback);
        }
        if (is_array($callback)) {
            /**
             * @var $controller \thecodeholic\phpmvc\Controller
             */
            $controller = new $callback[0];
            $controller->action = $callback[1];
            Application::$app->controller = $controller;
            $middlewares = $controller->getMiddlewares();
            foreach ($middlewares as $middleware) {
                $middleware->execute();
            }
            $callback[0] = $controller;
        }
        return call_user_func($callback, $this->request, $this->response);
    }

    public function renderView($view, $params = [])
    {
        return Application::$app->view->renderView($view, $params);
    }

    public function renderViewOnly($view, $params = [])
    {
        return Application::$app->view->renderViewOnly($view, $params);
    }
}