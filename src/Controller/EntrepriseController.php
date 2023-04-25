<?php

namespace App\Controller;



namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Response as BrowserKitResponse;


class EntrepriseController extends AbstractController
{
    #[Route('/entreprise', name: 'app_entreprise')]
    public function index(ManagerRegistry $doctrine): Response
    {
        // récupérer les entreprises de la bdd
        $entreprises = $doctrine->getRepository (Entreprise::class)->findAll();
        $tableau = ["valeur 1", "valeur 2", "valeur 3", "valeur 4"];
        return $this->render('entreprise/index.html.twig', [
    'entreprises' => $entreprises
        ]);
    }
    
    #[Route('/entreprise/add', name: 'add_entreprise')]
    #[Route('/entreprise/{id}/edit', name: 'edit_entreprise')]
 
public function add(ManagerRegistry $doctrine, Entreprise $entreprise = null, Request $request): Response 
{
    // Si l'entreprise n'existe pas
    if(!$entreprise) {
        $entreprise = new Entreprise();
    }
    // construit un formulaire qui se repose sur le $builder dans EntrepriseType 
    $form = $this->createForm(EntrepriseType::class, $entreprise);
    // analyse de ce qui se passe dans mon form
    $form->handleRequest($request);

    if($form->isSubmited() && $form->isValid()) 
    {
        // récupère les données saisient dans le form et ça les inject (setter), ça les hydrate = donne des valeurs
        $entreprise = $form->getData();
        $entityManager = $doctrine->getManager();
        // retiens l'objet en mémoire (prepare)
        $entityManager->persist($entreprise);
        // flush = tirer la chasse d'eau = envoye des données à la BDD (insert into (execute))
        $entityManager->flush();

        return $this->redirectToRoute('app_entreprise');
    }
    
    // vue formulaire add
    return $this->render('entreprise/add.html.twig', [
        // create view = génère la vue du formulaire
        'formAddEntreprise' => $form->createView(),
        'edit' => $entreprise->getId()
    ]);  
}


    #[Route('/entreprise/{id}/delete', name: 'delete_entreprise')]

    public function delete(ManagerRegistry $doctrine, Entreprise $entreprise): Response
{
    $entityManger = $doctrine->getManager();
    $entityManager = remove($entreprise);
    $entityManager->flush();

    return $this->redirectToRoute('app_entreprise');
}









#[Route('/entreprise/{id}', name: 'show_entreprise')]

public function show(Entreprise $entreprise): Response
{
    return $this->render('entreprise/show.html.twig', [
            'entreprise' => $entreprise
        ]);
    }


}