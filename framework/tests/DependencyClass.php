<?php

namespace Cascata\Framework\Tests;

class DependencyClass
{
    public function __construct(
        private readonly SubDependencyClass $subDependencyClass,
        callable $callable = null
    ) {
    }

    public function getSubDependency()
    {
        return $this->subDependencyClass;
    }
}