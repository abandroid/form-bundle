<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\FormBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class FieldAdmin extends AbstractAdmin
{
    /**
     * {@inheritdoc}
     */
    protected $datagridValues = [
        '_sort_by' => 'position',
        '_sort_order' => 'ASC',
    ];

    /**
     * {@inheritdoc}
     */
    public function getParentAssociationMapping()
    {
        return 'form';
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $field = $this->getSubject();

        $formMapper
            ->with('General')
                ->add('title')
            ->end()
        ;

        $adminAttributes = $formMapper->getFormBuilder()->getAttribute('sonata_admin');
        $editInline = isset($adminAttributes['edit']) && $adminAttributes['edit'] == 'inline';

        if ($editInline) {
            $formMapper->add('position');

            return;
        }

        $formMapper
            ->with('General')
                ->add('default')
            ->end()
            ->with('Validation')
                ->add('required')
            ->end()
        ;

        $field->buildAdmin($formMapper);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('required', 'boolean', ['editable' => true])
        ;
    }
}
