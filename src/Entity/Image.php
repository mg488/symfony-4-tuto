<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 */
class Image
{
    // la relation OneToOne se définie dans l'entité advert car :
    //chaque advert (annoce) posséde une image
    //on aura plutôt tendance à récupérer l'image à partir de l'annoce que l'inverse
    //cela permet de rendre indépendante l'entité Imag : elle pourra être utilisée par d'autres
    //entités que Advert, de façon totalement ivisible pour elle.
    //on note que seule l'entité propirétaire est modifiée : c'est parce qu'on a une relation unidirectionnelle
    //

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $alt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }
}
