<?php

namespace Cascata\Framework\Http;

use Cascata\Framework\Http\Exceptions\HttpException;
use Cascata\Framework\Routing\RouterInterface;
use Doctrine\DBAL\Connection;
use Psr\Container\ContainerInterface;

class Kernel
{
    private $appEnv;

    public function __construct(
        private readonly RouterInterface $router,
        private readonly ContainerInterface $container
    ) {
        $this->appEnv = $this->container->get("APP_ENV");
    }

    public function handle(Request $request): Response
    {
        try {
            [$routeHandler, $vars] = $this->router->dispatch($request, $this->container);

            $response = call_user_func_array($routeHandler, $vars);
        } catch (\Exception $exception) {
            $response = $this->createExceptionResponse($exception);
        }

        return $response;
    }

    /**
     * @throws \Exception $exception
     */
    private function createExceptionResponse(\Exception $exception): Response
    {
        if(in_array($this->appEnv, ['dev', 'test'])) {
            throw $exception;
        }

        if($exception instanceof HttpException) {
            return new Response($exception->getMessage(), $exception->getStatusCode());
        }

        return new Response('Server error', Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}