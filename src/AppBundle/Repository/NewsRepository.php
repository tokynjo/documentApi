<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * NewsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NewsRepository extends EntityRepository
{
    public function getNewsByFolder($id_folder)
    {
        $qb = $this->createQueryBuilder("n")
            ->select("n.id")
            ->addSelect("DATE_FORMAT(n.createdAt,'%d-%m-%Y') as date_created")
            ->addSelect("DATE_FORMAT(n.createdAt,'%h:%i') as time_created")
            ->addSelect("type.id as type_id")
            ->addSelect("type.label as type_label")
            ->addSelect("usr.id as user_id")
            ->addSelect("usr.username as user_name")
            ->addSelect("usr.firstname as user_firstname")
            ->addSelect("p.id as parent")
            ->addSelect("n.data as data")
            ->innerJoin("n.folder", "d")
            ->innerJoin("n.type", "type")
            ->innerJoin("n.user", "usr")
            ->leftJoin("n.parent","p")
            ->where("d.id =:id_folder")
            ->orderBy('n.createdAt', 'DESC')
            ->setParameter("id_folder", $id_folder);
        $data = $qb->getQuery()->getResult();
        foreach ($data as $key => $rows) {
            $comments = $this->_em->getRepository("AppBundle:Comment")->getCommentByNew($rows['id']);
            $data[$key]["comment"] = $comments;
            if (isset($rows['data']['id_folder'])) {
                $folder = $this->_em->getRepository("AppBundle:Folder")->find($rows['data']['id_folder']);
                if ($folder) {
                    $data[$key]['folder_name'] = $folder->getName();
                    $data[$key]['id_folder_created'] = $folder->getId();
                }
            }
            if (isset($rows['data']['file'])) {
                $file = $this->_em->getRepository("AppBundle:File")->getNameFile($rows['data']['file']);
                $data[$key]['files_uploads'] = $file;
            }
            if (isset($rows['data']['id_project'])) {
                $project = $this->_em->getRepository("AppBundle:Project")->find($rows['data']['id_project']);
                if ($project) {
                    $data[$key]['project_id'] = $project->getId();
                    $data[$key]['project_name'] = $project->getLibelle();
                }
            }
            unset($data[$key]['data']);
        }
        return $data;
    }
}
