<?php

/*
 * This file is part of the flysystem-bundle project.
 *
 * (c) Titouan Galopin <galopintitouan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\League\FlysystemBundle\Adapter\Builder;

use League\Flysystem\Cached\CachedAdapter;
use League\FlysystemBundle\Adapter\Builder\CacheAdapterDefinitionBuilder;
use Symfony\Component\DependencyInjection\Reference;

class CacheAdapterDefinitionBuilderTest extends AbstractAdapterDefinitionBuilderTest
{
    public function createBuilder()
    {
        return new CacheAdapterDefinitionBuilder($this->createDefinitionFactory());
    }

    public function provideValidOptions()
    {
        yield 'minimal' => [[
            'store' => 'my_store',
            'source' => 'my_source',
        ]];
    }

    /**
     * @dataProvider provideValidOptions
     */
    public function testCreateDefinition($options)
    {
        $this->assertSame(CachedAdapter::class, $this->createBuilder()->createDefinition($options)->getClass());
    }

    public function testOptionsBehavior()
    {
        $definition = $this->createBuilder()->createDefinition([
            'store' => 'my_store',
            'source' => 'my_source',
        ]);

        $this->assertSame(CachedAdapter::class, $definition->getClass());
        $this->assertInstanceOf(Reference::class, $definition->getArgument(0));
        $this->assertSame('flysystem.adapter.my_source', (string) $definition->getArgument(0));
        $this->assertInstanceOf(Reference::class, $definition->getArgument(1));
        $this->assertSame('my_store', (string) $definition->getArgument(1));
    }
}