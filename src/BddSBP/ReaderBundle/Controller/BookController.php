<?php

namespace BddSBP\ReaderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BddSBP\ReaderBundle\Entity\Book;
use BddSBP\ReaderBundle\Form\BookType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class BookController extends Controller
{
    /**
     * @Route("/new",name="book_new")
     * @Method({"GET"})
     * @Template()
     */
    public function newAction()
    {
         $securityContext = $this->get('security.context');
//         $this->get('ladybug')->log($this->get('security.authentication.provider.anonymous'));
//         var_dump($securityContext);
//         die;
        if (false === $securityContext->isGranted('ROLE_USER')) {
            $url = $this->container->get('router')->generate('access_denied');
          
           return $this->redirect($url); 
        }
        
        $book = new Book();
        $form   = $this->createForm(new BookType(), $book);

        return array(
            'form'   => $form->createView()
        );
    }


   /**
     * Creates a new Book entity.
     *
     * @Route("/", name="book_create")
     * @Method("POST")
     * @Template("ReaderBundle:Book:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $book  = new Book();
        $form = $this->createForm(new BookType(), $book);
        $form->handleRequest($request);
          $securityContext = $this->get('security.context');
//         $this->get('ladybug')->log($this->get('security.authentication.provider.anonymous'));
//         var_dump($securityContext);
//         die;
        if (false === $securityContext->isGranted('ROLE_USER')) {
            $url = $this->container->get('router')->generate('access_denied');
          
           return $this->redirect($url); 
        }
        if ($form->isValid()) {
            $book = $form->getData();
           
            $reader = $this->container->get('security.context')->getToken()->getUser();
            $book->setReader($reader);
            $em = $this->container->get('doctrine')->getManager();
          //   $this->container->get('vich_uploader.storage')->upload($book);
//            var_dump($book);die;
            $em->persist($book);
             
            $em->flush();
//           
//             $this->container->get('vich_uploader.storage')->upload($book);
            
//             ld($book->getImage());
//              ldd($book->getCover());
            
//              ldd($book);
            // creating the ACL
           // ld($book);
            $aclProvider = $this->get('security.acl.provider');
            $objectIdentity = ObjectIdentity::fromDomainObject($book);
            $acl = $aclProvider->createAcl($objectIdentity);

            // retrieving the security identity of the currently logged-in user
            
            $securityIdentity = UserSecurityIdentity::fromAccount($reader);

            // grant owner access
            $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
            $aclProvider->updateAcl($acl);
            $this->container->get('session')->getFlashBag()->add(
            'success',
            'You added new book into your library'
           );
           $url = $this->container->get('router')->generate('books');
           return $this->redirect($url);
        }
        $this->container->get('session')->getFlashBag()->add(
            'errors',
            'Something went wrong. Please correct the book creating form'
           ); 
        return array(
            'form'   => $form->createView(),
        );
    }
    
    /**
     * Lists all Book entities.
     *
     * @Route("/", name="books")
     * @Method("GET")
     * @Template("ReaderBundle:Book:index.html.twig")
     */
    public function indexAction()
    {
        
        $em = $this->container->get('doctrine')->getManager();
        $books=$em->getRepository('ReaderBundle:Book')->findAll();

        return array(
            'books' => $books,
        );
    }

    /**
     * Finds and displays a Book entity.
     *
     * @Route("/{id}", name="book_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->container->get('doctrine')->getManager();

        $book = $em->getRepository('ReaderBundle:Book')->find($id);

        if (!$book) {
            $this->container->get('session')->getFlashBag()->add(
            'errors',
            'Book doesn\'t exist'
           ); 
           $url = $this->container->get('router')->generate('home');
           return $this->redirect($url); 
        }

        $deleteForm = $this->createDeleteForm($id);
       
        return array(
            'book'      => $book,
            'delete_form' => $deleteForm->createView(),
        );
    }
    
  /**
     * Displays a form to edit an existing Book entity.
     *
     * @Route("/{id}/edit", name="book_edit")
     * @Method("GET")
     * @Template()
     */
     public function editAction($id)
    {
        $em = $this->container->get('doctrine')->getManager();

        $book = $em->getRepository('ReaderBundle:Book')->find($id);
         $securityContext = $this->get('security.context');
         //$token = $securityContext->getToken();
        // check for edit access
         
        if (false === $securityContext->isGranted('EDIT', $book)) {
            $url = $this->container->get('router')->generate('access_denied');
          
           return $this->redirect($url); 
        }
        if (!$book) {
            $this->container->get('session')->getFlashBag()->add(
            'errors',
            'Book doesn\'t exist'
           ); 
           $url = $this->container->get('router')->generate('home');
           return $this->redirect($url); 
        }

        $editForm = $this->createForm(new BookType(), $book);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'book'      => $book,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
     }
    
     /**
     * Edits an existing Book entity.
     *
     * @Route("/{id}", name="book_update")
     * @Method("PUT")
     * @Template("ReaderBundle:Book:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        //var_dump($request->isMethod("PUT"));//die;
        
        $em = $this->container->get('doctrine')->getManager();

        $book = $em->getRepository('ReaderBundle:Book')->find($id);
         $securityContext = $this->get('security.context');
         //$token = $securityContext->getToken();
        // check for edit access
         
        if (false === $securityContext->isGranted('EDIT', $book)) {
            $url = $this->container->get('router')->generate('access_denied');
          
           return $this->redirect($url); 
        }

        if (!$book) {
             $this->container->get('session')->getFlashBag()->add(
            'errors',
            'Book doesn\'t exist'
           ); 
           $url = $this->container->get('router')->generate('home');
           return $this->redirect($url); 
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new BookType(), $book);
        $editForm->handleRequest($request);
        $editForm->submit($request);
        //var_dump($editForm->handleRequest($request));//die;
        if ($editForm->isValid()) {
            $em->persist($book);
            $em->flush();
            
            $this->container->get('session')->getFlashBag()->add(
            'success',
            'Book has been updated'
           );
            $url = $this->container->get('router')->generate('books');
            return $this->redirect($url);
        }
//       var_dump($editForm->isValid());       
//       var_dump($editForm );
        return array(
            'book'      => $book,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
     
     /**
     * Deletes a Book entity.
     *
     * @Route("/{id}", name="book_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
//         $securityContext = $this->get('security.context');
//         //$token = $securityContext->getToken();
//        // check for edit access
//         
//        if (false === $securityContext->isGranted('DELETE', $book)) {
//            $url = $this->container->get('router')->generate('access_denied');
//          
//           return $this->redirect($url); 
//        }
        
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->container->get('doctrine')->getManager();
            $book = $em->getRepository('ReaderBundle:Book')->find($id);
           $securityContext = $this->get('security.context');
         //$token = $securityContext->getToken();
        // check for edit access
         
        if (false === $securityContext->isGranted('DELETE', $book)) {
            $url = $this->container->get('router')->generate('access_denied');
          
           return $this->redirect($url); 
        }
            if (!$book) {
               $this->container->get('session')->getFlashBag()->add(
            'errors',
            'Book doesn\'t exist'
           ); 
           $url = $this->container->get('router')->generate('home');
           return $this->redirect($url);  
            }

            $em->remove($book);
            $em->flush();
        }
       $url = $this->container->get('router')->generate('books');
       return $this->redirect($url);
    }
     
    /**
     * Creates a form to delete a Book entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

}
