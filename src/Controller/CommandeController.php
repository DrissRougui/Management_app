<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use App\Repository\FournisseurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/commande')]
class CommandeController extends AbstractController
{
    private $produitRepository;
    private $fournisseurRepository;

    public function __construct(ProduitRepository $produitRepository,FournisseurRepository $fournisseurRepository){

        $this->produitRepository = $produitRepository;
        $this->fournisseurRepository = $fournisseurRepository;
    }

    #[Route('/' , name:'commande')]
    public function index():Response
    {
        $fournisseurs = $this->fournisseurRepository->findBy(array('deleted' => null),array('id' => 'DESC'));
        return $this->render('commande/index.html.twig' , [
            'fournisseurs' => $fournisseurs,
            'produits' => []
        ]);

    }

    #[Route('/load', name:'loadProduit')]
    public function load(Request $request){


        function objectToArray($data){
            $outputArray['id'] = $data->getId();
            $outputArray['nomProduit'] = $data->getNomProduit();
            $outputArray['prixUnitaire'] = $data->getPrixUnitaire();
            return $outputArray;
        }

        $idFournisseur=$request->get('id');
        $produits=$this->produitRepository->findBy(array('deleted' => null , 'id_fournisseur' => $idFournisseur));
        $produitsJSON=array();
        foreach($produits as $produit){
            array_push($produitsJSON,objectToArray($produit));
        }
        return new JSONResponse ($produitsJSON);
    }

    #[Route('/check' , name: 'commandeCheck')]
    public function check(){
        return new Response("received");
    }
}