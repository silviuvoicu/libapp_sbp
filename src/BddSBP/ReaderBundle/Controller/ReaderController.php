<?php

namespace BddSBP\ReaderBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    
    /**
     * Register a new reader (create a new reader entity)
     * 
     * @Route("/readers", name="reader_create")
     * @Method("POST")
     * @Template("ReaderBundle:Reader:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $reader  = new Reader();
        //var_dump($reader);  die;
        $form = $this->createForm(new ReaderType(), $reader);
        $form->handleRequest($request);

        if ($form->isValid()) {
           $reader = $form->getData();
       
            $reader->setSalt(md5(time()));
//
            $encoder = $this->container->get('security.encoder_factory')->getEncoder($reader);
            $encodedPassword = $encoder->encodePassword(
                    $reader->getPassword(),
                    $reader->getSalt()
                );
            $reader->setPassword($encodedPassword);
////
//            
            $em = $this->container->get('doctrine')->getManager();
            $em->persist($reader);
            $em->flush();
            $this->container->get('session')->getFlashBag()->add(
            'success',
            'You registered !'
           );
            return $this->redirect($this->generateUrl('home'));
        }
         $this->container->get('session')->getFlashBag()->add(
            'errors',
            'Something went wrong. Please correct the registration form'
        );
        return array(
            'form'   => $form->createView(),
        );
    }

}

