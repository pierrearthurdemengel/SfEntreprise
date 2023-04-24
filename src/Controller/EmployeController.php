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
        $employes = $doctrine->getRepository(Employe::class)->findBy(["ville" => "STRASBOURG"], ["nom" => "ASC"]);
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


    /**
* [Route('/employe/add', name: 'add_employe')]
* [Route('/employe/{id}/edit', name: 'edit_employe')]
*/
public function add(ManagerRegistry $doctrine, Employe $employe = null, Request $request): Response 
{
    // Si l'employe n'existe pas
    if(!$employe) {
        $employe = new Employe();
    }
    // construit un formulaire qui se repose sur le $builder dans EntrepriseType 
    $form = $this->createForm(EmployeType::class, $employe);
    // analyse de ce qui se passe dans mon form
    $form->handleRequest($request);

    if($form->isSubmited() && $form->isValid()) 
    {
        // récupère les données saisient dans le form et ça les inject (setter), ça les hydrate = donne des valeurs
        $employe = $form->getData();
        $entityManager = $doctrine->getManager();
        // retiens l'objet en mémoire (prepare)
        $entityManager->persist($employe);
        // flush = tirer la chasse d'eau = envoye des données à la BDD (insert into (execute))
        $entityManager->flush();

        return $this->redirectToRoute('app_employe');
    }

    // vue formulaire add
    return $this->render('employe/add.html.twig', [
        // create view = génère la vue du formulaire
        'formAddEmploye' => $form->createView(),
        'edit' => $employe->getId()
    ]);  
}








    /**
     * [Route('/employe/{id}/delete', name: 'delete_employe')]
     */
    public function delete(ManagerRegistry $doctrine, Employe $employe): Response
    {
        $entityManger = $doctrine->getManager();
        $entityManager = remove($employe);
        $entityManager->flush();

        return $this->redirectToRoute('app_employe');
    }




