<?php

namespace App\Service;

use App\Model\Service;
use App\Controller\DefaultController;
use App\Service\sqlQueryService;
use App\Service\GUIDService;
use App\Service\SecurityService;
use App\Repository\UserRepository;
use PDO;

/**
 * Auth class for authentication related functions
 */
class AuthenticationService extends Service
{

    /**
     * Create session if username and password matches in the database
     *
     * @param string $providedUsername
     * @param string $providedPassword
     * @return void
     */
    public function login($username, $password)
    {   
        $userRepository = new UserRepository;
        $securityService = new SecurityService;

        $username = $securityService->sanitize($username);
        $password = $securityService->sanitize($password);

        if ($username === false || $password === false){
            return false;
        } else if ($this->checkRegistedUsername($username)){
            return("username");
        } else {
            $DBPassword = $userRepository->getPasswordWithUsername($username);
            $DBId = $userRepository->getIdWithUsername($username);
            if ($securityService->hash_equals($DBPassword, crypt($password, $DBPassword))) {
                //$_SESSION['auth'] = $username;
                //$_SESSION['authId'] = $DBId;
                $cookie = ['auth' => $username, 'authId' => $DBId];
                return $cookie;
            } else {
                return("password");
            }
        }
    }

    /**
     * Check if username already exists in database
     *
     * @param string $username
     * @return bool
     */
    public function checkRegistedUsername($username) {
        $securityService = new SecurityService;
        $defaultController = new DefaultController;
        $userRepository = new UserRepository;
        $available = true;
        if ($username === false) {
            $available = false;
        }
        if (!$securityService->validateUsername($username)){
            $available = false;
        }
        $getUser = $userRepository->getIdWithUsername($username);
        if ($getUser) {
            $available = false;
        }
        if ($available === true) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if email already exists in database
     *
     * @param string $email
     * @return bool
     */
    public function checkRegistedEmail($email) {
        $securityService = new SecurityService;
        $defaultController = new DefaultController;
        $userRepository = new UserRepository;
        $available = true;
        if ($email === false) {
            $available = false;
        }
        if (!$securityService->validateEmail($email)){
            $available = false;
        }
        $getUser = $userRepository->getIdWithEmail($email);
        if ($getUser) {
            $available = false;
        }
        if ($available === true) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * register new user in databse
     *
     * @param string $username
     * @param string $email
     * @param string $password
     * @return void
     */
    public function register($username, $email, $password) {
        $userRepository = new UserRepository;
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $userRepository->registerUser($username, $email, $hashedPassword);
        //copy('../public/assets/img/blankUser100x100.png', '../deposit/User_Avatar/'.$username.'.png');
    }

    /**
     * Generates a GUID as a token for reseting the password, then generates a password reset link and
     * send it to the admin 
     *
     * @param string $email
     * @return void
     */
    public function passwordResetLink($email)
    {
        $userRepository = new UserRepository;
        $user = $userRepository->getEverythingWithEmail($email);
        if ($user != []) {
            $GUIDService = new GUID;
            $resetToken = $GUIDService->getGUID();
            $hashedResetToken = password_hash($resetToken, PASSWORD_BCRYPT);
            $resetExpiration = date("Y-m-d H:i:s", strtotime('+24 hours'));
            $userRepository->updateToken($hashedResetToken, $resetExpiration, $email);
            $to      = $_POST['email'];
            $subject = 'Demande de réinitialisation de mot de passe';
            $message = 'Lien : http://gauthier.tuby.com/P5-Game/public/?p=login.recovery&user=' . $user['0']['username'] . '&token=' . $resetToken;
            $headers = 'From: webmaster@FlinoisG.com' . "\r\n" .
            'Reply-To: webmaster@forterocheblog.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
            mail($to, $subject, $message, $headers);
            return true;
        } else {
            //$loginController = new LoginController;
            //die($loginController->noEmail());
        }
    }

    /**
     * Replace in the database the current password with the one provided
     *
     * @param string $user
     * @param string $password
     * @return void
     */
    public function resetPassword($username, $password)
    {
        //require('../src/Service/PasswordService.php');
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $userRepository = new UserRepository;
        $userRepository->resetPassword($username, $hashedPassword);
    }

    

    

}
