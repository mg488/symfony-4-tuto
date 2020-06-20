<?php
namespace App\Service;


    class sendMailService{
        /**
         * @var \Swift_Mailer
         */
        private $mailer;
        public function __construct(\Swift_Mailer $mailer)
        {
            $this->mailer = $mailer;
        }
        public function sendNewNotification(String $contentMessage)
        {
            $message = new \Swift_Message(
                $contentMessage
          );
      
          $message
          
            // ->addTo($application->getAdvert()->getAuthor()) // Ici bien sûr il faudrait un attribut "email", j'utilise "author" à la place
            ->addFrom('contactvillagedenguith@gmail.com')
          ;
      
          $this->mailer->send($message);
        }
    }
?>