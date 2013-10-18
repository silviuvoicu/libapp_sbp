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
//use Symfony\Component\Security\Core\Encoder\EncoderFactory;
//use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use BddSBP\ReaderBundle\Entity\Book;
use BddSBP\ReaderBundle\Form\BookType;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use \stdClass;

class BookControllerSpec extends ObjectBehavior
{
     function let(Container $container, Registry $doctrine,EntityManager $entityManager,EntityRepository $repository, 
             Request $request, FormFactory $formFactory, FormBuilder $formBuilder, Form $form, FormView $formView,
             SecurityContext $securityContext,UsernamePasswordToken $token,
             Router $router, Session $session, FlashBag $flashBag)
                {
        $container->get('doctrine')->willReturn($doctrine);
        $container->get('form.factory')->willReturn($formFactory);
        $container->get('request')->willReturn($request);
        $container->get('router')->willReturn($router);
        $container->get('session')->willReturn($session);
//        $container->get('security.encoder_factory')->willReturn($encoderFactory);
        $container->get('security.context')->willReturn($securityContext);
        $session->getFlashBag()->willReturn($flashBag);

        $router->generate(Argument::cetera())->willReturn('url');

        $formFactory->createBuilder(Argument::cetera())->willReturn($formBuilder);
        $formBuilder->getForm(Argument::cetera())->willReturn($form);
        $formFactory->create(Argument::cetera())->willReturn($form);
        $form->createView()->willReturn($formView);

        $doctrine->getManager()->willReturn($entityManager);
        $entityManager->getRepository(Argument::any())->willReturn($repository);
//        $encoderFactory->getEncoder(Argument::any())->willReturn($encoder);
         $token->isAuthenticated()->willReturn(true);
        $securityContext->setToken($token)->willReturn($token);
        
        $securityContext->getToken()->willReturn($token);
        $token->getUser()->willReturn($token);
     
        $this->setContainer($container);
    }
    
    
    function it_is_initializable()
    {
        $this->shouldHaveType('BddSBP\ReaderBundle\Controller\BookController');
    }
    function it_is_of_type_container_aware()
    {
        $this->shouldBeAnInstanceOf('Symfony\Component\DependencyInjection\ContainerAware');
    }
    function its_newAction_should_render_new_form($form, $formView, $formFactory,Book $book, BookType $booktype,SecurityContext $securityContext )
    {
        // $anonToken = new AnonymousToken(uniqid(),'anon.',array());
       
//        var_dump($securityContext->getToken()); die;
        $securityContext->isGranted('ROLE_USER')->willReturn(true);
        
        $formFactory->create($booktype, $book)->willReturn($form);
  
        $form->createView()->willReturn($formView);

        $this->newAction()->shouldReturn(
                               array(
                                 'form' => $formView
                              )
                            );
    }
    function its_createAction_should_save_the_Book_when_form_is_valid($request,$router, $flashBag,$form, $formFactory, $entityManager, Book $book,BookType $booktype) {
        
        $formFactory->create($booktype, $book)->willReturn($form);
        $form->handleRequest($request)->willReturn($form);
        $form->isValid()->willReturn(true);
        $form->getData()->willReturn($book);              
        $entityManager->persist($book)->shouldBeCalled();
        $entityManager->flush()->shouldBeCalled();

        $flashBag->add(
            'success',
            'You added new book into your library'
        )->shouldBeCalled();
        //var_dump($reader->getRoles());die;
       
        $router->generate("books")->willReturn('/books');
        $response = $this->createAction($request);
        $response->shouldBeAnInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse');
        $response->getTargetUrl()->shouldBe("/books");
    }

    function its_createAction_should_render_new_form_when_form_is_invalid($request,$book, $booktype, $flashBag,$form, $formView, $formFactory) {
        $formFactory->create($booktype, $book)->willReturn($form);
        $form->handleRequest($request)->willReturn($form);
        $form->isValid()->willReturn(false);
        $form->createView()->willReturn($formView);
        
        $flashBag->add(
            'errors',
            'Something went wrong. Please correct the book creating form'
        )->shouldBeCalled();
        
        $this->createAction($request)->shouldReturn(
                                         array(
                                           'form' => $formView
                                        )
                                      );
    }
    
     function its_indexAction_should_render_a_list_of_BookObjects($entityManager, $repository, stdClass $object) {
        $entityManager->getRepository(Argument::exact('ReaderBundle:Book'))->willReturn($repository);
        $repository->findAll()->willReturn([$object]);

        $this->indexAction()->shouldReturn(['books' => [$object]]);
    }
    
    function its_showAction_should_render_an_BookObject(stdClass $object, $entityManager, $repository, $form, $formView, $formBuilder, $formFactory) {
        $entityManager->getRepository(Argument::exact('ReaderBundle:Book'))->willReturn($repository);
        $repository->find(1)->willReturn($object);

        $formFactory->createBuilder(Argument::cetera())->willReturn($formBuilder);
        $formBuilder->add('id', 'hidden')->willReturn($formBuilder);
        $formBuilder->getForm()->willReturn($form);
        $form->createView()->willReturn($formView);

        $this->showAction(1)->shouldReturn(
                                 array(
                                   'book' => $object,
                                   'delete_form' => $formView,
                                 )
                              );
    }
    
    function its_showAction_should_redirect_to_homeroute_and_set_a_flash_message_if_book_doesnt_exist($entityManager, $repository, $router,$flashBag) {
        $entityManager->getRepository(Argument::exact('ReaderBundle:Book'))->willReturn($repository);
        $repository->find(2)->willReturn(null);

        $flashBag->add(
            'errors',
            'Book doesn\'t exist'
        )->shouldBeCalled();
        
        $router->generate("home")->willReturn('/');
        $response = $this->showAction(2);
        $response->shouldBeAnInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse');
        $response->getTargetUrl()->shouldBe("/");
        
    }
    
     function its_editAction_should_render_edit_form(stdClass $object, $entityManager, $repository, $form, $formView, $formBuilder, $formFactory,$book,$booktype) {
        $entityManager->getRepository($book)->willReturn($repository);
        $repository->find(1)->willReturn($object);

        $formFactory->create($booktype, $object)->willReturn($form);
        $formFactory->createBuilder(1)->willReturn($formBuilder);

        $formBuilder->add('id', 'hidden')->willReturn($formBuilder);
        $formBuilder->getForm()->willReturn($form);

        $form->createView()->willReturn($formView);
        $form->createView()->willReturn($formView);

        $this->editAction(1)->shouldReturn(
                                 array(
                                   'book' => $object,
                                  'edit_form' => $formView,
                                  'delete_form' => $formView
                                )
                             );
    }
    
    function its_editAction_should_redirect_to_homeroute_and_set_a_flash_message_if_book_doesnt_exist($entityManager, $repository, $router,$flashBag) {
        $entityManager->getRepository(Argument::exact('ReaderBundle:Book'))->willReturn($repository);
        $repository->find(2)->willReturn(null);

        $flashBag->add(
            'errors',
            'Book doesn\'t exist'
        )->shouldBeCalled();
        
        $router->generate("home")->willReturn('/');
        $response = $this->showAction(2);
        $response->shouldBeAnInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse');
        $response->getTargetUrl()->shouldBe("/");
    }
    
    function its_updateAction_should_save_the_BookObject_when_form_is_valid(stdClass $object, $repository, $request, $form, $formBuilder, $formFactory, $entityManager,$router,$flashBag) {
        $entityManager->getRepository(Argument::exact('ReaderBundle:Book'))->willReturn($repository);
        $repository->find(1)->willReturn($object);

        $formFactory->createBuilder(1)->willReturn($formBuilder);
        $formBuilder->add('id', 'hidden')->willReturn($formBuilder);
        $formBuilder->getForm()->willReturn($form);
        $form->handleRequest($request)->willReturn($form);
        $form->submit($request)->willReturn($form);
        $form->isValid()->willReturn(true);

        $entityManager->persist($object)->shouldBecalled();
        $entityManager->flush()->shouldBeCalled();

        
         $flashBag->add(
            'success',
            'Book has been updated'
        )->shouldBeCalled();
        //var_dump($reader->getRoles());die;
       
        $router->generate("books")->willReturn('/books');
        $response = $this->updateAction($request,1);
        $response->shouldBeAnInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse');
        $response->getTargetUrl()->shouldBe("/books");
    }
    
    function its_updateAction_should_redirect_to_homeroute_and_set_a_flash_message_if_book_doesnt_exist($entityManager, $repository, $router,$flashBag,$request) {
        $entityManager->getRepository(Argument::exact('ReaderBundle:Book'))->willReturn($repository);
        $repository->find(2)->willReturn(null);

        $flashBag->add(
            'errors',
            'Book doesn\'t exist'
        )->shouldBeCalled();
        
        $router->generate("home")->willReturn('/');
        $response = $this->updateAction($request,2);
        $response->shouldBeAnInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse');
        $response->getTargetUrl()->shouldBe("/");
    }
    
    function its_updateAction_should_render_edit_form_when_form_is_invalid(stdClass $object, $entityManager, $repository, $request, $form, $formView, $formBuilder, $formFactory) {
        $entityManager->getRepository(Argument::exact('ReaderBundle:Book'))->willReturn($repository);
        $repository->find(1)->willReturn($object);

        $formFactory->create(Argument::type('BddSBP\ReaderBundle\Form\BookType'), $object)->willReturn($form);
        $formFactory->createBuilder(1)->willReturn($formBuilder);
        $formBuilder->add('id', 'hidden')->willReturn($formBuilder);
        $formBuilder->getForm()->willReturn($form);
        $form->handleRequest($request)->willReturn($form);
        $form->submit($request)->willReturn($form);
        $form->isValid()->willReturn(false);
        $form->createView()->willReturn($formView);
        $form->createView()->willReturn($formView);

        $this->updateAction($request, 1)->shouldReturn(
                                             array(
                                               'book' => $object,
                                               'edit_form' => $formView,
                                               'delete_form' => $formView
                                             )
                                          );
    }
    
     function its_deleteAction_should_delete_BookObject(stdClass $object, $repository, $request, $form, $formBuilder, $formFactory, $entityManager,$router) {
        $formFactory->createBuilder(1)->willReturn($formBuilder);
        $formBuilder->add('id', 'hidden')->willReturn($formBuilder);
        $formBuilder->getForm()->willReturn($form);
        $form->handleRequest($request)->willReturn($form);
        $form->submit($request)->willReturn($form);
        $form->isValid()->willReturn(true);

        $entityManager->getRepository(Argument::exact('ReaderBundle:Book'))->willReturn($repository);
        $repository->find(1)->willReturn($object);
        $entityManager->remove($object)->shouldBeCalled();
        $entityManager->flush()->shouldBeCalled();

        $router->generate("books")->willReturn('/books');
        $response = $this->deleteAction($request,1);
        $response->shouldBeAnInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse');
        $response->getTargetUrl()->shouldBe("/books");
    }
    
    function its_deleteAction_should_redirect_to_homeroute_and_set_a_flash_message_if_book_doesnt_exist($request, $entityManager, $repository, $form, $formBuilder, $formFactory,$router,$flashBag) {
        $formFactory->createBuilder(2)->willReturn($formBuilder);
        $formBuilder->add('id', 'hidden')->willReturn($formBuilder);
        $formBuilder->getForm()->willReturn($form);
        $form->handleRequest($request)->willReturn($form);
        $form->submit($request)->willReturn($form);
        $form->isValid()->willReturn(true);

        $entityManager->getRepository(Argument::exact('ReaderBundle:Book'))->willReturn($repository);
        $repository->find(2)->willReturn(null);

        $flashBag->add(
            'errors',
            'Book doesn\'t exist'
        )->shouldBeCalled();
        
        $router->generate("home")->willReturn('/');
        $response = $this->updateAction($request,2);
        $response->shouldBeAnInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse');
        $response->getTargetUrl()->shouldBe("/");

        
    }
}
