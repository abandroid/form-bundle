<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Bundle\FormBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * @ORM\Entity
 */
class ChoiceField extends Field
{
    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $expanded = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $multiple = false;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Choice", mappedBy="field", cascade={"persist"})
     * @ORM\OrderBy({"position"="ASC"})
     */
    protected $choices;

    /**
     * Creates a new instance.
     */
    public function __construct()
    {
        $this->choices = new ArrayCollection();
    }

    /**
     * Determines if the choices should be expanded.
     *
     * @param $expanded
     *
     * @return $this
     */
    public function setExpanded($expanded)
    {
        $this->expanded = $expanded;

        return $this;
    }

    /**
     * Checks if the choices should be expanded.
     *
     * @return bool
     */
    public function getExpanded()
    {
        return $this->expanded;
    }

    /**
     * Determines if multiple values can be selected.
     *
     * @param $multiple
     *
     * @return $this
     */
    public function setMultiple($multiple)
    {
        $this->multiple = $multiple;

        return $this;
    }

    /**
     * Checks if the multiple values can be selected.
     *
     * @return bool
     */
    public function getMultiple()
    {
        return $this->multiple;
    }

    /**
     * Sets the choices.
     *
     * @param $choices
     *
     * @return $this
     */
    public function setChoices($choices)
    {
        $this->choices->clear();

        foreach ($choices as $choice) {
            $this->addChoice($choice);
        }

        return $this;
    }

    /**
     * Adds a choice.
     *
     * @param Choice $choice
     *
     * @return $this
     */
    public function addChoice(Choice $choice)
    {
        if (!$this->choices->contains($choice)) {
            $this->choices->add($choice);
            $choice->setField($this);
        }

        return $this;
    }

    /**
     * Determines if a choice exists.
     *
     * @param Choice $choice
     *
     * @return bool
     */
    public function hasChoice(Choice $choice)
    {
        return $this->choices->contains($choice);
    }

    /**
     * Returns the choices.
     *
     * @return Choice[]
     */
    public function getChoices()
    {
        return $this->choices->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function buildAdmin(FormMapper $formMapper)
    {
        parent::buildAdmin($formMapper);

        $formMapper
            ->with('General')
                ->add('expanded')
                ->add('multiple')
                ->add('choices', 'sonata_type_collection',
                    array(
                        'label' => 'form.admin.choice.list.label',
                        'required' => false,
                        'by_reference' => false,
                    ),
                    array(
                        'edit' => 'inline',
                        'inline' => 'table',
                        'sortable' => 'position',
                    )
                )
            ->end()
        ;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        $options = parent::getOptions();

        $choices = array();
        foreach ($this->getChoices() as $choice) {
            $choices[$choice->getLabel()] = $choice->getLabel();
        }

        $options['expanded'] = $this->expanded;
        $options['multiple'] = $this->multiple;
        $options['choices'] = $choices;

        if ($this->multiple && isset($options['data'])) {
            $options['data'] = explode(',', $options['data']);
        }

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'choice';
    }
}
