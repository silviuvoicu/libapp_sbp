<?php

namespace BddSBP\ReaderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use BddSBP\ReaderBundle\Entity\Reader;
use Symfony\Component\Security\Acl\Model\DomainObjectInterface;

/**
 * Book
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="books")
 * @ORM\Entity()
 */
class Book implements DomainObjectInterface
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
    
    /**
     * @ORM\ManyToOne(targetEntity="Reader", inversedBy="books")
     * @ORM\JoinColumn(name="reader_id", referencedColumnName="id")
     * 
     * @var Reader $reader
     */
    private $reader;
    
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
    
     public function __toString() {
        return $this->getTitle();
    }
     /**
     * Gets the reader who created the book.
     * 
     * @return Reader A Reader object
     */
    public function getReader()
    {
        return $this->reader;
    }
    
    /**
     * Sets the reader who created the book.
     * 
     * @param Reader $value The reader
     */
    public function setReader( Reader $value )
    {
        $this->reader = $value;
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
   
    public function getObjectIdentifier() {
        return $this->getId();
    }
    
}
