<?php

namespace App\Authentication;

use Illuminate\Contracts\Auth\Authenticatable;

class User implements Authenticatable
{
	private $m_id;
	private $m_name;
	private $m_password;
	private $m_salt;
	private $m_globalId;
	private $m_rememberToken;
	
	public function __construct($data)
	{
		$this->m_id = $data->Id;
		$this->m_name = $data->Name;
		$this->m_password = $data->Password;
		$this->m_salt = $data->Salt;
		$this->m_globalId = $data->GlobalId;
		$this->m_rememberToken = $data->RememberToken;
	}
	
	public function getUserId()
	{
		return $this->m_id;
	}
	
	public function getUserName()
	{
		return $this->m_name;
	}
	
	public function getUserSalt()
	{
		return $this->m_salt;
	}
	
	public function getUserPassword()
	{
		return $this->m_password;
	}
	
	public function setUserRememberToken($token)
	{
		$this->m_rememberToken = $token;
	}

	public function getGlobalId()
    {
        return $this->m_globalId;
    }
	
    /**
     * @return string
     */
    public function getAuthIdentifierName()
    {
		return "id";
        // Return the name of unique identifier for the user (e.g. "id")
    }

    /**
     * @return mixed
     */
    public function getAuthIdentifier()
    {
		return $this->m_id;
        // Return the unique identifier for the user (e.g. their ID, 123)
    }

    /**
     * @return string
     */
    public function getAuthPassword()
    {
		return $this->m_password;
        // Returns the (hashed) password for the user
    }

    /**
     * @return string
     */
    public function getRememberToken()
    {
        // Return the token used for the "remember me" functionality
		return $this->m_rememberToken;
    }

    /**
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {
        // Store a new token user for the "remember me" functionality
		$this->m_rememberToken = $value;
    }

    /**
     * @return string
     */
    public function getRememberTokenName()
    {
		return "m_rememberToken";
        // Return the name of the column / attribute used to store the "remember me" token
    }
}