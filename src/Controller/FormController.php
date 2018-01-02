<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\FormBundle\Controller;

use Endroid\FormBundle\Builder\FormBuilder;
use Endroid\FormBundle\Entity\Form;
use Endroid\FormBundle\Event\FormSuccessEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FormController extends Controller
{
    /**
     * @Template()
     *
     * @param Form $form
     *
     * @return array|Response
     */
    public function showAction(Form $form)
    {
        /** @var Request $request */
        $request = $this->get('request_stack')->getMasterRequest();

        if ($request->query->get('success')) {
            return $this->render('EndroidFormBundle:Form:success.html.twig', [
                'successMessage' => $form->getSuccessMessage(),
            ]);
        }

        $symfonyForm = $this->getFormBuilder()->createForm($form);
        $symfonyForm->handleRequest($request);

        if ($symfonyForm->isSubmitted() && $symfonyForm->isValid()) {
            $result = $form->createResult($symfonyForm->getData());
            $this->getEventDispatcher()->dispatch(FormSuccessEvent::NAME, new FormSuccessEvent($result));
            if (Form::SUCCESS_ACTION_REDIRECT == $form->getSuccessAction()) {
                $redirectUrl = $form->getSuccessUrl();
            } else {
                $redirectUrl = $this->generateUrl($request->get('_route'), array_merge($request->get('_route_params'), ['success' => true]));
            }
            $redirectResponse = new RedirectResponse($redirectUrl);
            $redirectResponse->send();
        }

        return [
            'form' => $symfonyForm->createView(),
        ];
    }

    /**
     * Returns the event dispatcher.
     *
     * @return EventDispatcher
     */
    protected function getEventDispatcher()
    {
        return $this->get('event_dispatcher');
    }

    /**
     * Returns the form builder.
     *
     * @return FormBuilder
     */
    protected function getFormBuilder()
    {
        return $this->get('endroid_form.builder.form_builder');
    }
}
