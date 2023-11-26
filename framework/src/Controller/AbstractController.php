<?php

namespace Cascata\Framework\Controller;

use Cascata\Framework\Http\Response;
use Psr\Container\ContainerInterface;
use Twig\Environment;

abstract class AbstractController
{
    protected ?ContainerInterface $container = null;
    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public function render(string $templatePath, array $parameters = [], Response $response = null): Response
    {
        extract($parameters);
        require_once __DIR__ . "/../../../templates/$templatePath";
        return $response ??= new Response();
    }

    public function renderTwig(string $templatePath, array $parameters = [], Response $response = null): Response
    {
        /** @var Environment $twig */
        $twig = $this->container->get("twig");

        $response ??= new Response();

        $response->setContent($twig->render($templatePath, $parameters));

        return $response;
    }
}