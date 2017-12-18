<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Language;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class LanguageAdmin extends AbstractAdmin
{
    public function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('title')
            ->add('description')
            ->add('command')
            ->add('type')
            ->add('createdAt')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
	        ->add('title')
	        ->add('description')
	        ->add('command')
	        ->add('type', 'choice', [
	        	'choices' => [
			        'Compiler' => Language::COMPILER,
	        		'Interpreter' => Language::INTERPRETER
		        ],
		        'expanded' => true,
				'required' => true
	        ])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
	        ->add('title')
	        ->add('description')
	        ->add('command')
	        ->add('type')
	        ->add('createdAt')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('title')
            ->add('description')
            ->add('command')
            ->add('type')
            ->add('createdAt')
            ->add('_action', null, array(
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => []
                ]
            ))
        ;
    }
}