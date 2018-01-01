<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class DateField extends Field
{
    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        $options = parent::getOptions();

        $options['widget'] = 'single_text';
        $options['attr']['class'] = 'datepicker';
        $options['attr']['placeholder'] = 'dd-mm-yyyy';
        $options['input'] = 'string';
        $options['format'] = 'd-M-y';

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'date';
    }
}
