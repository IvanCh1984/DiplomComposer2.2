<?php

namespace App\controllers;

//use Model\QueryBuilder;
use League\Plates\Engine;

class ShowUserController {

    private $templates;

    //protected $queryFactory;

    public function __construct(Engine $engine/*, QueryBuilder $queryFactory*/)
    {
        $this -> templates = $engine;

        //$this->queryFactory = $queryFactory;

    }

    public function index() {

        //$this -> queryFactory -> getAll('users');

        echo $this -> templates -> render('users');

    }

    public function users() {

        echo $this -> templates -> render('users');
 

       }
}





?>