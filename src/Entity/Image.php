<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks()
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

    private $tempFilename;

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
        // dd($this->file);
        //on vérifie si on avait déjà une fichier pour cette entité
        if(null !== $this->url)
        {
            //on sauvegarde l'extension
            $this->tempFilename = $this->url;
            //on réinitialise les valeurs de attibuts url et alt
            $this->url = null;
            $this->alt = null;
        }
    }
    //****************2ere façon d'enregistrer l'image*********************************************** */
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        // dd($this->file);
        // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
        
        if (null === $this->file) {
        return;
        }
        // Le nom du fichier est son id, on doit juste stocker également son extension
        // Pour faire propre, on devrait renommer cet attribut en « extension », plutôt que « url »
        $this->url = $this->file->guessExtension();
        $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
        if(in_array($this->url, $extensions_autorisees)){
        // dd($this->file->guessExtension());
        // Et on génère l'attribut alt de la balise <img>, à la valeur du nom du fichier sur le PC de l'internaute
        $this->alt = pathinfo($this->file->getClientOriginalName(),PATHINFO_FILENAME);//sans l'extension
        }
        else
        {
            throw new NotFoundHttpException("L'extension ".$this->url." n'est pas autorisée.") ;
        }
    }
    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
       
        // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
        if (null === $this->file) {
        return;
        }

        // Si on avait un ancien fichier, on le supprime
        if (null !== $this->tempFilename) 
        {
        $oldFile = $this->getUploadRootDir().'/'.$this->id.'.'.$this->tempFilename;
            if (file_exists($oldFile)) 
            {
                unlink($oldFile);
            }
        }
        // dd($this->getUploadRootDir());
        // On déplace le fichier envoyé dans le répertoire de notre choix
        $this->file->move(
        $this->getUploadRootDir(), // Le répertoire de destination
        $this->id.'.'.$this->url   // Le nom du fichier à créer, ici « id.extension »
        );
        
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        // On sauvegarde temporairement le nom du fichier, car il dépend de l'id
        $this->tempFilename = $this->getUploadRootDir().'/'.$this->id.'.'.$this->url;
    }
    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        // En PostRemove, on n'a pas accès à l'id, on utilise notre nom sauvegardé
        if (file_exists($this->tempFilename)) {
        // On supprime le fichier
        unlink($this->tempFilename);
        }
    }

    public function getUploadDir()
    {
        // On retourne le chemin relatif vers l'image pour un navigateur
        return 'images';
    }

    protected function getUploadRootDir()
    {
        // On retourne le chemin relatif vers l'image pour notre code PHP
        return __DIR__.'/../../public/'.$this->getUploadDir();
    }
    //méthode pour l'affichage  dans le template
    public function getimgPath()
    {
      return $this->getUploadDir().'/'.$this->getId().'.'.$this->getUrl();
    }
   
    //****************1ere façon d'enregistrer l'image*********************************************** */
            // public function upload()
            // {
            //     // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
            //     if (null === $this->file) 
            //     {
            //         return;
            //     }
            //     // On récupère le nom original du fichier de l'internaute
            //     $name = $this->file->getClientOriginalName();
            
            //     // On déplace le fichier envoyé dans le répertoire de notre choix
            //     $this->file->move($this->getUploadDir(), $name);
            //     // $this->file->move($this->getUploadRootDir(), $name);
            
            //     // On sauvegarde le nom de fichier dans notre attribut $url
            //     $this->url = $this->getUploadDir().$name;
            //     // $this->url = $this->getUploadRootDir().'/'.$name;
            
            //     // On crée également le futur attribut alt de notre balise <img>
            //     $this->alt = $name;
            // }
        
            // public function getUploadDir()
            // {
            //     // On retourne le chemin relatif vers l'image pour un navigateur (relatif au répertoire /web donc)
            //     return 'images/';
            // }
        
            // protected function getUploadRootDir()
            // {
            //     // On retourne le chemin relatif vers l'image pour notre code PHP
            //     return __DIR__.'/'.$this->getUploadDir();
            //     // C:\wamp64\www\symfony-4-tuto\src\Entity/../../../../images/istockphoto-877369086-612x612.jpg
            // }
}
