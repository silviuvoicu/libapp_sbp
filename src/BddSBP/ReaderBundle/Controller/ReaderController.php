<?php

namespace BddSBP\ReaderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ReaderController extends Controller
{
    public function newAction() {
       
        return $this->render('ReaderBundle:Reader:new.html.twig');
        
    }
}
