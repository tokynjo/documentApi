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
            ->addSelect("f.expiration")
            ->addSelect("f.uploadDate")
            ->addSelect("f.locked")
            ->addSelect("f.archiveFileId")
            ->addSelect("FOLDER_.id as id_folder")
            ->leftJoin("f.folder", "FOLDER_")
            ->innerJoin("f.fileUsers", "FU")
            ->innerJoin("FU.user", "usr")
            ->where("usr =:user")
            ->groupBy("f.id")
            ->setParameter("user", $user);
        if ($id_folder == null) {
            $qb->andWhere("FOLDER_.id IS NULL");
        } else {
            $qb->andWhere("FOLDER_.id =:id_dossier")
                ->setParameter("id_dossier", $id_folder);
        }
        return $qb->getQuery()->getResult();
    }


    /**
     * @param $user
     * @return array
     */
    public function getFilesInvitRequest($user)
    {
        $qb = $this->createQueryBuilder("f")
            ->select("f.id as id_file")
            ->addSelect("f.name")
            ->addSelect("f.symbolicName")
            ->addSelect("f.serverId")
            ->addSelect("f.expiration")
            ->addSelect("f.uploadDate")
            ->addSelect("f.locked")
            ->addSelect("f.archiveFileId")
            ->addSelect("FOLDER_.id as id_folder")
            ->leftJoin("f.folder", "FOLDER_")
            ->innerJoin("f.invitationRequests", "ir")
            ->where("ir.email =:mail_user")
            ->groupBy("f.id")
            ->setParameter("mail_user", $user->getEmail());
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
            ->groupBy("f.id")
            ->setParameter("id_folder", $id_folder);
        return $qb->getQuery()->getResult();
    }

    public function getInfosUser($id_file){
        $qb = $this->createQueryBuilder("f")
            ->select("f.id")
            ->addSelect("f.size")
            ->addSelect("DATE_FORMAT(f.uploadDate, '%d-%m-%Y') as date_created")
            ->addSelect("DATE_FORMAT(f.uploadDate, '%h:%i') as heure_created")
            ->addSelect("usr.id as user_id")
            ->addSelect("usr.username as user_name")
            ->addSelect("usr.firstname as user_firstname")
            ->innerJoin("f.fileUsers","fu")
            ->leftJoin("fu.user","usr")
            ->where("f.id =:id_file")
            ->setParameter("id_file", $id_file);
        return $qb->getQuery()->getResult();
    }
}