<?php
namespace AppBundle\Service;

use AppBundle\Entity\Problem as ProblemEntity;
use AppBundle\Entity\Solution;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class Problem {

    private $em;

    /**
     * Problem constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function check(User $user, ProblemEntity $problem, $formData, $path) {
		$this->compile($user, $problem, $formData, $path);
	}

	private function compile(User $user, ProblemEntity $problem, $formData, $path) {
	    $outputsPath = $path . '/../outputs/';
	    $userPath = $outputsPath . $user->getId() . '/';

        if (!is_dir($userPath)) {
            @mkdir($userPath, 0777, true);
        }
        $codePath = $userPath . $problem->getId() . '.cpp';

        $solution = new Solution();
        $solution->setProblemId($problem->getId());
        $solution->setSourceCode($formData['code_input']);
        $solution->setUserId($user->getId());
        $solution->setLanguageId(1);

	    file_put_contents($codePath, $formData['code_input']);

        exec("cd {$userPath} && g++ -o {$problem->getId()} {$problem->getId()}.cpp 2>&1", $output, $return);

        if ($return != 0) {
            $solution->setStatus(1);
        } else {
            pclose(popen("utility/Main", 'r'));
        }
        $this->em->persist($solution);
        $this->em->flush();
    }
}