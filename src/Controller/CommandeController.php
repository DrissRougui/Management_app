<?php

namespace App\Controller;

use \Datetime;
use \stdClass;
use App\Entity\Produit;
use App\Entity\Commande;
use App\Entity\LigneCommande;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;
use App\Repository\LigneCommandeRepository;
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
    private $commandeRepository;
    private $ligneCommandeRepository;

    public function __construct(
        ProduitRepository $produitRepository,
        FournisseurRepository $fournisseurRepository,
        CommandeRepository $commandeRepository,
        LigneCommandeRepository $ligneCommandeRepository){

        $this->produitRepository = $produitRepository;
        $this->fournisseurRepository = $fournisseurRepository;
        $this->ligneCommandeRepository=$ligneCommandeRepository;
        $this->commandeRepository=$commandeRepository;
    }

    #[Route('/', name: 'commande')]
    public function commandList(){
        $commandes = $this->commandeRepository->findAll();

        foreach($commandes as $commande){
            $commande->countLignes=$commande->getLigneCommandesCount();
        }
        return $this->render('commande/index.html.twig',[
            'commandes' => $commandes
        ]);
    }


    #[Route('/add' , name:'commandeAdd')]
    public function index():Response
    {
        $fournisseurs = $this->fournisseurRepository->findBy(array('deleted' => null),array('id' => 'DESC'));
        return $this->render('commande/add.html.twig' , [
            'fournisseurs' => $fournisseurs
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
    public function check(Request $request,EntityManagerInterface $entityManager){
        $somme=0;
        $panierArray=$request->get('panier');
        $commande=new Commande();
        $commande->setDateCommande(new DateTime());
        $entityManager->persist($commande);
        $ligneArray=[];
        $fournisseurArray=[];
        
        foreach($panierArray as $panierElement){
            $produit=$this->produitRepository->find($panierElement["id"]);
            $quantity=$panierElement["quantity"];

            $ligne= new LigneCommande();
            $ligne->setIdCommande($commande);
            $ligne->setIdProduit($produit);
            $ligne->setPrixUnitaire($produit->getPrixUnitaire());
            $ligne->setQuantity($quantity);
            $entityManager->persist($ligne);
            array_push($ligneArray,$ligne);
            array_push($fournisseurArray,$produit->getIdFournisseur());

            
        }

        $fournisseurArray=array_unique($fournisseurArray);
        $outputArray=[];
        

        //format outputArray
        foreach($fournisseurArray as $f){

            $outputArrayElement=new stdClass();
            $outputArrayElement->fournisseur=$f;
            $lignesParFournisseur=[];
            foreach($ligneArray as $l){               
                if ($f->getId()==$l->getIdProduit()->getIdFournisseur()->getId()){
                    array_push($lignesParFournisseur,$l);
                }
            }
            $outputArrayElement->lignes=$lignesParFournisseur;
            array_push($outputArray,$outputArrayElement);
        }

        
        

        $entityManager->flush();
        return $this->render('commande/receipt.html.twig' , [
            'commandeData' => $outputArray
            
        ]);
    }



    #[Route('/details/{id}', name:'commandeDetails')]
    public function details($id){
        $commande=$this->commandeRepository->find($id);
        $lignes=$commande->getLigneCommandes()->toArray();
        $fournisseurArray=[];
        $outputArray=[];

        foreach($lignes as $ligne){
            array_push($fournisseurArray,$ligne->getIdProduit()->getIdFournisseur());

        }

        $fournisseurArray=array_unique($fournisseurArray);



        //Format outputArray
        foreach($fournisseurArray as $f){
            $outputArrayElement=new stdClass();
            $outputArrayElement->fournisseur=$f;
            $lignesParFournisseur=[];
            foreach($lignes as $l){               
                if ($f->getId()==$l->getIdProduit()->getIdFournisseur()->getId()){
                    array_push($lignesParFournisseur,$l);
                }
            }
            $outputArrayElement->lignes=$lignesParFournisseur;
            array_push($outputArray,$outputArrayElement);

        }


        return $this->render('commande/details.html.twig' , [
            'commandeData' => $outputArray
        ]);
    }
}