<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ProblemAdmin extends AbstractAdmin
{
    public function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('title')
            ->add('description')
            ->add('timeLimit')
            ->add('memoryLimit')
            ->add('topic')
            ->add('public')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title')
            ->add('description', null, ['attr' => ['class' => 'tinymce']])
            ->add('timeLimit')
            ->add('memoryLimit')
            ->add('topic')
            ->add('public')
            ->add('file', 'file', [
                'required' => $this->isCurrentRoute('create'),
                'attr' => ['accept' => '.zip']
            ])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('title')
            ->add('description')
            ->add('timeLimit')
            ->add('memoryLimit')
            ->add('topic')
            ->add('public')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('title')
            ->add('timeLimit')
            ->add('memoryLimit')
            ->add('topic')
            ->add('public', null, ['editable' => true])
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }
}