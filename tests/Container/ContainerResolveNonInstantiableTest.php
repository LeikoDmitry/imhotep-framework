<?php

namespace Imhotep\Tests\Container;

use Imhotep\Container\Container;
use PHPUnit\Framework\TestCase;

class ContainerResolveNonInstantiableTest extends TestCase
{
    public function testResolvingNonInstantiableWithDefaultRemovesWiths()
    {
        $container = new Container;
        $object = $container->make(ParentClass::class, ['i' => 42]);

        $this->assertSame(42, $object->i);
    }

    public function testResolvingNonInstantiableWithVariadicRemovesWiths()
    {
        $container = new Container;
        $parent = $container->make(VariadicParentClass::class, ['i' => 42]);

        $this->assertCount(0, $parent->child->objects);
        $this->assertSame(42, $parent->i);
    }

    public function testResolveVariadicPrimitive()
    {
        $container = new Container;
        $parent = $container->make(VariadicPrimitive::class);

        $this->assertSame($parent->params, []);
    }
}

interface TestInterface
{
}

class ParentClass
{
    public $i;

    public function __construct(TestInterface $testObject = null, int $i = 0)
    {
        $this->i = $i;
    }
}

class VariadicParentClass
{
    public $child;

    public $i;

    public function __construct(ChildClass $child, int $i = 0)
    {
        $this->child = $child;
        $this->i = $i;
    }
}

class ChildClass
{
    public $objects;

    public function __construct(TestInterface ...$objects)
    {
        $this->objects = $objects;
    }
}

class VariadicPrimitive
{
    public $params;

    public function __construct(...$params)
    {
        $this->params = $params;
    }
}