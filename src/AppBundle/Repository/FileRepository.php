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
     * @param User $user
     * @param null $idFolder
     *
     * @return array
     */
    public function getFilesByUser($user, $idFolder = null)
    {
        $qb = $this->createQueryBuilder("f")
            ->select()
            ->innerJoin("f.user", "usr")
            ->leftJoin("f.folder", "FOLDER_")
            ->where("usr =:user")
            ->andWhere("f.deletedAt IS NULL AND f.status !=:statut")
            ->groupBy("f.id")
            ->setParameter("statut", Constant::FILE_STATUS_DELETED)
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
            $file['right'] = [Constant::RIGHT_OWNER => "OWNER"];
            if (count($f->getFileUsers()) > 0) {
                $file['shared'] = 1;
            }
            $files[] = $file;
        }

        return $files;
    }

    /**
     * @param User   $user
     * @param null   $idFolder
     * @param string $keyCrypt
     *
     * @return mixed
     */
    public function getFilesByIdFolder($user, $idFolder = null, $keyCrypt = null)
    {
        $qb = $this->createQueryBuilder("f")
            ->select()
            ->innerJoin("f.user", "usr")
            ->leftJoin("f.folder", "FOLDER_")
            ->andWhere("FOLDER_.id =:id_folder")
            ->andWhere("f.deletedAt IS NULL AND f.status !=:statut")
            ->setParameter("id_folder", $idFolder)
            ->andWhere("usr.id =:user_ OR f.locked =:locked_")
            ->setParameter("user_", $user)
            ->setParameter("locked_", Constant::NOT_LOCKED)
            ->setParameter("statut", Constant::FILE_STATUS_DELETED);

        $files = [];
        $parent = null;
        foreach ($qb->getQuery()->getResult() as $f) {
            $parent = $f->getFolder();
            if ($user != $parent->getUser()
                && $parent->getCrypt() == Constant::CRYPTED  && $parent->getCryptPassword() != $keyCrypt
            ) {
                return [];
            }
            if ($parent->getLocked() == Constant::LOCKED) {
                return [];
            }
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
            $file['right'] = $f->getRightbyUser($user);
            if (count($f->getFileUsers()) > 0) {
                $file['shared'] = 1;
            }
            $files[] = $file;
        }
        if($parent && $parent->getUser() == $user) {
            $data["interne"]["files"] = $files;
            $data["externe"]["files"] = [];
        } else {
            $data["interne"]["files"] = [];
            $data["externe"]["files"] = $files;
        }

        return $data;
    }

    /**
     * @param User    $user
     * @param integer $idFolder
     *
     * @return array
     */
    public function getFilesInvitRequest($user, $idFolder)
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
            ->addSelect("r.id as id_right")
            ->addSelect("r.name as name_right")

            ->leftJoin("f.folder", "FOLDER_")
            ->innerJoin("f.fileUsers", "FU")
            ->innerJoin("FU.right", "r")
            ->innerJoin("FU.user", "usr")
            ->where("usr =:user")
            ->andWhere("f.locked =:locked_")
            ->andWhere("f.deletedAt IS NULL AND f.status !=:statut")
            ->groupBy("f.id")
            ->setParameter("user", $user)
            ->setParameter("locked_", Constant::NOT_LOCKED)
            ->setParameter("statut", Constant::FILE_STATUS_DELETED);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param integer $idFolder
     *
     * @return array
     */
    public function getTailleTotal($idFolder)
    {
        $qb = $this->createQueryBuilder("f")
            ->select("SUM(f.size) as size")
            ->addSelect("count(f.id) as nb_file")
            ->leftJoin("f.folder", "folder")
            ->where("folder.id =:id_folder")
            ->andWhere("f.deletedAt IS NULL  AND f.status !=:statut")
            ->setParameter("id_folder", $idFolder)
            ->setParameter("statut", Constant::FILE_STATUS_DELETED);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param integer $idFile
     *
     * @return array
     */
    public function getInfosUser($idFile)
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
            ->setParameter("id_file", $idFile);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param integer $id
     *
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
     *
     * @param integer $id
     *
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
     *
     * @param  integer $fileParentId
     *
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
     *
     * @param  int  $fileId
     * @param  User $user
     *
     * @return array
     */
    public function getRightToFile($fileId, User $user)
    {
        $r = null;
        $file = $this->find($fileId);
        if ($file && $file->getUser() == $user) {
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
                'date_now' => $dateNow->format('Y-m-d h:i:s'),
            ]
        );
        $right = $qb->getQuery()->getResult();
        if ($right) {
            $r = $right[0]['id_right'];
        }

        return $r;
    }

    /**
     * @param array $ids
     *
     * @return array
     */
    public function getByIds($ids)
    {
        $qb = $this->createQueryBuilder("fi")
            ->where('fi.deletedAt IS NULL');
        $qb->add('where', $qb->expr()->in('fi.id', $ids));

        return $qb->getQuery()->getResult();
    }


    /**
     * get assigned users to a file
     * @param integer $fileId
     * @return array
     */
    public function getUsersToFile($fileId)
    {
        $dateNow = new \DateTime();
        $qb = $this->createQueryBuilder("f")
            ->select("u.id")
            ->addSelect("u.lastname as last_name")
            ->addSelect("u.firstname as firs_tname")
            ->addSelect("u.email")
            ->addSelect("r.name as right")
            ->innerJoin("f.fileUsers", "fu")
            ->leftJoin("fu.user", "u")
            ->leftJoin("fu.right", "r")
            ->andWhere("f.id = :file_id ")
            ->andWhere("fu.expiredAt > :date_now OR fu.expiredAt IS NULL OR  fu.expiredAt = ''")
            ->andWhere('u.isDeleted = :isDeleted');
        $qb->setParameters(
            [
                'file_id' => $fileId,
                'date_now' => $dateNow->format('Y-m-d h:i:s'),
                'isDeleted' => Constant::USER_NOT_DELETED,
            ]
        );

        return $qb->getQuery()->getResult();
    }
}
