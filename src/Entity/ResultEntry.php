<?php

declare(strict_types=1);

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
 * @ORM\Table(name="form_result_entry")
 */
class ResultEntry
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $value;

    /**
     * @var Field
     *
     * @ORM\ManyToOne(targetEntity="Field", cascade={"persist"})
     */
    protected $field;

    /**
     * @var Result
     *
     * @ORM\ManyToOne(targetEntity="Result", inversedBy="entries")
     */
    protected $result;

    /**
     * Returns the ID.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the value of this entry.
     *
     * @param $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        if (is_array($value)) {
            $value = serialize($value);
        }

        $this->value = $value;

        return $this;
    }

    /**
     * Returns the value of this entry.
     *
     * @return array
     */
    public function getValue()
    {
        $value = @unserialize($this->value);
        if (is_array($value)) {
            $this->value = $value;
        }

        return $this->value;
    }

    /**
     * Sets the field.
     *
     * @param Field $field
     *
     * @return $this
     */
    public function setField(Field $field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Returns the field.
     *
     * @return Field
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Sets the result.
     *
     * @param Result $result
     *
     * @return $this
     */
    public function setResult(Result $result)
    {
        $this->result = $result;

        return $this;
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

    public function __toString()
    {
        return strval($this->value);
    }
}
