<?php

namespace Cascata\Framework\Routing;

use Cascata\Framework\Http\Request;
use Psr\Container\ContainerInterface;

interface RouterInterface
{
    public function dispatch(Request $request, ContainerInterface $container): array;
    public function setRoutes(RouteGrouper $routeGrouper): void;
}