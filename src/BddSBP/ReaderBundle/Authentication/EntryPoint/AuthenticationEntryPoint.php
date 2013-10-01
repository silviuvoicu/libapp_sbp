<?php


namespace BddSBP\ReaderBundle\Authentication\EntryPoint;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;


class AuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    
    private $router;

    public function __construct($router){
        $this->router = $router;

    }
    

    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
//       $session = $request->getSession();

        //I am choosing to set a FlashBag message with my own custom message.
        //Alternatively, you could use AuthenticaionException's generic message 
        //by calling $authException->getMessage()
//        $session->getFlashBag()->add('warning', 'You must be logged in to access that page');

        return new RedirectResponse($this->router->generate('access_denied'));
    }
}
