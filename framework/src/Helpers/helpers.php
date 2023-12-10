<?php

use Cascata\Framework\Http\Response;

function session(): \Cascata\Framework\Session\Session
{
    $container = \Cascata\Framework\GlobalContainer\Container::getInstance();
    $session = $container->get(\Cascata\Framework\Session\SessionInterface::class);
    return $session;
}

function redirect(string $url): \Cascata\Framework\Http\RedirectResponse
{
    return new \Cascata\Framework\Http\RedirectResponse($url);
}

function render(string $templatePath, array $parameters = [], Response $response = null): Response
{
    $container = \Cascata\Framework\GlobalContainer\Container::getInstance();
    $basePath = $container->get('BASE_PATH');
    extract($parameters);
    require_once __DIR__ . "/../../../templates/$templatePath";
    return $response ??= new Response();
}