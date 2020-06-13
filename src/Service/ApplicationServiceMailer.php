<?php
namespace App\Service;

use App\Entity\Application;

    class ApplicationServiceMailer{
        /**
         * @var \Swift_Mailer
         */
        private $mailer;
        public function __construct(\Swift_Mailer $mailer)
        {
            $this->mailer = $mailer;
        }
        public function sendNewNotification(Application $application)
        {
            $message = new \Swift_Message(
            'Nouvelle candidature',
            'Vous avez reçu une nouvelle candidature.'
          );
      
          $message
          
            ->addTo($application->getAdvert()->getAuthor()) // Ici bien sûr il faudrait un attribut "email", j'utilise "author" à la place
            ->addFrom('contactvillagedenguith@gmail.com')
          ;
      
          $this->mailer->send($message);
        }
    }
?>