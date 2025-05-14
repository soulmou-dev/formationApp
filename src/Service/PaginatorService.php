<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

class PaginatorService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function paginate(QueryBuilder $queryBuilder, string $countParam, Request $request, int $perPage = 10):array
    {
        $page = $request->attributes->getInt('page', 1); // Récupérer la page depuis les paramètres de route
        $offset = ($page - 1) * $perPage;

        $countQB = clone $queryBuilder;
        $totalItems = $countQB->select("COUNT($countParam)")->getQuery()->getSingleScalarResult();
        // Appliquer OFFSET et LIMIT
        $query = $queryBuilder
            ->setFirstResult($offset)   // OFFSET
            ->setMaxResults($perPage);  // LIMIT

        $results = $query->getQuery()->getResult();
  
        return [
            'results' => $results,
            'totalItems' => $totalItems,
            'perPage' => $perPage,
            'page' => $page,
            'totalPages' => (int)ceil($totalItems / $perPage),
        ];
    }
}