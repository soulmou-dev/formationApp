<?php

namespace App\Controller;

use App\Repository\ModuleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/module')]
class ModuleController extends AbstractController
{

    #[Route('/', 'module_index', methods:['GET'])]
    public function index(ModuleRepository $moduleRepository):Response
    {
        $modules = $moduleRepository->findAll();
        return $this->render('module/index.html.twig', [
            'modules' =>  $modules
        ]);
    }

}
