<?php
namespace AppBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * Class BaseManager
 * @package AppBundle\Manager
 */
abstract class BaseManager
{
    /**
     * @var EntityManagerInterface $entityManager
     */
    protected $entityManager;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $repository;

    /**
     *
     * @var type
     */
    protected $class;

    /**
     *
     * @param EntityManagerInterface $entityManager
     * @param Router                 $router
     *
     */
    public function __construct(EntityManagerInterface $entityManager, $class)
    {
        $this->entityManager = $entityManager;
        $this->class = $class;
        $this->repository = $this->entityManager->getRepository($this->class);
    }

    /**
     * @param $entity
     *
     * @return mixed
     */
    public function save($entity)
    {
        $this->entityManager->persist($entity);

        return $entity;
    }

    /**
     * @param $entity
     *
     * @return mixed
     */
    public function saveAndFlush($entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    /**
     * @param $entity
     *
     * @return mixed
     */
    public function delete($entity)
    {
        $this->entityManager->remove($entity);
        $this->flushAndClear();

        return true;
    }

    /**
     * @return mixed
     */
    public function flushAndClear()
    {
        $this->entityManager->flush();
    }

    public function createNew()
    {
        $class = $this->class;

        return new $class();
    }

    public function findAll()
    {
        return $this->repository->findAll();
    }

    public function find($id)
    {
        return  $this->repository->findOneBy(['id' => $id]);
    }

    public function findBy(array $_criteria, array $_orderBy = null, $_limit = null, $_offset = null)
    {
        return $this->repository->findBy($_criteria, $_orderBy, $_limit, $_offset);
    }

    public function findOneBy(array $criteria)
    {
        return  $this->repository->findOneBy($criteria);
    }
}
