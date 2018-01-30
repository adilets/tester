<?php
namespace AppBundle\Service;

use AppBundle\Entity\Solution;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\Problem as ProblemEntity;

class Problem {

    const TESTS_DIR = '../web/uploads/problems/tests/';

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

    public function getStatement(ProblemEntity $problem) {
        $problemId = $problem->getId();
        $path = self::TESTS_DIR . $problemId . '/';

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
