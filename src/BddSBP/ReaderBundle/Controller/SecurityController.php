<?php

namespace BddSBP\ReaderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

class SecurityController extends Controller
{
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        if ($error)
        {
             $this->container->get('session')->getFlashBag()->add(
            'errors',
            'Something went wrong. Please provide correct credentials'
             );
        } 
//        elseif()
//        {
//             $this->container->get('session')->getFlashBag()->add(
//            'success',
//            'You\'re logged in '
//           );     
//        }
        
        return $this->render(
            'ReaderBundle:Security:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                //'error'         => $error,
            )
        );
    }
}