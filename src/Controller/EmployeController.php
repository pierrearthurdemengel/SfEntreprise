<?php

namespace App\Controller;

use Doctrine\Persistance\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeController extends AbstractController
{
    /**
     * [Route('/employe', name: 'app_employe')]
    */
     public function index(ManagerRegistry $doctrine): Response
    {
        // récupérer les employés de la bdd
        $employes = $doctrine->getRepository (Employe::class)->findBy(["ville" => "STRASBOURG"], ["nom" => "ASC"]);
        return $this->render('employe/index.html.twig', [
    'employes' => $employes
        ]);
    }


    /**
     * [Route('/employe/{id}', name: 'show_employe')]
    */
    public function show(Employe $employe): Response
    {
        return $this->render('employe/show.html.twig', [
            'employe' => $employe
        ]);
    }
}
