<?php

namespace AppBundle\Manager;

use ApiBundle\Entity\User;
use AppBundle\Entity\File;
use AppBundle\Entity\Folder;
use AppBundle\Entity\Right;
use Doctrine\ORM\EntityManagerInterface;

class InvitationRequestManager extends BaseManager
{
    const SERVICE_NAME = 'app.invitation_request_manager';

    public function __construct(EntityManagerInterface $entityManager, $class)
    {
        parent::__construct($entityManager, $class);
    }

    /**
     * @param  $user
     * @return mixed
     */
    public function getStructureInterne($user)
    {
        return $this->repository->getFilesByUser($user);
    }

    /**
     * @param $id
     * @return array
     */
    public function getInvites($id)
    {
        return $this->repository->getInvitationByFolder($id);
    }

    /**
     * @param string $message
     * @param string $email
     * @param Folder $folder
     * @param File   $file
     * @param User   $from
     * @param Right  $right
     * @param string $sync
     * @return mixed
     */
    public function createInvitation($message, $email, Folder $folder = null, File $file = null, User $from, Right $right = null, $sync)
    {
        $class = $this->class;
        $invitation = new $class();
        $invitation->setEmail($email);
        $invitation->setStatus(0);
        $invitation->setToken(md5(uniqid(rand(), true)));
        $invitation->setFolder($folder);
        $invitation->setFichier($file);
        $invitation->setFrom($from);
        $invitation->setMessage($message);
        $invitation->setSynchro($sync);
        if ($right) {
            $invitation->setRight($right);
        }
        $this->saveAndFlush($invitation);

        return $invitation;
    }

}
