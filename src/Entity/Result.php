<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Bundle\FormBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="form_result")
 */
class Result
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
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $datetimeAdded;

    /**
     * @var Form
     *
     * @ORM\ManyToOne(targetEntity="Form", inversedBy="results")
     */
    protected $form;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ResultEntry", mappedBy="result", cascade={"persist"})
     */
    protected $entries;

    /**
     * Creates a new instance.
     */
    public function __construct()
    {
        $this->entries = new ArrayCollection();
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
     * Sets the date and time of addition.
     *
     * @param DateTime $datetimeAdded
     *
     * @return $this
     */
    public function setDatetimeAdded(DateTime $datetimeAdded)
    {
        $this->datetimeAdded = $datetimeAdded;

        return $this;
    }

    /**
     * Returns the date and time of addition.
     *
     * @return DateTime
     */
    public function getDatetimeAdded()
    {
        return $this->datetimeAdded;
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
     * Adds an entry.
     *
     * @param ResultEntry $entry
     *
     * @return $this
     */
    public function addEntry(ResultEntry $entry)
    {
        if (!$this->entries->contains($entry)) {
            $this->entries->add($entry);
        }
        $entry->setResult($this);

        return $this;
    }

    /**
     * Removes an entry.
     *
     * @param ResultEntry $entry
     *
     * @return $this
     */
    public function removeEntry(ResultEntry $entry)
    {
        $this->entries->removeElement($entry);

        return $this;
    }

    /**
     * Returns the entries.
     *
     * @return ResultEntry[]
     */
    public function getEntries()
    {
        return $this->entries->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return strval($this->id);
    }
}
