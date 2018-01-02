<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\FormBundle\EventListener;

use Endroid\FormBundle\Event\FormSuccessEvent;
use Swift_Mailer;
use Swift_MemorySpool;
use Swift_Message;
use Swift_Transport_SpoolTransport;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SendConfirmationMessageListener implements EventSubscriberInterface
{
    /**
     * @var TwigEngine
     */
    protected $twigEngine;

    /**
     * @var Swift_Mailer
     */
    protected $swiftMailer;

    /**
     * @var
     */
    protected $swiftMailerTransport;

    /**
     * Creates a new instance.
     *
     * @param TwigEngine   $twigEngine
     * @param Swift_Mailer $swiftMailer
     * @param $swiftMailerTransport
     */
    public function __construct(TwigEngine $twigEngine, Swift_Mailer $swiftMailer, $swiftMailerTransport)
    {
        $this->twigEngine = $twigEngine;
        $this->swiftMailer = $swiftMailer;
        $this->swiftMailerTransport = $swiftMailerTransport;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [FormSuccessEvent::NAME => 'sendConfirmationMail'];
    }

    /**
     * Sends a confirmation mail.
     *
     * @param FormSuccessEvent $event
     */
    public function sendConfirmationMail(FormSuccessEvent $event)
    {
        $result = $event->getResult();
        $form = $result->getForm();

        if (!$form->getSendMail()) {
            return;
        }

        $message = Swift_Message::newInstance();
        $message
            ->setSubject($form->getMailSubject())
            ->setTo([$form->getMailRecipientEmail() => $form->getMailRecipientName()])
            ->setBody($this->twigEngine->render('EndroidFormBundle:Mail:confirmation_message.html.twig', [
                'content' => $form->getMailBody(),
                'entries' => $result->getEntries(),
            ]), 'text/html')
        ;

        if (null != $form->getMailSenderName() && null != $form->getMailSenderEmail()) {
            $message->setFrom([$form->getMailSenderEmail() => $form->getMailSenderName()]);
        }

        $this->swiftMailer->send($message);

        $transport = $this->swiftMailer->getTransport();
        if ($transport instanceof Swift_Transport_SpoolTransport) {
            $spool = $transport->getSpool();
            if ($spool instanceof Swift_MemorySpool) {
                $spool->flushQueue($this->swiftMailerTransport);
            }
        }
    }
}
