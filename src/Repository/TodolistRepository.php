<?php

namespace App\Repository;

use App\Entity\Todolist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Todolist>
 *
 * @method Todolist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Todolist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Todolist[]    findAll()
 * @method Todolist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TodolistRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Todolist::class);
	}

	public function save(Todolist $entity, bool $flush = false): void
	{
		$this->getEntityManager()->persist($entity);

		if ($flush)
			$this->getEntityManager()->flush();
	}

	public function remove(Todolist $entity, bool $flush = false): void
	{
		$this->getEntityManager()->remove($entity);

		if ($flush)
			$this->getEntityManager()->flush();
	}
}
