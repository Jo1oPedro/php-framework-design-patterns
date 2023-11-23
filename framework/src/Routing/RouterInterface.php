<?php

namespace Cascata\Framework\Routing;

use Cascata\Framework\Http\Request;

interface RouterInterface
{
    public function dispatch(Request $request): array;
    public function setRoutes(RouteGrouper $routeGrouper): void;
}