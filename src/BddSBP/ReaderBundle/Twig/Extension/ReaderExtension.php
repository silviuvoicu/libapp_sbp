<?php

namespace BddSBP\ReaderBundle\Twig\Extension;

use Symfony\Component\Security\Core\SecurityContext;

class ReaderExtension extends \Twig_Extension
{
    private $securityContext;
    public function getName() {
        return 'reader';
    }
    
    public function getFunctions() {
        return array(
           'top_menu' => new \Twig_Function_Method($this, 'topmenu')
        );
    }
    
    public function topmenu()
    {  
       $reader = $this->securityContext->getToken()->getUser();
       if ($reader === 'anon.'){
         return 'Register';
       } else{
           return "Welcome, $reader";
       } 
    }        

    public function __construct(SecurityContext $securityContext)
    {
        $this->securityContext = $securityContext;
    }
}