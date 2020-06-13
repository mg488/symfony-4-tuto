<?php

namespace App\Entity;

use App\Entity\Advert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ApplicationRepository")
 * @ORM\Table(name="Tabapplication")
 * @ORM\HasLifecycleCallbacks()
 */
class Application
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Advert", inversedBy="applications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $advert;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_crea;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_maj;

    public function __construct(){
        //par défaut la date de la création est la date du jour
        $this->date_crea = new \Datetime();
    }
    public function setAdvert(Advert $advert)
    {
        $this->advert = $advert;

        return $this;
    }
    public function getAdvert()
    {
        return $this->advert;
    }
    public function getId(): ?int
    {
        return $this->id;
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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
    /**
     * @ORM\PrePersist
     */
    public function increase(){
        $this->getAdvert()->increaseApplication();
    }
    /**
     * @ORM\PreRemove
     */
    public function decrease(){
        $this->getAdvert()->decreaseApplication();
    }
}
