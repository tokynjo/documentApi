<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16/02/2018
 * Time: 13:08
 */

namespace AppBundle\Repository;


class FolderRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * @param $user
     * @return array
     */
    public function getFolderByUser($user, $id_folder = null)
    {
        $qb = $this->createQueryBuilder("d")
            ->select("d.id as id_folder")
            ->addSelect("d.name as name_folder")
            ->addSelect("d.createdAt as created_at")
            ->addSelect("d.share")
            ->addSelect("d.createdBy as created_by")
            ->addSelect("parent.id as parent__id")
            ->innerJoin("d.dossierUsers", "du")
            ->innerJoin("du.user", "us")
            ->leftJoin("d.childFolders", "parent")
            ->where("us =:user")
            ->groupBy("d.id")
            ->setParameter("user", $user);
        if ($id_folder != null) {
            $qb->andWhere("parent =:id_folder")
                ->setParameter("id_folder", $id_folder);
        } else {
            $qb->andWhere("parent.id IS NULL");
        }
        return $qb->getQuery()->getResult();
    }

    /**
     * @param $user
     * @param null $id_folder
     * @return array
     */
    public function getFolderInvitRequest($user)
    {
        $qb = $this->createQueryBuilder("d")
            ->select("d.id as id_folder")
            ->addSelect("d.name as name_folder")
            ->addSelect("d.createdAt as created_at")
            ->addSelect("d.share")
            ->addSelect("d.createdBy as created_by")
            ->addSelect("parent.id as parent__id")
            ->innerJoin("d.invitationRequests", "ir")
            ->leftJoin("d.childFolders", "parent")
            ->where("ir.email =:mail_user")
            ->groupBy("d.id")
            ->setParameter("mail_user", $user->getEmail());
        return $qb->getQuery()->getResult();
    }

    public function getFolderById($id)
    {
        $qb = $this->createQueryBuilder("d")
            ->select("d.id as id_folder")
            ->addSelect("DATE_FORMAT(d.createdAt, '%d-%m-%Y') as date_created")
            ->addSelect("DATE_FORMAT(d.createdAt, '%h:%i') as heure_created")
            ->addSelect("usr.id as user_id")
            ->addSelect("usr.username as user_name")
            ->addSelect("usr.firstname as user_firstname")
            ->leftJoin("d.dossierUsers","du")
            ->leftJoin("du.user","usr")
            ->where("d.id =:id")
            ->setParameter("id", $id);
        $result = $qb->getQuery()->getResult();
        return (isset($result[0])?$result[0]:0);
    }
}