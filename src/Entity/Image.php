<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

    private $file;

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

    public function getFile()
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }
    //*************************************************************** */
    public function upload()
    {
        // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
        if (null === $this->file) 
        {
            return;
        }
        // On récupère le nom original du fichier de l'internaute
        $name = $this->file->getClientOriginalName();
    
        // On déplace le fichier envoyé dans le répertoire de notre choix
        $this->file->move($this->getUploadDir(), $name);
        // $this->file->move($this->getUploadRootDir(), $name);
    
        // On sauvegarde le nom de fichier dans notre attribut $url
        $this->url = $this->getUploadDir().$name;
        // $this->url = $this->getUploadRootDir().'/'.$name;
    
        // On crée également le futur attribut alt de notre balise <img>
        $this->alt = $name;
    }
  
    public function getUploadDir()
    {
        // On retourne le chemin relatif vers l'image pour un navigateur (relatif au répertoire /web donc)
        return 'images/';
    }
  
    protected function getUploadRootDir()
    {
        // On retourne le chemin relatif vers l'image pour notre code PHP
        return __DIR__.'/'.$this->getUploadDir();
        // C:\wamp64\www\symfony-4-tuto\src\Entity/../../../../images/istockphoto-877369086-612x612.jpg
    }
}
