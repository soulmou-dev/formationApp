<?php

namespace App\Controller;

use App\Repository\ClassroomRepository;
use App\Service\PaginatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/classroom')]
class ClassroomController extends AbstractController
{

    #[Route('/', 'classroom_index', methods:['GET'])]
    public function index(ClassroomRepository $classroomRepository):Response
    {
        $classrooms = $classroomRepository->findAll();
        return $this->render('classroom/index.html.twig', [
            'classrooms' =>  $classrooms
        ]);
    }

    #[Route('/list/{page}', 'classroom_list', methods:['GET'], requirements: ['page' => '\d+'], defaults: ['page' => 1])]
    public function list(ClassroomRepository $repo, Request $request, PaginatorService $paginator):Response
    {
        $queryBuilder = $repo->createQueryBuilder('p')->orderBy('p.createdAt', 'DESC');
        $pagination = $paginator->paginate($queryBuilder, $request, 10);
       
        return $this->render('classroom/index.html.twig', [
            'classrooms' => $pagination['results'],
            'currentPage' => $pagination['page'],
            'totalPages' => $pagination['totalPages'],
        ]);

    }

}
