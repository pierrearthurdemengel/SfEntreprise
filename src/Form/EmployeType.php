<?php

namespace App\Form;

use App\Entity\Employe;
use Symfony\Component\Form\AbstractType;
use src\Form\EmployeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
                    // !!! importer chaque class Componnent
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-control']
                ])
            ->add('prenom', TextType::class, [
                'attr' => ['class' => 'form-control']
                ])
            ->add('dateNaissance', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('dateEmbauche',DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('ville', TextType::class, [
                'attr' => ['class' => 'form-control']
                ])
            ->add('entreprise', EntityType::class, [
                'class' => Entreprise::class,
                'choise_label' => 'raisonSociale',
                'attr' => ['class' => 'form-control']
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'form-control']
                ])
        ;
    }


    /**
* [Route('/employe/add', name: 'add_employe')]
* [Route('/employe/{id}/edit', name: 'edit_employe')]

*/
                                                                  // !!!! importer Fondation/Request
public function add(ManagerRegistry $doctrine, Employe $employe = null, Request $request): Response 
{
        // Si l'employe n'existe pas
        if(!$employe) {
            $employe = new Employe();
        }
    // construit un formulaire qui se repose sur le $builder dans employeType 
    // !!! Importer EmployeType
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
        'formAddEmploye' => $form->createView()
    ]);  
}



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
        ]);
    }

    
}