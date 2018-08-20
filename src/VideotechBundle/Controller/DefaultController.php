<?php

namespace VideotechBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Videotech/Default/index.html.twig');
    }
}
