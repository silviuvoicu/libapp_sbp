<?php
namespace BddSBP\ReaderBundle\Authentication\Handler;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;


class ReaderLoginSuccessHandler implements AuthenticationSuccessHandlerInterface{
    
    private $security;
    
    private $session;
    
    private $router;
    
    public function __construct(SecurityContext $security, Session $session,Router $router)
   {
        $this->security = $security;
        $this->session = $session;
        $this->router = $router;
    }


    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        if ($this->security->getToken()->getUser()!='anon.')
        {
            $this->session->getFlashBag()->add('success', "You are logged in!");
        } 
        $response = new RedirectResponse($this->router->generate('home'));
        return $response;
    }    
}


