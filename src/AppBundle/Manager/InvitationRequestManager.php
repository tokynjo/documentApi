<?php

namespace AppBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;

class InvitationRequestManager extends BaseManager
{
    const SERVICE_NAME = 'app.invitation_request_manager';

    public function __construct(EntityManagerInterface $entityManager, $class)
    {
        parent::__construct($entityManager, $class);
    }

    /**
     * @param $user
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
     * @param $email
     * @return mixed
     */
    public function createInvitation($email,$folder,$from,$right)
    {
        $class = $this->class;
        $invitation = new $class();
        $invitation->setEmail($email);
        $invitation->setStatus(0);
        $invitation->setToken("test");
        $invitation->setFolder($folder);
        $invitation->setFrom($from);
        if($right){
            $invitation->setRight($right);
        }
        $this->saveAndFlush($invitation);
        return $invitation;
    }

}
