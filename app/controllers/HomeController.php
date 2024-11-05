<?php

namespace App\controllers;

use League\Plates\Engine;

class HomeController {

   private $templates;

    public function __construct(Engine $engine)
    {
        $this -> templates = $engine;

    }

    public function index() {

        echo $this -> templates -> render('index_view');

    }

}

?>