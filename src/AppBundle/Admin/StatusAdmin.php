<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

class StatusAdmin extends AbstractAdmin
{
	protected function configureRoutes(RouteCollection $collection)
	{
		$collection->remove('delete');
	}

    public function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('title')
            ->add('description')
            ->add('reason')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
	        ->add('title')
	        ->add('description')
	        ->add('reason')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
	        ->add('title')
	        ->add('description')
	        ->add('reason')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
	        ->add('id')
	        ->add('title')
	        ->add('description')
	        ->add('reason')
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