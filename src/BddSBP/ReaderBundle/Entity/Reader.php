<?php

namespace BddSBP\ReaderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="readers")
 */

class Reader
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string")
     */
    private $email;
    
      /**
      * @ORM\Column(type="string")
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
}