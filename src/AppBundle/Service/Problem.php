<?php
namespace AppBundle\Service;

use AppBundle\Entity\Solution;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Problem {

    private $container;
    /**
     * Problem constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function runChecker(Solution $solution) {
        $utilityPath = $this->container->get('kernel')->getRootDir() .'/../utility';
        exec("cd {$utilityPath} && ./main -solution-id={$solution->getId()} > /dev/null &");
    }
}