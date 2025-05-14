<?php

namespace App\Controller;

use App\Repository\TeacherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

}
