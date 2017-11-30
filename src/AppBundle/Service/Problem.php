<?php
namespace AppBundle\Service;

use AppBundle\Entity\Problem as ProblemEntity;

class Problem {
	public function check(ProblemEntity $problem, $formData) {
		dump($problem);
		dump($formData);
		die();
	}
}