<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use App\Repository\FournisseurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/produit')]
class ProduitController extends AbstractController
{
    private $produitRepository;
    private $fournisseurRepository;

    public function __construct(ProduitRepository $produitRepository,FournisseurRepository $fournisseurRepository){

        $this->produitRepository = $produitRepository;
        $this->fournisseurRepository = $fournisseurRepository;
    }


    #[Route('/', name: 'produit')]
    public function index(): Response
    {
        $produitsListe = $this->produitRepository->findBy(array('deleted' => null), array('id' => 'DESC'));
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
            'produits' => $produitsListe
        ]);
    }

    #[Route('/add', name:'produitAdd')]
    public function add(Request $request){
        $fournisseurListe = $this->fournisseurRepository->findBy(array('deleted'=> null), array('id' => 'DESC'));
        return $this->render('produit/add.html.twig',[
            'titrePage' => 'Produit' ,
            'fournisseurs' => $fournisseurListe
        ]);
    }

    #[Route('/addCheck' , name:'produitAddCheck')]
    public function addCheck(Request $request,EntityManagerInterface $entityManager){
        
        $produit = new Produit();
        $fournisseur=$this->fournisseurRepository->find($request->get('idFournisseur'));

        $produit->setNomProduit($request->get('nom'));
        $produit->setPrixUnitaire($request->get('prixUnitaire'));
        $produit->setIdFournisseur($fournisseur);

        $entityManager->persist($produit);
        $entityManager->flush();
        return $this->redirectToRoute('produit',[]);
    }


    #[Route('/edit/{id}', name:'produitEdit')]
    public function edit(Request $request,$id){

        $produitToEdit=$this->produitRepository->find($id);
        $fournisseurs=$this->fournisseurRepository->findBy(array('deleted'=> null), array('id' => 'DESC'));
        $oldFournisseur=$this->fournisseurRepository->find($produitToEdit->getIdFournisseur());
                                        
        return $this->render('produit/edit.html.twig',[
            'produit' => $produitToEdit,
            'fournisseur' => $oldFournisseur,
            'fournisseurs' => $fournisseurs
        ]);

    }

    #[Route('/editCheck{id}' , name:'produitEditCheck')]
    public function editCheck(Request $request,$id,EntityManagerInterface $entityManager){
        $produitToEdit=$this->produitRepository->find($id);
        $fournisseur=$this->fournisseurRepository->find($request->get('idFournisseur'));

        $produitToEdit->setNomProduit($request->get('nom'));
        $produitToEdit->setPrixUnitaire($request->get('prixUnitaire'));
        $produitToEdit->setIdFournisseur($fournisseur);

        $entityManager->flush();
        return $this->redirectToRoute('produit',[
            "message" => "Produit modifié"
        ]);


    }

    #[Route('/remove' , name:'produitRemove')]
    public function remove(Request $request,EntityManagerInterface $entityManager){

        $produitToDelete=$this->produitRepository->find($request->get('id'));
        $produitToDelete->setDeleted(1);

        $entityManager->flush();

        return new Response("deleted");
    }
}
