<?php

namespace App\Controller;

use App\Repository\TeacherRepository;
use App\Service\PaginatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/teacher')]
class TeacherController extends AbstractController
{

    #[Route('/', 'teacher_index', methods:['GET'])]
    public function index(TeacherRepository $teacherRepository):Response
    {
        $teachers = $teacherRepository->findAll();
        return $this->render('teacher/index.html.twig', [
            'teachers' =>  $teachers
        ]);
    }

    #[Route('/list/{page}', 'teacher_list', methods:['GET'], requirements: ['page' => '\d+'], defaults: ['page' => 1])]
    public function list(TeacherRepository $repo, Request $request, PaginatorService $paginator):Response
    {
        $queryBuilder = $repo->createQueryBuilder('t')->orderBy('t.createdAt', 'DESC');
        $pagination = $paginator->paginate($queryBuilder, 't.id', $request, 10);
       
        return $this->render('teacher/index.html.twig', [
            'teachers' => $pagination['results'],
            'currentPage' => $pagination['page'],
            'totalPages' => $pagination['totalPages'],
        ]);

    }


}
