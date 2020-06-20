<?php

namespace App\Entity;

use App\Entity\Image;
use App\Entity\Category;
use App\Entity\Application;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdvertRepository")
 * @ORM\Table(name="Tabadvert")
 * @ORM\HasLifecycleCallbacks()
 */
class Advert
{   

    /**
     *@Gedmo\Slug(fields={"title"})
     *@ORM\Column(name="slug", type="string", length=255, unique=false) 
     */
    private $slug;
    /**
    * @ORM\Column(name="nb_applications", type="integer")
    */
    private $nbApplications = 0;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Application", mappedBy="advert")
     */
    private $applications;
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Image", cascade={"persist"})
     */
    private $image;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", cascade={"persist","remove"})
     * @ORM\JoinTable(name="advert_category")
     */
    private $categories;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author;

    /**
     ****peut-être utilisé pour gérer automatique les dates: @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $date_crea;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_maj;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $published = true;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    public function __construct()
    {
        //par défaut la date de création est la date du jour et définir
        //categories et applications comme des ArrayCollection
        $this->date_crea= new \Datetime();
        $this->categories = new ArrayCollection();
        $this->applications = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getDateCrea(): ?\DateTimeInterface
    {
        return $this->date_crea;
    }

    public function setDateCrea(\DateTimeInterface $date_crea): self
    {
        $this->date_crea = $date_crea;

        return $this;
    }

    public function getDateMaj(): ?\DateTimeInterface
    {
        return $this->date_maj;
    }

    public function setDateMaj(?\DateTimeInterface $date_maj): self
    {
        $this->date_maj = $date_maj;

        return $this;
    }

    public function getPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(?bool $published): self
    {
        $this->published = $published;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
    public function setImage(Image $image = null)
    {
       $this->image = $image; //dans le tutuo pas de return
       return $this;
    }
  
    public function getImage()
    {
      return $this->image;
    }
    //******addCategory, removeCategory, getCategories : on a un tableau donc ce quifait que les setter//
    //et gettes de category sont différents**/
    public function addCategory(Category $category)
    {
        //ici on utilise l'ArrayCollection vraiment comme un tableau
        $this->categories[] = $category;
    }
    public function removeCategory(Category $category)
    {
        //ici on utilise remone méthode de l'ArrayCollection, pour supprimer la catégorie en argument
        $this->categories->removeElement($category);
    }
    public function getCategories()
    {
        //on récupère une liste de catégories
        return $this->categories;
    }
    public function addApplication(Application $application)
    {
        $this->applications[] = $application;
        //on lie l'application à l'annonce
        //lors de la liaison de l'annonce à l'application dans le controlleur :
        // ($advert->addApplication($application)) ==> fait appel à addApplication et on en profite pour lier
        //l'annonce avec $this à l'application
        $application->setAdvert($this);
    }
    public function getApplications()
    {
        return $this->applications;
    }
    public function removeApplication(Application $application)
    {
        $this->applications->removeElement($application);
    }
    /**
    * @ORM\PreUpdate
    */
    public function updateDate()
    {
        $this->setDateMaj(new \DateTime());
    }
    
    public function increaseApplication()
    {
        $this->nbApplications++;
    }
    public function decreaseApplication()
    {
        $this->nbApplications--;
    }
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }
    public function getSlug()
    {
        return $this->slug;
    }
}
