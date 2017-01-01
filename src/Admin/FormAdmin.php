<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Bundle\FormBundle\Admin;

use Endroid\Bundle\FormBundle\Entity\Form;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class FormAdmin extends AbstractAdmin
{
    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
                ->add('title')
                ->add('successAction', 'sonata_type_translatable_choice', [
                    'required' => true,
                    'choices' => [
                        'Redirect to URL' => Form::SUCCESS_ACTION_REDIRECT,
                        'Show message' => Form::SUCCESS_ACTION_MESSAGE,
                    ],
                ])
                ->add('successUrl')
                ->add('successMessage')
            ->end()
            ->with('Results')
                ->add('storeResults')
            ->end()
            ->with('Mail')
                ->add('sendMail')
                ->add('mailRecipientName')
                ->add('mailRecipientEmail')
                ->add('mailSubject')
                ->add('mailBody')
                ->add('mailSenderName')
                ->add('mailSenderEmail')
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormBuilder()
    {
        /** @var Form $form */
        $form = $this->getSubject();
        $data = $this->getRequest()->request->get($this->getUniqid());

        if ($form && $data) {
            if (isset($data['successAction'])) {
                $this->formOptions['validation_groups'][] = $data['successAction'];
            }
            if (isset($data['sendMail']) && $data['sendMail']) {
                $this->formOptions['validation_groups'][] = 'email';
            }
        }

        return parent::getFormBuilder();
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('results', 'string', ['label' => 'form.admin.form.list.results', 'template' => 'EndroidFormBundle:Admin:results.html.twig'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('results', $this->getRouterIdParameter().'/results');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureTabMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        // Only allow management of fields when the form exists
        if (!$this->getSubject() || !$this->getSubject()->getId()) {
            return;
        }

        $menu->addChild(
            'form.admin.field.list.label',
            ['uri' => $this->getChild('endroid_form.admin.field_admin')->generateUrl('list', ['id' => $this->getSubject()->getId()])]
        );
    }
}
