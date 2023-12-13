<?php

use Cascata\Framework\GlobalContainer\Container;
use Cascata\Framework\Http\Response;
use Cascata\Framework\Session\Session;
use Cascata\Framework\Session\SessionInterface;

function session(): Session
{
    $container = Container::getInstance();
    $session = $container->get(\Cascata\Framework\Session\SessionInterface::class);
    return $session;
}

function redirect(string $url): \Cascata\Framework\Http\RedirectResponse
{
    return new \Cascata\Framework\Http\RedirectResponse($url);
}

function render(string $templatePath, array $parameters = [], Response $response = null): Response
{
    $container = Container::getInstance();
    $basePath = $container->get('BASE_PATH');
    extract($parameters);
    require_once __DIR__ . "/../../../templates/$templatePath";
    return $response ??= new Response();
}

function errors(bool $flash = true): array
{
    /** @var Session $session */
    $session = Container::getInstance()->get(SessionInterface::class);
    if($session->has('errors')) {
        $errors = $session->get('errors');
        if($flash) {
            $session->remove('errors');
        }

        return $errors;
    }
    return [];
}