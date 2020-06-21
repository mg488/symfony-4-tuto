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
        public function sendNewMail(array $tabContact)
        {
          $message = (new \Swift_Message());
          $message
                ->setSubject($tabContact['objet'])
                ->setBody($tabContact['content'].' Par : '.$tabContact['firstName'].'-'. $tabContact['lastName'].' : '.$tabContact['email'],'text/html')
                ->addTo('contactvillagedenguith@gmail.com')
                ->setFrom(['mg@nguith.com'=> $tabContact['firstName'].'-'. $tabContact['lastName']])
          ;
        
      
          $this->mailer->send($message);
        }
    }
?>