<?php
namespace AppBundle\Service;

use AppBundle\Entity\Solution;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\Problem as ProblemEntity;

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
        $utilityPath = $this->container->getParameter('utility_path');
        if (chdir($utilityPath)) {
            exec("sudo {$utilityPath}/main -solution-id={$solution->getId()} > /dev/null &");
        }
    }

    public function getStatement(ProblemEntity $problem) {
        $problemId = $problem->getId();
        $path = $this->container->getParameter('utility_path') . "/tests/$problemId/";

        $statement = [];

        $inFileName = $path . '1.in';
        $outFileName = $path . '1.out';

        if (is_file($inFileName)) {
            $statement['in'] = file_get_contents($inFileName);
        }

        if (is_file($outFileName)) {
            $statement['out'] = file_get_contents($outFileName);
        }

        return $statement;
    }
}
