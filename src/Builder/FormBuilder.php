<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\FormBundle\Builder;

use Endroid\FormBundle\Entity\Form;
use Endroid\FormBundle\Form\Type\FormType;
use Symfony\Component\Form\Form as SymfonyForm;
use Symfony\Component\Form\FormFactoryInterface;

class FormBuilder
{
    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * Sets the form factory.
     *
     * @param FormFactoryInterface $formFactory
     *
     * @return $this
     */
    public function setFormFactory(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;

        return $this;
    }

    /**
     * Creates the form.
     *
     * @param Form $form
     *
     * @return SymfonyForm
     */
    public function createForm(Form $form)
    {
        $formBuilder = $this->formFactory->createBuilder(FormType::class);
        $form->build($formBuilder);

        $form = $formBuilder->getForm();

        return $form;
    }
}
