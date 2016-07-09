<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Bundle\FormBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Endroid\Bundle\FormBundle\Event\FormSuccessEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class StoreResultListener implements EventSubscriberInterface
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * Creates a new instance.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(FormSuccessEvent::NAME => 'storeResult');
    }

    /**
     * Sends a confirmation mail.
     *
     * @param FormSuccessEvent $event
     */
    public function storeResult(FormSuccessEvent $event)
    {
        $result = $event->getResult();
        $form = $result->getForm();

        if (!$form->getStoreResults()) {
            return;
        }

        $this->entityManager->persist($result);
        $this->entityManager->flush();
    }
}
