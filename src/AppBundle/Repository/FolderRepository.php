<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16/02/2018
 * Time: 13:08
 */
namespace AppBundle\Repository;

use ApiBundle\Entity\User;
use AppBundle\Entity\Folder;

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
            ->addSelect("proprietaire.id as user_id")
            ->addSelect("creator.id as created_by")
            ->addSelect("parent.id as parent__id")
            ->addSelect("DATE_FORMAT(d.createdAt, '%d-%m-%Y') as created_at")
            ->addSelect("DATE_FORMAT(d.createdAt, '%h:%i') as created_time")
            ->addSelect("d.share")
            ->leftJoin("d.childFolders", "parent")
            ->leftJoin("d.user", "proprietaire")
            ->leftJoin("d.createdBy", "creator")
            ->where("proprietaire.id =:user")
            ->andWhere("d.deletedAt IS NULL")
            ->setParameter("user", $user)
            ->andWhere("parent.id IS NULL");
        return $qb->getQuery()->getResult();
    }

    /**
     * @param $user
     * @param $id_folder
     * @return array
     */
    public function getFolderByUserIdFolder($user, $id_folder)
    {
        $qb = $this->createQueryBuilder("d")
            ->select("d.id as id_folder")
            ->addSelect("d.name as name_folder")
            ->addSelect("DATE_FORMAT(d.createdAt, '%d-%m-%Y') as created_at")
            ->addSelect("DATE_FORMAT(d.createdAt, '%h:%i') as created_time")
            ->addSelect("d.share")
            ->addSelect("creator.id as created_by")
            ->addSelect("parent.id as parent_id")

            ->leftJoin("d.childFolders", "parent")
            ->leftJoin("d.createdBy", "creator")
            ->leftJoin("d.user", "proprietaire")

            ->andWhere("d.deletedAt IS NULL")
            ->andWhere("parent.id =:id_folder")
            ->setParameter("id_folder", $id_folder);
           // ->andWhere("proprietaire.id =:user_")
           // ->setParameter("user_", $user);
        return $qb->getQuery()->getResult();
    }
    public function getFolderExterne($user, $id_folder)
    {
        $qb = $this->createQueryBuilder("d")
            ->select("d.id as id_folder")
            ->addSelect("d.name as name_folder")
            ->addSelect("DATE_FORMAT(d.createdAt, '%d-%m-%Y') as created_at")
            ->addSelect("DATE_FORMAT(d.createdAt, '%h:%i') as created_time")
            ->addSelect("d.share")
            ->addSelect("creator.id as created_by")
            ->addSelect("parent.id as parent_id")

            ->leftJoin("d.childFolders", "parent")
            ->leftJoin("d.createdBy", "creator")
            ->innerJoin("d.user", "proprietaire")
            ->innerJoin("d.folderUsers", "du")
            ->innerJoin("du.user", "us")

            ->andWhere("d.deletedAt IS NULL")
            ->andWhere("parent.id =:id_folder")
            ->setParameter("id_folder", $id_folder)
            ->andWhere("us.id =:user_")
            ->setParameter("user_", $user);
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
            ->setParameter("user", $user);
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

    /**
     * @param Folder $folder
     * @param User   $user
     * @return array
     */
    public function findFolderLockableByUser(Folder $folder, User $user)
    {
        $dateNow = new \DateTime();
        $qb = $this->createQueryBuilder("fo")
            ->leftJoin("fo.folderUsers", "fu", 'with', "fu.right IN ('1','4')")
            ->where("fo.user = :user_id")
            ->orWhere("fu.user = :user_id")
            ->andWhere("fo.id = :folder_id ")
            ->andWhere("fu.expiredAt > :date_now OR fu.expiredAt IS NULL OR  fu.expiredAt = ''");
        $qb->setParameters(
            [
                'user_id' => $user,
                'folder_id' => $folder,
                'date_now'=> $dateNow->format('Y-m-d h:i:s')
            ]
        );

        return $qb->getQuery()->getArrayResult();
    }
}
