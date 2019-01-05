<?php
namespace AppBundle\EventListener;

use AppBundle\Entity\News;
use AppBundle\Entity\Problem;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Subscriber implements EventSubscriber
{
    private $container;

    /**
     * Subscriber constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getSubscribedEvents()
    {
        return array(
            'postPersist',
            'postUpdate',
            'prePersist',
            'preUpdate'
        );
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->post($args);
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->post($args);
    }

    public function preUpdate(LifecycleEventArgs $args) {
        $this->pre($args);
    }

    public function prePersist(LifecycleEventArgs $args) {
        $this->pre($args);
    }

    public function pre(LifecycleEventArgs $args) {
        $entity = $args->getObject();
        if ($entity instanceof News) {
            $entity->setUpdatedAt(new \DateTime());

            if ($entity->getCreatedAt() == null) {
                $entity->setCreatedAt(new \DateTime());
                $user = $this->container->get('security.token_storage')->getToken()->getUser();
                $entity->setAuthor($user);
            }
        }
    }

    public function post(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof Problem) {
            $problemService = $this->container->get("app.service.problem");
            $problemService->extractTests($entity);
        }
    }
}