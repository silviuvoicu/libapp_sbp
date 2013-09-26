<?php

namespace BddSBP\ReaderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Book
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="books")
 * @ORM\Entity()
 */
class Book
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id; 
    /**
     * @var string
     *
     * @ORM\Column()
     * @Assert\NotBlank(message="Book without a title is pretty useless")
     */
    private $title;
    /**
     * @var string
     *
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank(message="Book should have a number of pages")
     */
    private $pages;
    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Book should have a description")
     */
    private $description;
    /**
     * @var string
     *
     * @ORM\Column()
     * @Assert\NotBlank(message="Book should have an author")
     */
    private $author;
    
     /**
      * @ORM\Column(type="datetime")
      */
    private $createdAt;
    
    /**
      * @ORM\Column(type="datetime")
      */
    private $updatedAt;
    
    public function getId()
    {
        return $this->id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
       return $this->title;
    }

    public function setPages($pages)
    {
        $this->pages = $pages;
    }

    public function getPages()
    {
        return $this->pages;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function getAuthor()
    {
       return $this->author;
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
