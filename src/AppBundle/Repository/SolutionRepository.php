<?php

namespace AppBundle\Repository;
use AppBundle\Entity\Problem;
use AppBundle\Entity\Tournament;
use AppBundle\Entity\User;
use AppBundle\Model\Rating\UserRating;
use Doctrine\DBAL\DBALException;

/**
 * SolutionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SolutionRepository extends \Doctrine\ORM\EntityRepository
{
    public function getGeneralRating() {
        $solvedQuery = "SELECT user_id, count(DISTINCT problem_id) as count FROM solution WHERE status_id = 1 GROUP BY user_id ORDER BY user_id;";
        $allSentQuery = "SELECT user_id, count(*) as count FROM solution GROUP BY user_id ORDER BY user_id;";

        $connection = $this->getEntityManager()->getConnection();
        try {
            $solvedQueryStmt = $connection->prepare($solvedQuery);
            $solvedQueryStmt->execute();
            $allSentQueryStmt = $connection->prepare($allSentQuery);
            $allSentQueryStmt->execute();

            $rating = [];

            foreach ($solvedQueryStmt->fetchAll() as $solved) {
                $rating[$solved['user_id']]['solved'] = $solved['count'];
            }

            foreach ($allSentQueryStmt->fetchAll() as $all) {
                $rating[$all['user_id']]['all'] = $all['count'];
            }

            $userRepository = $this->getEntityManager()->getRepository('AppBundle:User');
            $ratingObjects = [];
            foreach ($rating as $userId => $data) {
                $ratingObjects[] = new UserRating($userRepository->find($userId), $data['solved'], $data['all']);
            }

            uasort($ratingObjects, function($value1, $value2) {
                /**
                 * @var UserRating $value1
                 * @var UserRating $value2
                 */
                if ($value1->getSolvedProblemCount() > $value2->getSolvedProblemCount() ||
                    (
                        $value1->getSolvedProblemCount() == $value2->getSolvedProblemCount() &&
                        $value1->getAllSentProblemCount() < $value2->getAllSentProblemCount())
                ) {
                    return -1;
                }
                return 1;
            });

            return $ratingObjects;
        } catch (DBALException $e) {
            return [];
        }
    }

    public function getUserRating(User $user) {
        $solvedQuery = "SELECT user_id, count(DISTINCT problem_id) as count FROM solution WHERE status_id = 1 AND user_id = :user_id";
        $allSentQuery = "SELECT user_id, count(*) as count FROM solution WHERE user_id = :user_id;";

        $connection = $this->getEntityManager()->getConnection();
        try {
            $solvedQueryStmt = $connection->prepare($solvedQuery);
            $solvedQueryStmt->execute(["user_id" => $user->getId()]);
            $allSentQueryStmt = $connection->prepare($allSentQuery);
            $allSentQueryStmt->execute(["user_id" => $user->getId()]);

            return new UserRating($user, $solvedQueryStmt->fetch()["count"], $allSentQueryStmt->fetch()["count"]);
        } catch (DBALException $e) {
            return [];
        }
    }

    public function getTournamentRating(Tournament $tournament)
    {
        $sql = "SELECT user_id, problem_id, status_id, MAX(created_at) AS sent_time, count(*) AS count FROM solution WHERE tournament_id = :tournament_id GROUP BY user_id, problem_id, status_id ORDER BY sent_time;";
        try {
            $stmt = $this->getEntityManager()->getConnection()
                ->prepare($sql);
            $stmt->execute(array('tournament_id' => $tournament->getId()));
            return $stmt->fetchAll();
        } catch (DBALException $e) {
            return [];
        }

    }

    public function getProblemSolutionsCount(Problem $problem) {
        $qb = $this->createQueryBuilder('s');

        $solutionsCount = $qb
            ->select('COUNT(s) AS solutions')
            ->where('s.problem = :problem')
            ->setParameter('problem', $problem)
            ->getQuery()->getResult();

        return $solutionsCount[0]['solutions'];
    }

    public function getProblemAcceptedSolutionsCount(Problem $problem) {
        $qb = $this->createQueryBuilder('s');

        $solutionsCount = $qb
            ->select('COUNT(s) AS solutions')
            ->where('s.problem = :problem')
            ->andWhere('s.status = 1')
            ->setParameter('problem', $problem)
            ->getQuery()->getResult();

        return $solutionsCount[0]['solutions'];
    }

    public function getProblemSolutionsSentUsersCount(Problem $problem) {
        $sql = <<<SQL
SELECT COUNT(*) AS users
FROM (
  SELECT (s.user_id)
  FROM solution AS s
  WHERE s.problem_id = :problem_id
  GROUP BY s.user_id
) AS x;
SQL;

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute(['problem_id' => $problem->getId()]);
        $usersCount = $stmt->fetchAll();

        return $usersCount[0]['users'];
    }

    public function getProblemSolvedUsersCount(Problem $problem) {
        $sql = <<<SQL
SELECT COUNT(*) AS users
FROM (
  SELECT (s.user_id)
  FROM solution AS s
  WHERE s.problem_id = :problem_id AND s.status_id = 1
  GROUP BY s.user_id
) AS x;
SQL;

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute(['problem_id' => $problem->getId()]);
        $usersCount = $stmt->fetchAll();

        return $usersCount[0]['users'];
    }
}
