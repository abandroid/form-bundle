<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\FormBundle\Event;

use Endroid\FormBundle\Entity\Result;
use Symfony\Component\EventDispatcher\Event;

class FormSuccessEvent extends Event
{
    const NAME = 'endroid_form.success';

    /**
     * @var Result
     */
    protected $result;

    /**
     * Creates a new instance.
     *
     * @param Result $result
     */
    public function __construct(Result $result)
    {
        $this->result = $result;
    }

    /**
     * Returns the result.
     *
     * @return Result
     */
    public function getResult()
    {
        return $this->result;
    }
}
