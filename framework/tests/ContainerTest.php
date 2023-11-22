<?php

namespace Cascata\Framework\Tests;

use Cascata\Framework\Container\Container;
use Cascata\Framework\Container\Exceptions\ContainerException;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ContainerTest extends TestCase
{
    /** @test */
    public function um_servico_pode_ser_recuperado_de_um_container(): void
    {
        // SETUP
        $container = new Container();

        // DO SOMETHING
        // id string, concrete class name string | object
        $container->add('dependant-class', DependantClass::class);

        // assertions
        $this->assertInstanceOf(DependantClass::class, $container->get('dependant-class'));
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ContainerException
     * @test
     */
    public function lanca_container_exception_se_o_servico_nao_foi_encontrado(): void
    {
        $container = new Container();

        $this->expectException(ContainerException::class);

        $container->add("dependant-class");
    }

    /**
     * @test
     * @throws ContainerException
     */
    public function deve_retornar_existencia_de_service_no_container(): void
    {
        $container = new Container();

        $container->add("dependent-class", DependantClass::class);

        $this->assertTrue($container->has("dependent-class"));
        $this->assertFalse($container->has("non-existing-class"));
    }
}