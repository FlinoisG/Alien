<?php

namespace App\Repository;

use PDO;
use App\Model\Repository;
use App\Service\sqlQueryService;
use App\Entity\UserEntity;

class UserRepository extends Repository
{

    /**
     * gets all users from database and return them as user entities in a array
     *
     * @return array Returns an array of User entities
     */
    public function getUsers()
    {
        $sqlQueryService = new sqlQueryService();
        $query = "SELECT * FROM game_users";
        $users = $sqlQueryService->sqlQueryService($query);
        $userEntities = [];
        for ($i=0; $i < sizeof($users); $i++) { 
            $userParameters = [
                'id'=>$users[$i]["id"], 
                'username'=>$users[$i]["username"], 
                'email'=>$users[$i]["email"]
            ];
            $userEntities[$i] = new UserEntity($userParameters);
        }
        return $userEntities;
    }

    
    /**
     * Get an encrypted password from the database
     * with the corresponding username
     *
     * @param string $username
     * @return string encrypted password
     */
    public function getPasswordWithUsername($username)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT password FROM game_users WHERE username = :username");
        $query->bindParam(":username", $username, PDO::PARAM_STR);
        $query->execute();
        $response = $query->fetch();
        return $response[0];
    }

    /**
     * Get the Id corresponding to a username
     *
     * @param string $username
     * @return string Id
     */
    public function getIdWithUsername($username)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT id FROM game_users WHERE username = :username");
        $query->bindParam(":username", $username, PDO::PARAM_STR);
        $query->execute();
        $response = $query->fetch();
        return $response[0];
    }

    /**
     * Get the Id corresponding to a email
     *
     * @param string $email
     * @return string Id
     */
    public function getIdWithEmail($email)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT id FROM game_users WHERE email = :email");
        $query->bindParam(":email", $email, PDO::PARAM_STR);
        $query->execute();
        $response = $query->fetch();
        return $response[0];
    }

    
    /**
     * Get an username from the database
     * with the corresponding Id
     *
     * @param mixed $id
     * @return void
     */
    public function getUsernameWithId($id){
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT username FROM game_users WHERE id = :id");
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        $query->execute();
        $response = $query->fetch();
        return $response[0];
    }


    /**
     * Get the token corresponding to
     * a username
     *
     * @param string $username
     * @return string token
     */
    public function getTokenWithUsername($username)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT token FROM game_users WHERE username = :username");
        $query->bindParam(":username", $username, PDO::PARAM_STR);
        $query->execute();
        $response = $query->fetch();
        return $response[0];
    }

    /**
     * Get the whole entry corresponding to
     * an email
     *
     * @param string $email
     * @return array
     */
    public function getEverythingWithEmail($email)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT * FROM game_users WHERE email = :email");
        $query->bindParam(":email", $email, PDO::PARAM_STR);
        $query->execute();
        $response = $query->fetchAll();
        return $response;
    }


    /**
     * Registers a new user into the database
     *
     * @param string $username
     * @param string $email
     * @param string $hashedPassword
     * @return void
     */
    public function registerUser($username, $email, $hashedPassword)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("INSERT INTO game_users (username, email, password)
        VALUES (:username, :email, :hashedPassword)");
        $query->bindParam(":username", $username, PDO::PARAM_STR);
        $query->bindParam(":email", $email, PDO::PARAM_STR);
        $query->bindParam(":hashedPassword", $hashedPassword, PDO::PARAM_STR);
        $query->execute();
    }

    /**
     * update token and token_exp at the
     * specified email
     *
     * @param string $hashedResetToken Encrypted 
     * token that will be sent to user in case he
     * forgot his password
     * @param string $resetExpiration 
     * @param string $email
     * @return void
     */
    public function updateToken($hashedResetToken, $resetExpiration, $email)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("UPDATE game_users SET token = :hashedResetToken, token_exp = :resetExpiration WHERE email = :email");
        $query->bindParam(":hashedResetToken", $hashedResetToken, PDO::PARAM_STR);
        $query->bindParam(":resetExpiration", $resetExpiration, PDO::PARAM_STR);
        $query->bindParam(":email", $email, PDO::PARAM_STR);
        $query->execute();
    }

    /**
     * Replace in the database the current
     * password with the one provided
     *
     * @param string $user
     * @param string $password
     * @return void
     */
    public function resetPassword($username, $hashedPassword)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare('UPDATE game_users SET password = \':hashedPassword\', token = \'\', token_exp = \'\' WHERE username = :username');
        $query->bindParam(":hashedPassword", $hashedPassword, PDO::PARAM_STR);
        $query->bindParam(":username", $username, PDO::PARAM_STR);
        $query->execute();
    }

    /**
     * gets every id and usernames from game_users
     *
     * @return array
     */
    public function getAllUsername(){
        $sqlQueryService = new sqlQueryService();
        $query = "SELECT id, username FROM game_users";
        $results = $sqlQueryService->sqlQueryService($query);
        $usernames = [];
        foreach ($results as $result) {
            $usernames[$result["id"]] = $result["username"];
        }
        return $usernames;
    }

}