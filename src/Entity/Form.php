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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="form")
 */
class Form
{
    const SUCCESS_ACTION_REDIRECT = 'redirect';
    const SUCCESS_ACTION_MESSAGE = 'message';

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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Field", mappedBy="form", cascade={"persist"})
     * @ORM\OrderBy({"position"="ASC"})
     */
    protected $fields;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $sendMail = false;

    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"email"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $mailRecipientName;

    /**
     * @var string
     *
     * @Assert\Email(groups={"email"})
     * @Assert\NotBlank(groups={"email"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $mailRecipientEmail;

    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"email"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $mailSubject;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $mailBody;

    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"email"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $mailSenderName;

    /**
     * @var string
     *
     * @Assert\Email(groups={"email"})
     * @Assert\NotBlank(groups={"email"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $mailSenderEmail;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $successAction = self::SUCCESS_ACTION_MESSAGE;

    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"redirect"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $successUrl;

    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"message"})
     * @ORM\Column(type="text", nullable=true)
     */
    protected $successMessage;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $storeResults = true;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Result", mappedBy="form", cascade={"persist"})
     */
    protected $results;

    /**
     * Creates a new instance.
     */
    public function __construct()
    {
        $this->fields = new ArrayCollection();
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
     * Sets the fields.
     *
     * @param $fields
     *
     * @return $this
     */
    public function setFields($fields)
    {
        $this->fields->clear();

        foreach ($fields as $field) {
            $this->addField($field);
        }

        return $this;
    }

    /**
     * Adds a field.
     *
     * @param Field $field
     *
     * @return $this
     */
    public function addField(Field $field)
    {
        if (!$this->fields->contains($field)) {
            $this->fields->add($field);
            $field->setForm($this);
        }

        return $this;
    }

    /**
     * Determines if a field exists.
     *
     * @param Field $field
     *
     * @return bool
     */
    public function hasField(Field $field)
    {
        return $this->fields->contains($field);
    }

    /**
     * Returns the fields.
     *
     * @return Field[]
     */
    public function getFields()
    {
        return $this->fields->toArray();
    }

    /**
     * Determines if a mail should be sent.
     *
     * @param $sendMail
     *
     * @return $this
     */
    public function setSendMail($sendMail)
    {
        $this->sendMail = $sendMail;

        return $this;
    }

    /**
     * Checks if a mail should be sent.
     *
     * @return bool
     */
    public function getSendMail()
    {
        return $this->sendMail;
    }

    /**
     * Sets the mail recipient name.
     *
     * @param $mailRecipientName
     *
     * @return $this
     */
    public function setMailRecipientName($mailRecipientName)
    {
        $this->mailRecipientName = $mailRecipientName;

        return $this;
    }

    /**
     * Returns the mail recipient name.
     *
     * @return string
     */
    public function getMailRecipientName()
    {
        return $this->mailRecipientName;
    }

    /**
     * Sets the mail recipient email address.
     *
     * @param $mailRecipientEmail
     *
     * @return $this
     */
    public function setMailRecipientEmail($mailRecipientEmail)
    {
        $this->mailRecipientEmail = $mailRecipientEmail;

        return $this;
    }

    /**
     * Returns the mail recipient email address.
     *
     * @return string
     */
    public function getMailRecipientEmail()
    {
        return $this->mailRecipientEmail;
    }

    /**
     * Sets the mail subject.
     *
     * @param $mailSubject
     *
     * @return $this
     */
    public function setMailSubject($mailSubject)
    {
        $this->mailSubject = $mailSubject;

        return $this;
    }

    /**
     * Returns the mail subject.
     *
     * @return string
     */
    public function getMailSubject()
    {
        return $this->mailSubject;
    }

    /**
     * Sets the mail body.
     *
     * @param $mailBody
     *
     * @return $this
     */
    public function setMailBody($mailBody)
    {
        $this->mailBody = $mailBody;

        return $this;
    }

    /**
     * Returns the mail body.
     *
     * @return string
     */
    public function getMailBody()
    {
        return $this->mailBody;
    }

    /**
     * Sets the mail sender name.
     *
     * @param $mailSenderName
     *
     * @return $this
     */
    public function setMailSenderName($mailSenderName)
    {
        $this->mailSenderName = $mailSenderName;

        return $this;
    }

    /**
     * Returns the mail sender name.
     *
     * @return string
     */
    public function getMailSenderName()
    {
        return $this->mailSenderName;
    }

    /**
     * Sets the mail sender email address.
     *
     * @param $mailSenderEmail
     *
     * @return $this
     */
    public function setMailSenderEmail($mailSenderEmail)
    {
        $this->mailSenderEmail = $mailSenderEmail;

        return $this;
    }

    /**
     * Returns the mail sender email address.
     *
     * @return string
     */
    public function getMailSenderEmail()
    {
        return $this->mailSenderEmail;
    }

    /**
     * Sets the success action.
     *
     * @param $successAction
     *
     * @return $this
     */
    public function setSuccessAction($successAction)
    {
        $this->successAction = $successAction;

        return $this;
    }

    /**
     * Returns the success action.
     *
     * @return string
     */
    public function getSuccessAction()
    {
        return $this->successAction;
    }

    /**
     * Sets the success URL.
     *
     * @param $successUrl
     *
     * @return $this
     */
    public function setSuccessUrl($successUrl)
    {
        $this->successUrl = $successUrl;

        return $this;
    }

    /**
     * Returns the success URL.
     *
     * @return string
     */
    public function getSuccessUrl()
    {
        return $this->successUrl;
    }

    /**
     * Sets the success message.
     *
     * @param $successMessage
     *
     * @return $this
     */
    public function setSuccessMessage($successMessage)
    {
        $this->successMessage = $successMessage;

        return $this;
    }

    /**
     * Returns the success message.
     *
     * @return string
     */
    public function getSuccessMessage()
    {
        return $this->successMessage;
    }

    /**
     * Determines if results should be stored.
     *
     * @param $storeResults
     *
     * @return $this
     */
    public function setStoreResults($storeResults)
    {
        $this->storeResults = $storeResults;

        return $this;
    }

    /**
     * Checks if results should be stored.
     *
     * @return bool
     */
    public function getStoreResults()
    {
        return $this->storeResults;
    }

    /**
     * Sets the results.
     *
     * @param $results
     *
     * @return $this
     */
    public function setResults($results)
    {
        $this->results->clear();

        foreach ($results as $result) {
            $this->addResult($result);
        }

        return $this;
    }

    /**
     * Adds a result.
     *
     * @param Result $result
     *
     * @return $this
     */
    public function addResult(Result $result)
    {
        if (!$this->results->contains($result)) {
            $this->results->add($result);
            $result->setForm($this);
        }

        return $this;
    }

    /**
     * Determines if a result exists.
     *
     * @param Result $result
     *
     * @return bool
     */
    public function hasResult(Result $result)
    {
        return $this->results->contains($result);
    }

    /**
     * Returns the results.
     *
     * @return Result[]
     */
    public function getResults()
    {
        return $this->results->toArray();
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
        foreach ($this->getFields() as $field) {
            $field->build($formBuilder);
        }

        $formBuilder->add('submit', SubmitType::class);

        return $this;
    }

    /**
     * Creates a result given the data.
     *
     * @param array $data
     *
     * @return Result
     */
    public function createResult(array $data = array())
    {
        $result = new Result();
        $result->setForm($this);
        $result->setDatetimeAdded(new DateTime());

        foreach ($this->getFields() as $field) {
            $entry = new ResultEntry();
            $entry->setField($field);
            $entry->setValue($data['field_'.$field->getId()]);
            $result->addEntry($entry);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return (string) $this->title;
    }
}
