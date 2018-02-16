<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class RatingController extends Controller {

    /**
    * @Route("/rating", name="rating")
    * @Method("GET")
    */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $rating = $em->getRepository('AppBundle:Solution')->getGeneralRating();

        return $this->render('@App/Rating/index.html.twig', array(
            'rating' => $rating
        ));
    }
}