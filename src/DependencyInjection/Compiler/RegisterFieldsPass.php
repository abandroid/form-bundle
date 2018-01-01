<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\FormBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class RegisterFieldsPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('endroid_form.registry.field_registry')) {
            return;
        }

        $fieldRegistryDefinition = $container->getDefinition('endroid_form.registry.field_registry');

        $fields = [];
        foreach ($container->findTaggedServiceIds('endroid_form.field') as $id => $tag) {
            $fields[substr($id, strrpos($id, '.') + 1)] = $container->getDefinition($id)->getClass();
        }

        $fieldRegistryDefinition->setArguments([$fields]);

        if (!$container->hasDefinition('endroid_form.admin.field_admin')) {
            return;
        }

        $container
            ->getDefinition('endroid_form.admin.field_admin')
            ->addMethodCall('setSubClasses', $fieldRegistryDefinition->getArguments())
        ;
    }
}
