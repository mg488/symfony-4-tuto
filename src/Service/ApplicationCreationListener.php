<?php
namespace App\Service;

use App\Entity\Application;
use App\Service\ApplicationServiceMailer;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class ApplicationCreationListener
{
    /**
     * @var ApplicationServiceMailer
     */
    private $applicationServiceMailer;

    public function __construct(ApplicationServiceMailer $applicationServiceMailer)
    {
        $this->applicationServiceMailer = $applicationServiceMailer;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // On ne veut envoyer un email que pour les entités Application
        if (!$entity instanceof Application) {
            return;
        }

        $this->applicationServiceMailer->sendNewNotification($entity);
    }
}
?>