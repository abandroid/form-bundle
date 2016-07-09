<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Bundle\FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @ORM\Entity
 * @ORM\Table(name="form_field")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 */
abstract class Field
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
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="defaultValue", type="string", nullable=true)
     */
    protected $default;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $required = false;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $position;

    /**
     * @var Form
     *
     * @ORM\ManyToOne(targetEntity="Form", inversedBy="fields")
     * @ORM\JoinColumn(name="form_id", referencedColumnName="id")
     */
    protected $form;

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
     * Sets the title.
     *
     * @param $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Returns the title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the default.
     *
     * @param $default
     *
     * @return $this
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * Returns the default.
     *
     * @return string
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Sets the form.
     *
     * @param Form $form
     *
     * @return $this
     */
    public function setForm(Form $form)
    {
        $this->form = $form;

        if (!$form->hasField($this)) {
            $form->addField($this);
        }

        return $this;
    }

    /**
     * Returns the form.
     *
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Determines if the field is required.
     *
     * @param $required
     *
     * @return $this
     */
    public function setRequired($required)
    {
        $this->required = $required;

        return $this;
    }

    /**
     * Checks if the field is required.
     *
     * @return bool
     */
    public function getRequired()
    {
        return $this->required;
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
     * Builds the form.
     *
     * @param FormBuilderInterface $formBuilder
     *
     * @return $this
     */
    public function build(FormBuilderInterface $formBuilder)
    {
        $formBuilder->add('field_'.$this->getId(), $this->getType(), $this->getOptions());

        return $this;
    }

    /**
     * Builds dhe admin form.
     *
     * @param FormMapper $formMapper
     *
     * @return $this
     */
    public function buildAdmin(FormMapper $formMapper)
    {
        return $this;
    }

    /**
     * Returns the form type of this field.
     *
     * @return string
     */
    public function getType()
    {
        return 'text';
    }

    /**
     * Returns the field options.
     *
     * @return array
     */
    public function getOptions()
    {
        $options = array(
            'label' => $this->title,
            'required' => $this->required,
            'constraints' => $this->required ? array(new NotBlank()) : array(),
        );

        if ($this->getDefault()) {
            $options['data'] = $this->getDefault();
        }

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return (string) $this->title;
    }
}
