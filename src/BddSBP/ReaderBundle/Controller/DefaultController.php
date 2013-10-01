<?php

namespace BddSBP\ReaderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ReaderBundle:Default:index.html.twig');
    }
    
    public function access_deniedAction()
    {
         return $this->render('ReaderBundle:Default:access_denied.html.twig');
    }        
}
