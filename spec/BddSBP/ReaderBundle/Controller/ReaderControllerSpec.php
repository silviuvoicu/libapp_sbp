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
use \stdClass;

class ReaderControllerSpec extends ObjectBehavior
{
    
    function let(Container $container, Registry $doctrine, EntityRepository $repository, EntityManager $entityManager, Request $request, FormFactory $formFactory, FormBuilder $formBuilder, Form $form, FormView $formView, Router $router, $reader, $readertype) {
        $container->get('doctrine')->willReturn($doctrine);
        $container->get('form.factory')->willReturn($formFactory);
        $container->get('request')->willReturn($request);
        $container->get('router')->willReturn($router);

        $router->generate(Argument::cetera())->willReturn('url');

        $formFactory->createBuilder(Argument::cetera())->willReturn($formBuilder);
        $formBuilder->getForm(Argument::cetera())->willReturn($form);
        $formFactory->create(Argument::cetera())->willReturn($form);
        $form->createView()->willReturn($formView);

        $doctrine->getManager()->willReturn($entityManager);
        $entityManager->getRepository(Argument::any())->willReturn($repository);
        
        $reader->beADoubleOf('BddSBP\ReaderBundle\Entity\Reader');
        $readertype->beADoubleOf('BddSBP\ReaderBundle\Form\ReaderType');

        $this->setContainer($container);
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('BddSBP\ReaderBundle\Controller\ReaderController');
    }
    
    function it_is_of_type_container_aware() {
        $this->shouldBeAnInstanceOf('Symfony\Component\DependencyInjection\ContainerAware');
    }
    
    function its_newAction_should_render_new_form( $form, $formView, $formFactory,$reader, $readertype) {


        $formFactory->create($readertype, $reader)->willReturn($form);
    //    $formFactory->create(Argument::type('\BddSBP\ReaderBundle\Form\ReaderType'), Argument::type('\BddSBP\ReaderBundle\Entity\Reader'))->willReturn($form);
        $form->createView()->willReturn($formView);

        $this->newAction()->shouldReturn(
                               array(
                                 'form' => $formView
                              )
                            );
    }
    
}
