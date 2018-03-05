<?php

namespace AppBundle\Repository;
use AppBundle\Entity\Constants\Constant;

/**
 * FileUserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FileUserRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param $id_file
     * @return array
     */
    public function getInvitationByFile($id_file)
    {
        $qb = $this->createQueryBuilder("fu")
            ->select("user.id as user_id")
            ->addSelect("fichier.id as file_id")
            ->addSelect("user.username as user_name")
            ->addSelect("user.firstname as user_firstname")
            ->addSelect("user.email as user_mail")
            ->addSelect("droit.id as droite_id")
            ->addSelect("droit.name as droite_name")
            ->innerJoin("fu.file", "fichier")
            ->innerJoin("fu.user", "user")
            ->innerJoin("fu.right", "droit")
            ->where("fichier.id =:id_file")
            ->setParameter("id_file", $id_file);
        return $qb->getQuery()->getResult();
    }

    /**
     * @param $user
     * @param $folder
     * @return array
     */
    public function getDroitOfUser($user, $folder)
    {
        return $this->createQueryBuilder("fu")
            ->innerJoin("fu.user", "user")
            ->innerJoin("fu.file", "f")
            ->innerJoin("fu.right", "r")
            ->where("user =:user_")
            ->andWhere("f =:folder_ AND r.id =:manager_")
            ->setParameter("user_", $user)
            ->setParameter("folder_", $folder)
            ->setParameter("manager_", Constant::RIGHT_MANAGER)
            ->getQuery()->getResult();
    }
}
