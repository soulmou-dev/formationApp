<?php

namespace App\Controller;

use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/course')]
class CourseController extends AbstractController
{

    #[Route('/', 'course_index', methods:['GET'])]
    public function index(CourseRepository $courseRepository):Response
    {
        $courses = $courseRepository->findAll();
        return $this->render('course/index.html.twig', [
            'courses' =>  $courses
        ]);
    }

}
