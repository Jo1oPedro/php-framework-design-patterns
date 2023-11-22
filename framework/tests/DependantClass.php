<?php

namespace Cascata\Framework\Tests;

class DependantClass
{
    public function __construct(
        private readonly DependencyClass $dependency,
        private readonly string $name
    ) {
    }

    public function getDependency(): DependencyClass
    {
        return $this->dependency;
    }

}