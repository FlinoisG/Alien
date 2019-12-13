<?php

namespace App\Entity;

class UserEntity
{
	protected $id;
	protected $username;
	protected $email;
	protected $password;
	protected $token;
	protected $tokenExp;

    public function __construct($args)
    {
		$this->hydrate($args);
    }

	/**
     * Hydrate the entity with specified arguments
     *
     * @param array $args
     */
	private function hydrate ($args)
	{
        if (is_array($args)){
            if (isset($args["id"])){
                $this->id = $args["id"];
            }
            if (isset($args["username"])){
                $this->username = $args["username"];
            }
            if (isset($args["email"])){
                $this->email = $args["email"];
            }
            if (isset($args["password"])){
                $this->password = $args["password"];
            }
            if (isset($args["token"])){
                $this->token = $args["token"];
            }
            if (isset($args["token_exp"])){
                $this->tokenExp = $args["token_exp"];
            }
        }
    }

	/**
	 * Get the value of id
	 */ 
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Get the value of username
	 */ 
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * Set the value of username
	 *
	 * @return  self
	 */ 
	public function setUsername($username)
	{
		$this->username = $username;

		return $this;
	}

	/**
	 * Get the value of email
	 */ 
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * Set the value of email
	 *
	 * @return  self
	 */ 
	public function setEmail($email)
	{
		$this->email = $email;

		return $this;
	}

	/**
	 * Get the value of password
	 */ 
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * Set the value of password
	 *
	 * @return  self
	 */ 
	public function setPassword($password)
	{
		$this->password = $password;

		return $this;
	}

	/**
	 * Get the value of token
	 */ 
	public function getToken()
	{
		return $this->token;
	}

	/**
	 * Set the value of token
	 *
	 * @return  self
	 */ 
	public function setToken($token)
	{
		$this->token = $token;

		return $this;
	}

	/**
	 * Get the value of token_exp
	 */ 
	public function getToken_exp()
	{
		return $this->token_exp;
	}

	/**
	 * Set the value of token_exp
	 *
	 * @return  self
	 */ 
	public function setToken_exp($token_exp)
	{
		$this->token_exp = $token_exp;

		return $this;
	}

	
}