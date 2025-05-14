<?php

namespace App\Controller;

use App\Repository\ClassroomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

}
