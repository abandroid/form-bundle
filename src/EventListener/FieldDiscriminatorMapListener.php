<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\FormBundle\EventListener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Endroid\FormBundle\Registry\FieldRegistry;

class FieldDiscriminatorMapListener
{
    /**
     * @var FieldRegistry
     */
    protected $fieldRegistry;

    /**
     * Creates a new instance.
     *
     * @param FieldRegistry $fieldRegistry
     */
    public function __construct(FieldRegistry $fieldRegistry)
    {
        $this->fieldRegistry = $fieldRegistry;
    }

    /**
     * Sets the discriminator map when loading class meta data.
     *
     * @param LoadClassMetadataEventArgs $args
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $args)
    {
        /** @var ClassMetaData $classMetadata */
        $classMetadata = $args->getClassMetadata();

        if ($classMetadata->getName() != 'Endroid\FormBundle\Entity\Field') {
            return;
        }

        $classMetadata->discriminatorMap = $this->fieldRegistry->getFields();
    }
}
