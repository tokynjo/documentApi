<?php

namespace AppBundle\Repository;

/**
 * InvitationRequestRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InvitationRequestRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param $id_folder
     * @return array
     */
    public function getInvitationByFolder($id_folder)
    {
        $qb = $this->createQueryBuilder("inv")
            ->select("inv.id as invitation_id")
            ->addSelect("user.id as user_id")
            ->addSelect("user.username as user_name")
            ->addSelect("user.firstname as user_firstname")
            ->addSelect("user.email as user_mail")
            ->addSelect("droit.id as droite_id")
            ->addSelect("droit.name as droite_name")
            ->innerJoin("inv.folder", "dossier")
            ->leftJoin("inv.right", "droit")
            ->innerJoin("ApiBundle:User", "user", "WITH", "user.email = inv.email")
            ->where("dossier.id =:id_folder")
            ->setParameter("id_folder", $id_folder);
        return $qb->getQuery()->getResult();
    }

    public function getEmailByFolder($email, $id_folder)
    {
        $qb = $this->createQueryBuilder("inv")
            ->select("inv.email");
        $qb->add('where', $qb->expr()->in('inv.email', $email));
        $qb->where("inv.folder =:id_folder")
            ->setParameter("id_folder", $id_folder);
        $result = $qb->getQuery()->getResult();
        $data = [];
        foreach ($result as $mails) {
            $data[] = $mails['email'];
        }
        return $data;
    }
}
