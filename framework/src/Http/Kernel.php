<?php

namespace Cascata\Framework\Http;

use Cascata\Framework\Http\Exceptions\HttpException;
use Cascata\Framework\Routing\RouterInterface;
use Psr\Container\ContainerInterface;

class Kernel
{
    public function __construct(
        private readonly RouterInterface $router,
        private readonly ContainerInterface $container
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            [$routeHandler, $vars] = $this->router->dispatch($request, $this->container);

            $response = call_user_func_array($routeHandler, $vars);
        } catch (HttpException $exception) {
            $response = new Response($exception->getMessage(), $exception->getStatusCode());
        } catch (\Exception $exception) {
            $response = new Response($exception->getMessage(), 500);
        }

        return $response;
    }
}