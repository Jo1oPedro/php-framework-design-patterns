<?php

namespace Cascata\Tests;
use Cascata\Framework\Session\Session;
use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{
    protected function setUp(): void
    {
        unset($_SESSION);
    }

    public function testSetAndGetFlash(): void
    {
        $session = new Session();
        $session->setFlash('success', 'Sucesso');
        $session->setFlash('error', 'Erro');
        $this->assertTrue($session->hasFlash('success'));
        $this->assertTrue($session->hasFlash('error'));
        $this->assertEquals(['Sucesso'], $session->getFlash('success'));
        $this->assertEquals(['Erro'], $session->getFlash('error'));
        $this->assertEquals([], $session->getFlash('warning'));
    }
}