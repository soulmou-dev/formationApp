<?php

namespace App\Controller;

use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/student')]
class StudentController extends AbstractController
{

    #[Route('/', 'student_index', methods:['GET'])]
    public function index(StudentRepository $studentRepository):Response
    {
        $students = $studentRepository->findAll();
        return $this->render('student/index.html.twig', [
            'students' =>  $students
        ]);
    }

}
