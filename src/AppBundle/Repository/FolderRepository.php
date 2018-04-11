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
use AppBundle\Entity\Folder;
use AppBundle\Manager\FolderManager;
use Doctrine\Common\Collections\ArrayCollection;

class FolderRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * @param User $user
     *
     * @return array
     */
    public function getFolderByUser($user)
    {
        $qb = $this->createQueryBuilder('d')
            ->select()
            ->leftJoin("d.parentFolder", "parent")
            ->leftJoin("d.user", "proprietaire")
            ->leftJoin("d.createdBy", "creator")
            ->where("proprietaire.id =:user AND d.status =:statut")
            ->andWhere("d.deletedAt IS NULL AND d.deletedBy IS NULL")
            ->setParameter("user", $user)
            ->setParameter("statut", Constant::FOLDER_STATUS_CREATED)
            ->andWhere("parent.id IS NULL");

        $folders = [];
        foreach ($qb->getQuery()->getResult() as $f) {
            $folder = [];
            $folder['id_folder'] = $f->getId();
            $folder['parent_id'] = ($f->getParentFolder() === null) ? '' : $f->getParentFolder()->getId();
            $folder['name_folder'] = $f->getName();
            $folder['created_at'] = $f->getCreatedAt()->format("Y-m-d");
            $folder['created_time'] = $f->getCreatedAt()->format("h:i:s");
            $folder['sharedPermalink'] = $f->getShare();
            $folder['locked'] = $f->getLocked();
            $folder['crypted'] = $f->getCrypt();
            $folder['shared'] = 0;
            $folder['right'] = [Constant::RIGHT_OWNER => "OWNER"];
            if (count($f->getFolderUsers()) > 0) {
                $folder['shared'] = 1;
            }
            $folders[] = $folder;
        }

        return $folders;
    }

    /**
     * @param User        $user
     * @param integer     $idFolder
     * @param string|null $keyCrypt
     * @return array
     */
    public function getFolderByUserIdFolder(User $user, $idFolder, $keyCrypt = null)
    {
        $qb = $this->createQueryBuilder("d")
            ->select()
            ->leftJoin("d.parentFolder", "parent")
            ->leftJoin("d.user", "proprietaire")
            ->leftJoin("d.folderUsers", "fu")
            ->leftJoin("fu.right", "r")
            ->andWhere("d.deletedAt IS NULL AND d.status =:statut")
            ->andWhere("parent.id =:id_folder")
            ->andWhere("d.deletedBy IS NULL")
            ->setParameter("id_folder", $idFolder)
            ->setParameter("statut", Constant::FOLDER_STATUS_CREATED);

        $data = [];
        $folders = [];
        $parent = null;
        foreach ($qb->getQuery()->getResult() as $f) {
            $parent = $f->getParentFolder();
            if($user != $f->getParentFolder()->getUser()
                && $f->getParentFolder()->getCrypt() == Constant::CRYPTED
                && $f->getParentFolder()->getCryptPassword() != $keyCrypt
            ) {
                return $data;
            }
            if ($user != $f->getParentFolder()->getUser()
                && $f->getParentFolder()->getLocked() == Constant::LOCKED
            ) {
                return $data;
            }
            if ($user != $f->getUser() && $f->getLocked() == Constant::LOCKED) {
                continue;
            }
            $folder = [];
            $folder['id_folder'] = $f->getId();
            $folder['parent_id'] = ($f->getParentFolder() === null) ? '' : $f->getParentFolder()->getId();
            $folder['name_folder'] = $f->getName();
            $folder['created_at'] = $f->getCreatedAt()->format("Y-m-d");
            $folder['created_time'] = $f->getCreatedAt()->format("h:i:s");
            $folder['sharedPermalink'] = $f->getShare();
            $folder['locked'] = $f->getLocked();
            $folder['crypted'] = $f->getCrypt();
            $folder['shared'] = 0;
            $folder['right'] = $f->getRightbyUser($user);
            if (count($f->getFolderUsers()) > 0) {
                $folder['shared'] = 1;
            }
            $folders[] = $folder;
        }
        if($parent && $parent->getUser() == $user) {
            $data["interne"]["folders"] = $folders;
            $data["externe"]["folders"] = [];
        } else {
            $data["interne"]["folders"] = [];
            $data["externe"]["folders"] = $folders;
        }

        return $data;
    }

    /**
     * @param User    $user
     * @param integer $idFolder
     *
     * @return array
     */
    public function getFolderExterne($user, $idFolder)
    {
        $qb = $this->createQueryBuilder("d")
            ->select("d.id as id_folder")
            ->addSelect("d.name as name_folder")
            ->addSelect("DATE_FORMAT(d.createdAt, '%d-%m-%Y') as created_at")
            ->addSelect("DATE_FORMAT(d.createdAt, '%h:%i') as created_time")
            ->addSelect("d.share")
            ->addSelect("d.locked")
            ->addSelect("creator.id as created_by")
            ->addSelect("parent.id as parent_id")
            ->addSelect("du.name")
            ->leftJoin("d.parentFolder", "parent")
            ->leftJoin("d.createdBy", "creator")
            ->innerJoin("d.user", "proprietaire")
            ->innerJoin("d.folderUsers", "du")
            ->innerJoin("du.user", "us")
            ->leftJoin("du.right", "r")
            ->andWhere("d.deletedAt IS NULL  AND d.status =:statut")
            ->andWhere("d.deletedBy IS NULL")
            ->andWhere("parent.id =:id_folder")
            ->setParameter("id_folder", $idFolder)
            ->andWhere("us.id =:user_")
            ->setParameter("user_", $user)
            ->andWhere("du.expiredAt > :date_now OR du.expiredAt IS NULL OR  du.expiredAt = ''")
            ->setParameter("statut", Constant::FOLDER_STATUS_CREATED)
            ->setParameter("date_now", new \DateTime());

        return $qb->getQuery()->getResult();
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function getFolderInvitRequest($user)
    {
        $qb = $this->createQueryBuilder("d")
            ->select()
            ->innerJoin("d.folderUsers", "du")
            ->leftJoin("du.right", "r")
            ->innerJoin("du.user", "us")
            ->leftJoin("d.parentFolder", "parent")
            ->leftJoin("d.createdBy", "creator")
            ->where("us =:user")
            ->andWhere("d.deletedAt IS NULL AND d.status =:statut")
            ->andWhere("d.deletedBy IS NULL")
            ->andWhere("du.expiredAt > :date_now OR du.expiredAt IS NULL OR  du.expiredAt = ''")
            ->setParameter("user", $user)
            ->setParameter("statut", Constant::FOLDER_STATUS_CREATED)
            ->setParameter("date_now", new \DateTime());
        $folders = [];
        $tabIdFolder = [];
        foreach ($qb->getQuery()->getResult() as $f) {
            if ($user != $f->getUser() && $f->getLocked() == Constant::LOCKED) {
                continue;
            }
            $idParent = ($f->getParentFolder() === null) ? '' : $f->getParentFolder()->getId();
            if (!in_array($idParent, $tabIdFolder)) {
                $folder = [];
                $folder['id_folder'] = $f->getId();
                $folder['parent_id'] = $idParent;
                $folder['name_folder'] = $f->getName();
                $folder['created_at'] = $f->getCreatedAt()->format("Y-m-d");
                $folder['created_time'] = $f->getCreatedAt()->format("h:i:s");
                $folder['sharedPermalink'] = $f->getShare();
                $folder['locked'] = $f->getLocked();
                $folder['crypted'] = $f->getCrypt();
                $folder['shared'] = 0;
                $folder['right'] = $f->getRightbyUser($user);
                if (count($f->getFolderUsers()) > 0) {
                    $folder['shared'] = 1;
                }
                $folders[] = $folder;
                $tabIdFolder[] = $f->getId();
            }
        }

        return $folders;
    }

    /**
     * @param integer $id
     *
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
            ->addSelect("d.locked")
            ->leftJoin("d.createdBy", "creator")
            ->where("d.id =:id")
            ->andWhere("d.deletedAt IS NULL AND d.status =:statut")
            ->andWhere("d.deletedBy IS NULL")
            ->setParameter("statut", Constant::FOLDER_STATUS_CREATED)
            ->setParameter("id", $id);
        $result = $qb->getQuery()->getResult();

        return (isset($result[0]) ? $result[0] : 0);
    }

    /**
     * @param Folder $folder
     * @param User   $user
     *
     * @return array
     */
    public function findFolderLockableByUser(Folder $folder, User $user)
    {
        $dateNow = new \DateTime();
        $qb = $this->createQueryBuilder("fo")
            ->leftJoin(
                "fo.folderUsers",
                "fu",
                'with',
                "fu.right IN ('".Constant::RIGHT_MANAGER."','".Constant::RIGHT_OWNER."')"
            )
            ->where("fo.user = :user_id")
            ->orWhere("fu.user = :user_id")
            ->andWhere("fo.id = :folder_id ")
            ->andWhere("fo.deletedBy IS NULL")
            ->andWhere("fo.deletedAt IS NULL")
            ->andWhere("fu.expiredAt > :date_now OR fu.expiredAt IS NULL OR  fu.expiredAt = ''");
        $qb->setParameters(
            [
                'user_id' => $user,
                'folder_id' => $folder,
                'date_now' => $dateNow->format('Y-m-d h:i:s'),
            ]
        );

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * @param string $folderParentId
     *
     * @return array
     */
    public function findDirectChildFolder($folderParentId)
    {

        $qb = $this->createQueryBuilder("fo")
            ->leftJoin("fo.parentFolder", "fp")
            ->andWhere("fo.deletedBy IS NULL");
        if ($folderParentId) {
            $qb->andWhere("fp.id= :parent_id")
                ->setParameter('parent_id', $folderParentId);
        }

        return $qb->getQuery()->getResult();
    }


    /**
     * get the right of an user to a folder
     *
     * @param int  $idFolder
     * @param User $user
     *
     * @return array
     */
    public function getRightToFolder($idFolder, User $user)
    {
        $r = null;
        $folder = $this->find($idFolder);
        if ($folder && $folder->getUser() == $user) {
            return Constant::RIGHT_OWNER;
        }

        $dateNow = new \DateTime();
        $qb = $this->createQueryBuilder("fo")
            ->select("r.id as id_right")
            ->leftJoin("fo.folderUsers", "fu")
            ->leftJoin("fu.right", "r")
            ->where("fo.user = :user_id")
            ->orWhere("fu.user = :user_id")
            ->andWhere("fo.id = :folder_id ")
            ->andWhere("fo.deletedBy IS NULL")
            ->andWhere("fo.deletedAt IS NULL")
            ->andWhere("fu.expiredAt > :date_now OR fu.expiredAt IS NULL OR  fu.expiredAt = ''")
            ->andWhere("fo.status =:stat");
        $qb->setParameters(
            [
                'user_id' => $user,
                'folder_id' => $folder,
                'date_now' => $dateNow->format('Y-m-d h:i:s'),
                'stat' => Constant::STATUS_CREATED
            ]
        );
        $right = $qb->getQuery()->getResult();
        if ($right) {
            $r = $right[0]['id_right'];
        }

        return $r;
    }

    /**
     * get assigned users to a folder
     *
     * @param integer $folderId
     *
     * @return array
     */
    public function getUsersToFolder($folderId)
    {
        $dateNow = new \DateTime();
        $qb = $this->createQueryBuilder("fo")
            ->select("u.id")
            ->addSelect("u.lastname as last_name")
            ->addSelect("u.firstname as firs_tname")
            ->addSelect("u.email")
            ->addSelect("r.name as right")
            ->innerJoin("fo.folderUsers", "fu")
            ->leftJoin("fu.user", "u")
            ->leftJoin("fu.right", "r")
            ->andWhere("fo.id = :folder_id ")
            ->andWhere("fu.expiredAt > :date_now OR fu.expiredAt IS NULL OR  fu.expiredAt = ''")
            ->andWhere('u.isDeleted = :isDeleted')
            ->andWhere("fo.deletedBy IS NULL")
            ->andWhere("fo.deletedAt IS NULL");
        $qb->setParameters(
            [
                'folder_id' => $folderId,
                'date_now' => $dateNow->format('Y-m-d h:i:s'),
                'isDeleted' => Constant::USER_NOT_DELETED,
            ]
        );

        return $qb->getQuery()->getResult();
    }

    /**
     * Get folder by id with url_mapping
     *
     * @param  integer $id
     * @return array
     */
    public function getPermalink($id)
    {
        return $this->createQueryBuilder("d")
            ->select("d.id")
            ->addSelect("d.permalink")
            ->addSelect("(CASE WHEN d.sharePassword != '' THEN 1 ELSE 0 END) as protected")
            ->addSelect("d.share")
            ->addSelect("urlmapp.code")
            ->leftJoin("AppBundle:UrlMapping", "urlmapp", "WITH", "urlmapp.url LIKE CONCAT('%',d.permalink,'%')")
            ->where("d.id =:id_")
            ->setParameter("id_", $id)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get folder has ids
     *
     * @param array $ids
     *
     * @return array
     */
    public function getByIds($ids)
    {
        $qb = $this->createQueryBuilder("fo")
            ->where('fo.deletedAt IS NULL')
            ->andWhere("fo.deletedBy IS NULL");
        $qb->add('where', $qb->expr()->in('fo.id', $ids));
        $qb->andWhere("fo.status =:stat")
        ->setParameter('stat', Constant::STATUS_CREATED);
        return $qb->getQuery()->getResult();
    }
}
