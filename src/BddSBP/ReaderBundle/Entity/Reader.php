<?php

namespace BddSBP\ReaderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use BddSBP\ReaderBundle\Entity\Book;


/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="readers")
 * @DoctrineAssert\UniqueEntity(fields="email", message="A reader with this email already exist. Please enter another email")
 */

class Reader implements UserInterface,\Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Pelease enter an email address")
     * @Assert\Email(message="This mail '{{ value }}' is not a valid email adddress")
     */
    private $email;
    
      /**
      * @ORM\Column(type="string")
       * @Assert\NotBlank(message="Please enter a password")
       * @Assert\Length(min=8,minMessage="The Password must be at least {{ limit }} characters")
      */
    private $password;
    
      /**
      * @ORM\Column(type="string")
      */
    private $salt;
    
      /**
      * @ORM\Column(type="datetime")
      */
    private $createdAt;
    
    /**
      * @ORM\Column(type="datetime")
      */
    private $updatedAt;
    
     /**
     * @ORM\OneToMany(targetEntity="Book", mappedBy="reader")
     * @ORM\OrderBy({"createdAt" = "DESC"})
     *
     * @var ArrayCollection $books
     */
    private $books;

    /**
     * Constructs a new instance of Reader
     */
    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    public function getSalt()
    {
        return $this->salt;
    }
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @ORM\PrePersist
     */
   public function setCreatedAtValue()
   {
    $this->createdAt = new \DateTime();
   }
   
   /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
   public function setUpdatedAtValue()
   {
    $this->updatedAt = new \DateTime();
   }
   
   /**
     * Gets all of the reader's books
     *
     * @return ArrayCollection The reader's books
     */
    public function getBooks()
    {
        return $this->books;
    }

    
     /**
     * Add books
     *
     * @param BddSBP\ReaderBundle\Entity\Book $books
     */
    public function addBook(Book $books)
    {
        $this->books[] = $books;
    }


    public function eraseCredentials() {
        
    }

    public function getRoles() {
        return array('ROLE_USER');
    }

    public function getUsername() {
        return $this->getEmail();
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id, $this->email,$this->password,$this->salt,$this->createdAt,$this->updatedAt,$this->books
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id, $this->email,$this->password,$this->salt,$this->createdAt,$this->updatedAt,$this->books
        ) = unserialize($serialized);
    }
    
    public function __toString() {
        return $this->getUsername();
    }
}