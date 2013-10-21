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
use BddSBP\ReaderBundle\Event\ReaderEmailRegistrationEvent;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

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
        
//        var_dump($form->createView());die;
        
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
       
        $form = $this->createForm(new ReaderType(), $reader);
        $form->handleRequest($request);

        if ($form->isValid()) {
           $reader = $form->getData();
       
            $reader->setSalt(md5(time()));

            $encoder = $this->container->get('security.encoder_factory')->getEncoder($reader);
            $encodedPassword = $encoder->encodePassword(
                    $reader->getPassword(),
                    $reader->getSalt()
                );
            $reader->setPassword($encodedPassword);

            $em = $this->container->get('doctrine')->getManager();
            $em->persist($reader);
            $em->flush();
            $this->container->get('session')->getFlashBag()->add(
            'success',
            'You registered !'
           );
          // var_dump($reader->getRoles());die;
            $token = new UsernamePasswordToken($reader, $reader->getPassword(), 'readers', $reader->getRoles());
            $this->container->get('security.context')->setToken($token);
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch('reader.email.registration', new ReaderEmailRegistrationEvent($reader));
            $url = $this->container->get('router')->generate('home');
            return $this->redirect($url);
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

