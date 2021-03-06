<?php

namespace Behat\Behat\DependencyInjection\Compiler;

/*
 * This file is part of the Behat.
 *
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Context generators pass.
 * Registers all available context generators.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class ContextGeneratorsPass implements CompilerPassInterface
{
    /**
     * Processes container.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $initDefinition = $container->getDefinition('console.processor.init');

        $calls = array();
        foreach ($container->findTaggedServiceIds('context.generator') as $id => $attributes) {
            $calls[] = array('registerGenerator', array(new Reference($id)));
        }

        $initDefinition->setMethodCalls(array_merge($calls, $initDefinition->getMethodCalls()));
    }
}
