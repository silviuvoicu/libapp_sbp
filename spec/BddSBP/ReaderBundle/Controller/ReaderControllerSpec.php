<?php

namespace spec\BddSBP\ReaderBundle\Controller;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormView;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use BddSBP\ReaderBundle\Entity\Reader;
use BddSBP\ReaderBundle\Form\ReaderType;
use \stdClass;

class ReaderControllerSpec extends ObjectBehavior
{
    
    function let(Container $container, Registry $doctrine,EntityManager $entityManager,EntityRepository $repository, Request $request, FormFactory $formFactory, FormBuilder $formBuilder, Form $form, FormView $formView, Router $router, Session $session, FlashBag $flashBag,EncoderFactory $encoderFactory, MessageDigestPasswordEncoder $encoder) {
        $container->get('doctrine')->willReturn($doctrine);
        $container->get('form.factory')->willReturn($formFactory);
        $container->get('request')->willReturn($request);
        $container->get('router')->willReturn($router);
        $container->get('session')->willReturn($session);
        $container->get('security.encoder_factory')->willReturn($encoderFactory);
       
        $session->getFlashBag()->willReturn($flashBag);

        $router->generate(Argument::cetera())->willReturn('url');

        $formFactory->createBuilder(Argument::cetera())->willReturn($formBuilder);
        $formBuilder->getForm(Argument::cetera())->willReturn($form);
        $formFactory->create(Argument::cetera())->willReturn($form);
        $form->createView()->willReturn($formView);

        $doctrine->getManager()->willReturn($entityManager);
        $entityManager->getRepository(Argument::any())->willReturn($repository);
        $encoderFactory->getEncoder(Argument::any())->willReturn($encoder);
     
        $this->setContainer($container);
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('BddSBP\ReaderBundle\Controller\ReaderController');
    }
    
    function it_is_of_type_container_aware() {
        $this->shouldBeAnInstanceOf('Symfony\Component\DependencyInjection\ContainerAware');
    }
    
    function its_newAction_should_render_new_form( $form, $formView, $formFactory, Reader $reader,ReaderType $readertype) {
       $formFactory->create($readertype, $reader)->willReturn($form);
  
        $form->createView()->willReturn($formView);

        $this->newAction()->shouldReturn(
                               array(
                                 'form' => $formView
                              )
                            );
    }
    
     function its_createAction_should_save_the_Reader_when_form_is_valid($request, $flashBag,$encoderFactory , $encoder,$form, $formFactory, $entityManager, Reader $reader,ReaderType $readertype) {
        $encodedPassword = '';
        $formFactory->create($readertype, $reader)->willReturn($form);
        $form->handleRequest($request)->willReturn($form);
        $form->isValid()->willReturn(true);
        $form->getData()->willReturn($reader);
     
        $reader->setSalt(Argument::any())->shouldBeCalled();
        $encoderFactory->getEncoder($reader)->willReturn($encoder); 
        $reader->getPassword()->shouldBeCalled();
        $reader->getSalt()->shouldBeCalled();
        $encoder->encodePassword(
                    Argument::any(),
                    Argument::any()
                )->shouldBeCalled()->willReturn($encodedPassword);
        $reader->setPassword($encodedPassword)->shouldBeCalled();
         
        $entityManager->persist($reader)->shouldBeCalled();
        $entityManager->flush()->shouldBeCalled();

        $flashBag->add(
            'success',
            'You registered !'
        )->shouldBeCalled();
        
        $response = $this->createAction($request);
        $response->shouldBeAnInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse');
    }

    
    
}
