<?php

namespace App\Classe;
use App\Entity\Category;

//permet de créer un objet de recherche 
class Search 
{
    /**
     * @var string
     */
    public $string = '';

    /**
     * @var Category[]
     */
    public $categories = [];

    public function __toString()
    {
        return $this->string;
    }
}