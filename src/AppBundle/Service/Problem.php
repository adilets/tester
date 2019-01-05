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
        $utilityPath = $this->getUtilityPath();
        exec("cd {$utilityPath} && sudo ./main -solution-id={$solution->getId()} > /dev/null &");
    }

    public function getStatement(ProblemEntity $problem) {
        $problemId = $problem->getId();
        $path = $this->container->getParameter('utility_path') . "/tests/$problemId/";
//        var_dump($path);die();
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

    public function extractTests(ProblemEntity $problem) {
        if (null === $problem->getFile()) {
            return;
        }
        $zipper = new \ZipArchive();
        $tests = $zipper->open($problem->getFile());

        if ($tests === true) {
            $testsPath = $this->getTestsPath($problem->getId());
            $zipper->extractTo($testsPath);
            $zipper->close();

            shell_exec("sudo /bin/cp -fr {$testsPath} {$this->getChrootPath()}/utility/tests");
            shell_exec("sudo /bin/cp -fr {$this->getCheckerPath()} {$this->getChrootPath()}/utility/checkers/checker_{$problem->getId()}");
        }
    }

    private function getUtilityPath() {
        return $this->container->getParameter('utility_path');
    }

    private function getTestsPath($problemId) {
        return $this->getUtilityPath(). "/tests/$problemId/";
    }

    private function getCheckerPath() {
        return $this->getUtilityPath() . "/checkers/checker_1";
    }

    private function getChrootPath() {
        return $this->container->getParameter('chroot_path');
    }
}
