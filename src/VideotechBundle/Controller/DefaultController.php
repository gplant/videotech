<?php

namespace VideotechBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('VideotechBundle:Default:index.html.twig');
    }
}
