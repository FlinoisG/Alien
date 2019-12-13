<?php

namespace App\Controller;

use App\Service\AuthenticationService;
use App\Service\SecurityService;

class AuthentificationController extends DefaultController
{

    public function login(){
        $username = $_GET['username'];
        $password = $_GET['password'];

        $authentificationService = new AuthenticationService;
        
        $loginStatus = $authentificationService->login($username, $password);
        if ($loginStatus == "username" || $loginStatus == "password"){
            echo $loginStatus;
        } else {
            echo json_encode($loginStatus);
        }
    }

    public function disconnect(){
        echo(session_destroy());
    }

    public function register(){
        $username = $_GET['username'];
        $password = $_GET['password'];
        $email = $_GET['email'];

        $authentificationService = new AuthenticationService;
        $securityService = new SecurityService;

        $sanitizedUsername = $securityService->sanitize($username);
        $sanitizedPassword = $securityService->sanitize($password);
        $sanitizesEmail = $securityService->sanitize($email);

        if (!$securityService->sanitize($username) || 
            !$securityService->validateUsername($sanitizedUsername) ||
            !$authentificationService->checkRegistedUsername($username)){
                var_dump($securityService->sanitize($username));
            die("username");
        } else if (!$securityService->sanitize($email) || 
            !$securityService->validateEmail($sanitizesEmail) ||
            !$authentificationService->checkRegistedEmail($email)){
            die("email");
        } else if (!$securityService->sanitize($password)){
            die("password");
        } else {
            echo "true";
            $authentificationService->register($username, $email, $password);
        };
    } 

    public function checkUsername(){
        $username = $_GET['username'];
        $authentificationService = new AuthenticationService;
        if ($authentificationService->checkRegistedUsername($username)){
            echo "true";
        } else {
            echo "false";
        }
    }

    public function checkEmail(){
        $email = $_GET['email'];
        $authentificationService = new AuthenticationService;
        if ($authentificationService->checkRegistedEmail($email)){
            echo "true";
        } else {
            echo "false";
        }
    }
    

}