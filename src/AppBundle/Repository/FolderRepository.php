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
    public function getFolderByUser($user)
    {
        $qb = $this->createQueryBuilder("d")
            ->select("d.id as id_folder")
            ->addSelect("d.name as name_folder")
            ->addSelect("DATE_FORMAT(d.createdAt, '%d-%m-%Y') as created_at")
            ->addSelect("DATE_FORMAT(d.createdAt, '%h:%i') as created_time")
            ->addSelect("d.share")
            ->addSelect("creator.id as created_by")
            ->addSelect("parent.id as parent__id")
            ->leftJoin("d.childFolders", "parent")
            ->innerJoin("d.createdBy", "creator")
            ->where("creator.id =:user")
            ->andWhere("d.deletedAt IS NULL")
            ->setParameter("user", $user)
            ->andWhere("parent.id IS NULL");
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
            ->addSelect("DATE_FORMAT(d.createdAt, '%d-%m-%Y') as created_at")
            ->addSelect("DATE_FORMAT(d.createdAt, '%h:%i') as created_time")
            ->addSelect("d.share")
            ->addSelect("creator.id as created_by")
            ->addSelect("parent.id as parent__id")
            ->innerJoin("d.folderUsers", "du")
            ->innerJoin("du.user", "us")
            ->leftJoin("d.childFolders", "parent")
            ->innerJoin("d.createdBy", "creator")
            ->where("us =:user")
            ->andWhere("d.deletedAt IS NULL")
            ->groupBy("d.id")
            ->setParameter("user", $user)
            ->andWhere("parent.id IS NULL");
        return $qb->getQuery()->getResult();
    }

    /**
     * @param $id
     * @return array
     */
    public function getFolderById($id)
    {
        $qb = $this->createQueryBuilder("d")
            ->select("d.id as id_folder")
            ->addSelect("DATE_FORMAT(d.createdAt, '%d-%m-%Y') as date_created")
            ->addSelect("DATE_FORMAT(d.createdAt, '%h:%i') as heure_created")
            ->addSelect("creator.id as user_id")
            ->addSelect("creator.username as user_name")
            ->addSelect("creator.firstname as user_firstname")
            ->leftJoin("d.createdBy", "creator")
            ->where("d.id =:id")
            ->andWhere("d.deletedAt IS NULL")
            ->setParameter("id", $id);
        $result = $qb->getQuery()->getResult();
        return (isset($result[0]) ? $result[0] : 0);
    }
}