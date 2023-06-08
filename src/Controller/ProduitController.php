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
        $produitsListe = $this->produitRepository->findAll(array('deleted' => null), array('id' => 'DESC'));
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
            'produits' => $produitsListe
        ]);
    }

    #[Route('/add', name:'produitAdd')]
    public function add(Request $request){
        $fournisseurListe = $this->fournisseurRepository->findAll(array('deleted'=> null), array('id' => 'DESC'));
        return $this->render('produit/add.html.twig',[
            'titrePage' => 'Produit' ,
            'fournisseurs' => $fournisseurListe
        ]);
    }

    #[Route('/addCheck' , name:'produitAddCheck')]
    public function addCheck(Request $request,EntityManagerInterface $entitymanager){
        
    }
}
