<?php

namespace App\Controller;

use App\Service\AvatarService;
use DateTime;

class HomeController extends DefaultController
{

    public function home(){
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        require('src/View/HomeView.php');
    }

}