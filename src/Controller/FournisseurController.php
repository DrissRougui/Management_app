<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Repository\FournisseurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/fournisseur')]
class FournisseurController extends AbstractController
{

    private $fournisseurRepository;
   

    public function __construct(FournisseurRepository $fournisseurRepository)
    {
       
		$this->fournisseurRepository = $fournisseurRepository;
    }
    
    
    #[Route('/', name: 'fournisseur')]
    public function index(): Response
    {
        
        $fournisseursListe = $this->fournisseurRepository->findAll(array('deleted' => null), array('id' => 'DESC'));
        return $this->render('fournisseur/index.html.twig', [
            'controller_name' => 'FournisseurController',
            'fournisseurs' => $fournisseursListe
        ]);
    }


    #[Route('/add', name: 'fournisseurAdd')]
    public function add(Request $request){
        return $this->render('fournisseur/add.html.twig',[
            'titre_page' => 'Fournisseur'
        ]);

        
    }
    #[Route('/addCheck', name: 'fournisseurAddCheck')]
    public function add_check( Request $request,EntityManagerInterface $entityManager)
    {
        $fournisseur = new Fournisseur();
        $fournisseur->setNomFournisseur( $request->get('nom'));
        $fournisseur->setAdresse($request->get('adresse'));
        $fournisseur->setCodePostal($request->get('codepostal'));
        $fournisseur->setVille($request->get('ville'));
        
       $entityManager->persist($fournisseur);
       $entityManager->flush();
       
       return $this->redirectToRoute('fournisseur', []
    );
    }

    #[Route('/edit/{id}', name: 'fournisseurEdit')]
    public function edit(Request $request,$id){
        
        $fournisseurToEdit=$this->fournisseurRepository->find($id);
        return $this->render('fournisseur/edit.html.twig',[
                'fournisseur' => $fournisseurToEdit
        ]);
    }


    #[Route('/editCheck/{id}', name: 'fournisseurEditCheck')]
    public function editCheck(Request $request,$id,EntityManagerInterface $entityManager){
        $fournisseurToEdit=$this->fournisseurRepository->find($id);
        $fournisseurToEdit->setNomFournisseur($request->get('nom'));
        $fournisseurToEdit->setAdresse($request->get('adresse'));
        $fournisseurToEdit->setCodePostal($request->get('codepostal'));
        $fournisseurToEdit->setVille($request->get('ville'));

        $entityManager->flush();
        return $this->redirectToRoute('fournisseur',[
            "message" => "Fournisseur modifié"
        ]);

    }

    #[Route('/remove/{id}',name : 'fournisseurDelete')]
    public function remove(Request $request,$id){

    }


}
