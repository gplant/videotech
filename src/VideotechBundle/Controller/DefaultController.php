<?php

namespace VideotechBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use VideotechBundle\Entity\Film;

class DefaultController extends Controller
{
    public function indexAction()
    {
        // Get entity Manager
        $em = $this->get('doctrine')->getManager();



	$nbFilm = count($em->getRepository('VideotechBundle:Film')
                   ->findAll());
        return $this->render('@Videotech/Default/index.html.twig',  array(
	    "nbFilm" => $nbFilm
        ));
    }
}
