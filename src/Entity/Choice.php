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
 * @ORM\Table(name="form_field_choice")
 */
class Choice
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
     * @ORM\Column(type="string", length=255)
     */
    protected $label;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $position;

    /**
     * @var ChoiceField
     *
     * @ORM\ManyToOne(targetEntity="ChoiceField", inversedBy="choices")
     */
    protected $field;

    /**
     * Creates a new instance.
     *
     * @param $label
     */
    public function __construct($label = '')
    {
        $this->label = $label;
    }

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
     * Sets the label.
     *
     * @param $label
     *
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Returns the label.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Sets the position.
     *
     * @param $position
     *
     * @return $this
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Returns the position.
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Sets the field.
     *
     * @param ChoiceField $field
     *
     * @return $this
     */
    public function setField(ChoiceField $field)
    {
        $this->field = $field;

        if (!$field->hasChoice($this)) {
            $field->hasChoice($this);
        }

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

    public function __toString()
    {
        return (string) $this->label;
    }
}
