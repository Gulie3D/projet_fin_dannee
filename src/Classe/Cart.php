<?php
namespace App\Classe;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart 
{
    private $session;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $session)
    {
        $this->session = $session->getSession();
        $this->entityManager = $entityManager;
    }

    //permet d'ajouter des produits au panier
    public function add($id)
    {
        $cart = $this->session->get('cart', []); //récupère la session cart 

        if(!empty($cart[$id])) //si il y a id produit déjà existant dans le panier
        {
            $cart[$id]++; //alors on ajoute +1 dans la quantity
        }
        else
        {
            $cart[$id] = 1;
        }

        $this->session->set('cart', $cart);
    }

    //récupère le panier
    public function get()
    {
        return $this->session->get('cart');
    }

    //suprime le panier et ces produits
    public function remove()
    {
        return $this->session->remove('cart');
    }

    //supprime un produit du panier 
    public function delete($id)
    {
        $cart = $this->session->get('cart', []); //récupère la session cart 

        unset($cart[$id]); //retire de mon tableau cart un produit sélectionner avec son id
        
        return $this->session->set('cart', $cart);
    }

    //enlève -1 en quantité sur un produit dans le panier
    public function decrease($id)
    { 
        $cart = $this->session->get('cart', []); //récupère la session cart 

        //vérifier si la quantité de notre produit est supérieur à 1
        if($cart[$id] > 1 )
        {
            $cart[$id]--; //alors on retire 1 à la quantité 
        }
        else //sinon supprimer mon produit
        {
            unset($cart[$id]); //retire de mon tableau cart un produit sélectionner avec son id
        }
        
        return $this->session->set('cart', $cart);
    }

    //recupère toutes les infos du panier
    public function getFull()
    {
        $cartComplete = [];

        //si le panier n'est pas vide alors on l'affiche 
        if($this->get())
        {
            //retourne les produits qui sont dans le panier, on lui demande le produit et sa quantitée
            foreach($this->get() as $id => $quantity)
            {
                $productObject = $this->entityManager->getRepository(Product::class)->findOneById($id);

                if(!$productObject) //si le produit n'existe pas 
                {
                    $this->delete($id);
                    continue; //continue avec la boucle pour le produit suivant
                }
                $cartComplete[] = [
                    'product' => $productObject,
                    'quantity' => $quantity 
                ];
            }
        }

        return $cartComplete;
    }
}