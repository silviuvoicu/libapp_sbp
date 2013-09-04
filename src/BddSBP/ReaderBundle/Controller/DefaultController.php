<?php

namespace BddSBP\ReaderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ReaderBundle:Default:index.html.twig');
    }
}
