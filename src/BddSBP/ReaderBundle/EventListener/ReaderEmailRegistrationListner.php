<?php

namespace BddSBP\ReaderBundle\EventListener;
use BddSBP\ReaderBundle\Event\ReaderEmailRegistrationEvent;
use  \Swift_Mailer as Mailer;
class ReaderEmailRegistrationListner
{
    private $mailer;
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }
    
    public function onReaderEmailRegistrationEvent(ReaderEmailRegistrationEvent $event)
    {
        $reader = $event->getReader();
        $message = \Swift_Message::newInstance()
           ->setSubject('Libapp_sbp registration')
           ->setFrom('test@test.com')
           ->setTo($reader->getEmail())
           ->setBody('Welcome to the Libapp_sbp');
      $this->mailer->send($message);  
    }         
}
