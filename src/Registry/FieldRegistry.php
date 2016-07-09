<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Bundle\FormBundle\Registry;

class FieldRegistry
{
    /**
     * @var array
     */
    protected $fields;

    /**
     * Creates a new instance.
     *
     * @param array $fields
     */
    public function __construct(array $fields = array())
    {
        $this->fields = $fields;
    }

    /**
     * Returns all fields.
     *
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }
}
