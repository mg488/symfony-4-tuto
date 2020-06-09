<?php

namespace App\Entity;

use App\Entity\Image;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdvertRepository")
 * @ORM\Table(name="Tabadvert")
 */
class Advert
{
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Image", cascade={"persist"})
     */
    private $image;

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
    private $published;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    public function __construct(){
        //par défaut la date de création est la date du jour
        $this->date_crea= new \Datetime();
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
}
