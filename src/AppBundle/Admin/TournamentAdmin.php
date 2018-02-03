<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class TournamentAdmin extends AbstractAdmin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('title')
            ->add('description')
            ->add('start')
            ->add('end')
            ->add('isPublic')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('title')
            ->add('description')
            ->add('start')
            ->add('end')
            ->add('isPublic', null, ['editable' => true])
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                ),
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title')
            ->add('description')
            ->add('start', 'sonata_type_datetime_picker')
            ->add('end', 'sonata_type_datetime_picker')
            ->add('isPublic')
            ->add('problems', 'sonata_type_model', [
                'multiple' => true,
                'btn_add' => false,
                'query' => $this
                    ->modelManager
                    ->getEntityManager('AppBundle\Entity\Problem')
                    ->createQueryBuilder('p')
                    ->select('p')
                    ->from('AppBundle:Problem', 'p')
                    ->where('p.public = FALSE')
            ])
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('title')
            ->add('description')
            ->add('start')
            ->add('end')
            ->add('isPublic')
            ->add('problems')
        ;
    }
}
