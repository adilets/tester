<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/users")
 */
class UsersController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->findBy([
            'enabled' => true
        ]);

        return $this->render('AppBundle:Users:index.html.twig', array(
            'users' => $users
        ));
    }

    /**
     * @Route("/{username}", name="user")
     * @ParamConverter("user", class="AppBundle:User", options={"username" = "username"})
     * @param User $user
     *
     * @return Response
     */
    public function userAction(User $user)
    {
        if ($user == $this->getUser()) {
            return $this->redirectToRoute('fos_user_profile_show');
        }

        return $this->render('AppBundle:Users:user.html.twig', array(
            'user' => $user
        ));
    }

    /**
     * @Route("/{username}/card", name="user_card")
     * @ParamConverter("user", class="AppBundle:User", options={"username" = "username"})
     * @param User $user
     *
     * @return Response
     */
    public function userCardAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();

        $problems = $em->getRepository('AppBundle:Problem')->findBy([
            'public' => true
        ]);

        $problemsResolved = $em->getRepository('AppBundle:Problem')->getResolved($user);

        return $this->render('AppBundle:Users:card.html.twig', array(
            'user' => $user,
            'problems' => $problems,
            'problemsResolved' => $problemsResolved
        ));
    }

}
