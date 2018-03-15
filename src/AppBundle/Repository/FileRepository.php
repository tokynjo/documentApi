<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16/02/2018
 * Time: 13:08
 */

namespace AppBundle\Repository;


use ApiBundle\Entity\User;
use AppBundle\Entity\Constants\Constant;


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
            ->select()
            ->innerJoin("f.user", "usr")
            ->leftJoin("f.folder", "FOLDER_")
            ->where("usr =:user")
            ->andWhere("f.deletedAt IS NULL")
            ->groupBy("f.id")
            ->setParameter("user", $user);
        $qb->andWhere("FOLDER_.id IS NULL");

        $files = [];
        foreach ($qb->getQuery()->getResult() as $f) {
            $file = [];
            $file['id_file'] = $f->getId();
            $file['symbolicName'] = $f->getSymbolicName();
            $file['name'] = $f->getName();
            $file['serverId'] = $f->getServerId();
            $file['expirated_at'] = $f->getExpiration()->format("Y-m-d");
            $file['expirated_time'] = $f->getExpiration()->format("Y-m-d");
            $file['updated_at'] = $f->getUploadDate()->format("Y-m-d");
            $file['updated_time'] = $f->getUploadDate()->format("Y-m-d");
            $file['sharedPermalink'] = $f->getShare();
            $file['locked'] = $f->getLocked();
            $file['encryption'] = $f->getEncryption();
            $file['archiveFileId'] = $f->getArchiveFileId();
            $file['shared'] = 0;
            if (count($f->getFileUsers()) > 0) {
                $folder['shared'] = 1;
            }
            $files[] = $file;
        }
        return $files;
    }

    public function getFilesByIdFolder($user, $id_folder = null)
    {
        $qb = $this->createQueryBuilder("f")
            ->select()
            ->innerJoin("f.user", "usr")
            ->leftJoin("f.folder", "FOLDER_")
            ->andWhere("FOLDER_.id =:id_folder")
            ->andWhere("f.deletedAt IS NULL")
            ->setParameter("id_folder", $id_folder)
            ->andWhere("usr.id =:user_ OR f.locked =:locked_")
            ->andWhere("usr.id =:user_ OR f.encryption =:encryption_")
            ->setParameter("user_", $user)
            ->setParameter("encryption_", Constant::NOT_CRYPTED)
            ->setParameter("locked_", Constant::NOT_LOCKED);

        $files = [];
        foreach ($qb->getQuery()->getResult() as $f) {
            $file = [];
            $file['id_file'] = $f->getId();
            $file['symbolicName'] = $f->getSymbolicName();
            $file['name'] = $f->getName();
            $file['serverId'] = $f->getServerId();
            $file['expirated_at'] = $f->getExpiration()->format("Y-m-d");
            $file['expirated_time'] = $f->getExpiration()->format("Y-m-d");
            $file['updated_at'] = $f->getUploadDate()->format("Y-m-d");
            $file['updated_time'] = $f->getUploadDate()->format("Y-m-d");
            $file['sharedPermalink'] = $f->getShare();
            $file['locked'] = $f->getLocked();
            $file['encryption'] = $f->getEncryption();
            $file['archiveFileId'] = $f->getArchiveFileId();
            $file['shared'] = 0;
            if (count($f->getFileUsers()) > 0) {
                $folder['shared'] = 1;
            }
            $files[] = $file;
        }

        return $files;
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
            ->addSelect("f.encryption")
            ->addSelect("f.archiveFileId")
            ->addSelect("FOLDER_.id as id_folder")
            ->leftJoin("f.folder", "FOLDER_")
            ->innerJoin("f.fileUsers", "FU")
            ->innerJoin("FU.user", "usr")
            ->where("usr =:user")
            ->andWhere("f.locked =:locked_ AND f.encryption =:encryption_")
            ->andWhere("f.deletedAt IS NULL")
            ->groupBy("f.id")
            ->setParameter("user", $user)
            ->setParameter("encryption_", Constant::NOT_CRYPTED)
            ->setParameter("locked_", Constant::NOT_LOCKED);
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

    /**
     * @param $id
     * @return array
     */
    public function getNameFile($id)
    {
        $qb = $this->createQueryBuilder("f")
            ->select("f.id")
            ->addSelect("f.name");
        $qb->add('where', $qb->expr()->in('f.id', $id));
        return $qb->getQuery()->getResult();
    }


    /**
     * Get folder by id with url_mapping
     * @param $id
     * @return array
     */
    public function getPermalink($id)
    {
        return $this->createQueryBuilder("f")
            ->select("f.id")
            ->addSelect("f.permalink")
            ->addSelect("(CASE WHEN f.sharePassword != '' THEN 1 ELSE 0 END) as protected")
            ->addSelect("f.share")
            ->addSelect("urlmapp.code")
            ->leftJoin("AppBundle:UrlMapping", "urlmapp", "WITH", "urlmapp.url LIKE CONCAT('%',f.permalink,'%')")
            ->where("f.id =:id_")
            ->setParameter("id_", $id)
            ->getQuery()
            ->getResult();
    }

    /**
     * get file by id_folder
     * @param $fileParentId
     * @return array
     */
    public function findDirectChildFolder($fileParentId)
    {
        $qb = $this->createQueryBuilder("f")
            ->leftJoin("f.folder", "fp");
        if ($fileParentId) {
            $qb->andWhere("fp.id= :parent_id")
                ->setParameter('parent_id', $fileParentId);
        }
        return $qb->getQuery()->getResult();
    }


    /**
     * get the right of an user to a file
     * @param int $file_id
     * @param User $user
     * @return array
     */
    public function getRightToFile($file_id, User $user)
    {
        $r = null;
        $file = $this->find($file_id);
        if($file && $file->getUser() == $user) {
            return Constant::RIGHT_OWNER;
        }

        $dateNow = new \DateTime();
        $qb = $this->createQueryBuilder("f")
            ->select("r.id as id_right")
            ->leftJoin("f.fileUsers", "fu")
            ->leftJoin("fu.right", "r")
            ->where("f.user = :user_id")
            ->orWhere("fu.user = :user_id")
            ->andWhere("f.id = :file_id ")
            ->andWhere("fu.expiredAt > :date_now OR fu.expiredAt IS NULL OR  fu.expiredAt = ''");
        $qb->setParameters(
            [
                'user_id' => $user,
                'file_id' => $file,
                'date_now'=> $dateNow->format('Y-m-d h:i:s')
            ]);
        $right = $qb->getQuery()->getResult();
        if($right) {
            $r = $right[0]['id_right'];
        }
        return $r;
    }
}
