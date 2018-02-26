<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16/02/2018
 * Time: 13:08
 */

namespace AppBundle\Repository;


class FileRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * @param $user
     * @param null $id_folder
     * @return array
     */
    public function getFilesByUser($user, $id_folder = null)
    {
        $qb = $this->createQueryBuilder("f")
            ->select("f.id as id_file")
            ->addSelect("f.name")
            ->addSelect("f.symbolicName")
            ->addSelect("f.serverId")
            ->addSelect("DATE_FORMAT(f.expiration, '%d-%m-%Y') as expirated_at")
            ->addSelect("DATE_FORMAT(f.expiration, '%h:%i') as time_expiration")
            ->addSelect("DATE_FORMAT(f.uploadDate, '%d-%m-%Y') as updated_at")
            ->addSelect("DATE_FORMAT(f.uploadDate, '%h:%i') as updated_at_time")
            ->addSelect("f.locked")
            ->addSelect("f.archiveFileId")
            ->innerJoin("f.user", "usr")
            ->leftJoin("f.folder", "FOLDER_")
            ->where("usr =:user")
            ->groupBy("f.id")
            ->setParameter("user", $user);
        $qb->andWhere("FOLDER_.id IS NULL");
        return $qb->getQuery()->getResult();
    }

    public function getFilesByIdFolder($user, $id_folder = null)
    {
        $qb = $this->createQueryBuilder("f")
            ->select("f.id as id_file")
            ->addSelect("f.name")
            ->addSelect("f.symbolicName")
            ->addSelect("f.serverId")
            ->addSelect("DATE_FORMAT(f.expiration, '%d-%m-%Y') as expirated_at")
            ->addSelect("DATE_FORMAT(f.expiration, '%h:%i') as time_expiration")
            ->addSelect("DATE_FORMAT(f.uploadDate, '%d-%m-%Y') as updated_at")
            ->addSelect("DATE_FORMAT(f.uploadDate, '%h:%i') as updated_at_time")
            ->addSelect("f.locked")
            ->addSelect("f.archiveFileId")
            ->innerJoin("f.user", "usr")
            ->leftJoin("f.folder", "FOLDER_")
            ->andWhere("FOLDER_.id =:id_folder")
            ->setParameter("id_folder", $id_folder);
//            ->andWhere("usr.id =:id_user")
//            ->setParameter("id_user", $user);
        return $qb->getQuery()->getResult();
    }

    /**
     * @param $user
     * @return array
     */
    public function getFilesInvitRequest($user, $id_folder)
    {
        $qb = $this->createQueryBuilder("f")
            ->select("f.id as id_file")
            ->addSelect("f.name")
            ->addSelect("f.symbolicName")
            ->addSelect("f.serverId")
            ->addSelect("DATE_FORMAT(f.expiration, '%d-%m-%Y') as expirated_at")
            ->addSelect("DATE_FORMAT(f.expiration, '%h:%i') as time_expiration")
            ->addSelect("DATE_FORMAT(f.uploadDate, '%d-%m-%Y') as updated_at")
            ->addSelect("DATE_FORMAT(f.uploadDate, '%h:%i') as updated_at_time")
            ->addSelect("f.locked")
            ->addSelect("f.archiveFileId")
            ->addSelect("FOLDER_.id as id_folder")
            ->leftJoin("f.folder", "FOLDER_")
            ->innerJoin("f.fileUsers", "FU")
            ->innerJoin("FU.user", "usr")
            ->where("usr =:user")
            ->groupBy("f.id")
            ->setParameter("user", $user);
        return $qb->getQuery()->getResult();
    }

    /**
     * @param $id_folder
     * @return array
     */
    public function getTailleTotal($id_folder)
    {
        $qb = $this->createQueryBuilder("f")
            ->select("SUM(f.size) as size")
            ->addSelect("count(f.id) as nb_file")
            ->leftJoin("f.folder", "folder")
            ->where("folder.id =:id_folder")
            ->andWhere("f.deletedAt IS NULL")
            ->setParameter("id_folder", $id_folder);
        return $qb->getQuery()->getResult();
    }

    public function getInfosUser($id_file)
    {
        $qb = $this->createQueryBuilder("f")
            ->select("f.id")
            ->addSelect("f.size")
            ->addSelect("DATE_FORMAT(f.uploadDate, '%d-%m-%Y') as date_created")
            ->addSelect("DATE_FORMAT(f.uploadDate, '%h:%i') as heure_created")
            ->addSelect("creator.id as user_id")
            ->addSelect("creator.username as user_name")
            ->addSelect("creator.firstname as user_firstname")
            ->leftJoin("f.user", "creator")
            ->where("f.id =:id_file")
            ->groupBy("f.id")
            ->setParameter("id_file", $id_file);
        return $qb->getQuery()->getResult();
    }
}