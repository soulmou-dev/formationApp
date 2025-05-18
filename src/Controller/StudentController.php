<?php

namespace App\Controller;

use App\Entity\Student;
use App\Entity\User;
use App\Event\StudentRegisteredEvent;
use App\Repository\StudentRepository;
use App\Form\StudentType;
use App\Service\PaginatorService;
use App\Service\PasswordGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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

    #[Route('/list/{page}', 'student_list', methods:['GET'], requirements: ['page' => '\d+'], defaults: ['page' => 1])]
    public function list(StudentRepository $repo, Request $request, PaginatorService $paginator):Response
    {
        $queryBuilder = $repo->createQueryBuilder('s')->orderBy('s.createdAt', 'DESC');
        $pagination = $paginator->paginate($queryBuilder, 's.id', $request, 10);
       
        return $this->render('student/index.html.twig', [
            'students' => $pagination['results'],
            'currentPage' => $pagination['page'],
            'totalPages' => $pagination['totalPages'],
        ]);

    }

    #[Route('/add', 'student_add', methods:['GET', 'POST'])]
    #[Route('/edit/{id}', 'student_edit', methods:['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function form(StudentRepository $repo,
                        Request $request,
                        EntityManagerInterface $em,
                        UserPasswordHasherInterface $passwordHasher,
                        PasswordGenerator $passwordGenerator,
                        EventDispatcherInterface $dispatcher,
                        int $id=null):Response
    {
        $student = $id ? $repo->find($id) : new Student();

        if($id && !$student){
            throw $this->createNotFoundException('Étudiant introuvable.');
        }

        $new = $student->getId() ? false : true; 

        $form = $this->createForm(StudentType::class, $student); 
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            if($new){
                $email = $form->get('email')->getData();

                // Création de l'utilisateur
                $user = new User();
                $user->setEmail($email);
                $user->setRoles(['ROLE_STUDENT']);
                $plainPassword = $passwordGenerator->generate(12);
                $user->setPassword(
                $passwordHasher->hashPassword($user, $plainPassword) // tu peux générer ou demander un mot de passe
                );

                $student->setUser($user);
                $em->persist($user);
            }



            $flasshMessage = !$new ? "l'étudiant a bien été créé." : "l'étudiant a bien été modifié.";
            
            $em->persist($student);
            $em->flush();
            $this->addFlash('success', $flasshMessage);
            if($new){
                $dispatcher->dispatch(new StudentRegisteredEvent($student, $plainPassword), StudentRegisteredEvent::NAME);
            }

            return $this->redirectToRoute('student_list');
        }

        return $this->render('student/edit.html.twig', [
            'form' => $form->createView(),
            'student' => $student
        ]);
    }

    #[Route('/show/{id}', 'student_show', methods:['GET'], requirements: ['id' => '\d+'])]
    public function show(StudentRepository $repo, Request $request, int  $id):Response
    {
        $student = $repo->find($id);
        if (!$student) {
            throw $this->createNotFoundException('Étudiant introuvable.');
        }
       
        return $this->render('student/show.html.twig', [
            'student' => $student
        ]);

    }

    #[Route('/delete/{id}', 'student_delete', methods:['POST'], requirements: ['id' => '\d+'])]
    public function delete(StudentRepository $repo, Request $request, EntityManagerInterface $em, int $id):RedirectResponse
    {
        $student = $repo->find($id);
        if (!$student) {
            throw $this->createNotFoundException('Étudiant introuvable.');
        }

        if (!$this->isCsrfTokenValid('delete_student_' . $id, $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
        }

        $this->addFlash(
            'success',
            "l'étudiant ".$student->getLastname()." ".$student->getFirstname()." a bien été supprimé"
        );

        $em->remove($student);
        $em->flush();

        return $this->redirectToRoute('student_list');
    }

}
