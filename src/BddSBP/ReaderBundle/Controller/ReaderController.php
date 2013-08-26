<?php

namespace BddSBP\ReaderBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BddSBP\ReaderBundle\Entity\Reader;
use BddSBP\ReaderBundle\Form\ReaderType;

class ReaderController extends Controller
{
    /**
     * Displays a form to create a new Reader entity.
     *
     * @Route("/register", name="register")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $reader = new Reader();
        $form  = $this->createForm(new ReaderType(), $reader);
        
        return array(
            'form'   => $form->createView()
        );
      
    }
}
