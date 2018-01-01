<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Email;

/**
 * @ORM\Entity
 */
class EmailField extends Field
{
    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        $options = parent::getOptions();

        $options['constraints'][] = new Email();

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'email';
    }
}
