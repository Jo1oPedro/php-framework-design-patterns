<?php

namespace Cascata\Framework\Routing;

use Cascata\Framework\Controller\AbstractController;
use Cascata\Framework\Http\Exceptions\HttpException;
use Cascata\Framework\Http\Exceptions\HttpRequestMethodException;
use Cascata\Framework\Http\Request;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Psr\Container\ContainerInterface;
use function FastRoute\simpleDispatcher;

class Router implements RouterInterface
{
    const CONTROLLER = 0;
    const METHOD = 1;
    private Dispatcher $dispatcher;

    /**
     * @throws HttpException
     * @throws HttpRequestMethodException
     */
    public function dispatch(Request $request, ContainerInterface $container): array
    {
        $routeInfo = $this->extractRouteInfo($request);

        [$handler, $vars] = $routeInfo;

        if(is_array($handler)) {
            [$controllerId, $method] = $handler;

            $controller = $container->get($controllerId);

            /*if(is_subclass_of($controller, AbstractController::class)) {
                $controller->setRequest($request);
            }*/
            $handler = [$controller, $method];
            $vars = $this->autoWireMethod($handler, $vars, $container);
        }

        return [$handler, $vars];
    }

    /**
     * @throws HttpRequestMethodException
     * @throws HttpException
     */
    private function extractRouteInfo(Request $request): array
    {
        $routeInfo = $this->dispatcher->dispatch(
            $request->getMethod(),
            $request->getPathInfo()
        );

        switch ($routeInfo[0]) {
            case Dispatcher::FOUND:
                return [$routeInfo[1], $routeInfo[2]]; // routeHandler, vars
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = implode(', ', $routeInfo[1]);
                $excepetion = new HttpRequestMethodException("The allowed methods are $allowedMethods");
                $excepetion->setStatusCode(405);
                throw $excepetion;
            default:
                file_put_contents('x.txt', json_encode($routeInfo) . PHP_EOL . PHP_EOL . json_encode($request) . PHP_EOL . PHP_EOL . $request->getMethod() . $request->getPathInfo() . PHP_EOL . "FIM REQUEST" . PHP_EOL, FILE_APPEND);
                $excepetion = new HttpException("Route Not found");
                $excepetion->setStatusCode(404);
                throw $excepetion;
        }
    }

    public function setRoutes(RouteGrouper $routeGrouper): void
    {
        // Creates a dispatcher
        $this->dispatcher = simpleDispatcher(function (RouteCollector $routeCollector) use ($routeGrouper) {
            foreach($routeGrouper->getRoutes() as $route) {
                $routeCollector->addRoute(...$route);
            }
        });
    }

    private function autoWireMethod(array $handler, array $vars, ContainerInterface $container)
    {
        $reflection = new \ReflectionClass($handler[self::CONTROLLER]);
        $parameters = $reflection->getMethod($handler[self::METHOD])->getParameters();

        /** @var \ReflectionParameter $parameter */
        foreach($parameters as $parameter) {
            if(array_key_exists($parameter->getName(), $vars)) {
                continue;
            }
            $vars[$parameter->getName()] = $container->get($parameter->getType()->getName());
        }
        return $vars;
    }
}