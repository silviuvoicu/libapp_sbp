<?php

namespace BddSBP\ReaderBundle\Twig\Extension;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class ReaderExtension extends \Twig_Extension
{
    private $securityContext;
    private $router;
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
       $result= '<ul class="nav pull-left">
                    <li>
                      <a href="'.$this->router->generate('books').'">Books</a>
                    </li>
              </ul>';
       $result .= ' <ul class="nav pull-right">';
       if ($reader === 'anon.'){
         $result.=" <li>
                      <a href='".$this->router->generate('register'). "'>Register</a>
                   </li>
                   <li class=\"divider-vertical\"></li>
                   <li>
                      <a href='".$this->router->generate('reader_login')."'>Login</a>
                  </li>
              </ul>";
         return $result;
       } else{
           $result.= "<li>
                       <a href='".$this->router->generate('home')."'>Welcome, ".$reader."</a>
                  </li>
                  <li class=\"divider-vertical\"></li>
                  <li>
                    <a href='".$this->router->generate('logout')."'>Logout</a>
                  </li>
               </ul>";
           return $result;
       } 
    }        

    public function __construct(SecurityContext $securityContext, Router $router)
    {
        $this->securityContext = $securityContext;
        $this->router = $router;
    }
}