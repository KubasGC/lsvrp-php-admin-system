<?php

namespace App\Authentication;

use Illuminate\Contracts\Auth\UserProvider as IlluminateUserProvider;
use Illuminate\Support\Facades\DB;
use App\Authentication\User;
use Illuminate\Contracts\Auth\Authenticatable;

class UserProvider implements IlluminateUserProvider
{
    /**
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
		$user = DB::table("lsvrp_admin_accounts")->where(['Id' => $identifier]);
		if ($user->count() == 0) return null;
		return new User($user->first());
        // Get and return a user by their unique identifier
    }

    /**
     * @param  mixed   $identifier
     * @param  string  $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
		$user = DB::table("lsvrp_admin_accounts")->where(['Id' => $identifier, 'RememberToken' => $token]);
		if ($user->count() == 0) return null;
		return new User($user->first());
        // Get and return a user by their unique identifier and "remember me" token
    }

    /**
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string  $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
		DB::table("lsvrp_admin_accounts")->where(['Id' => $user->getUserId()])->update(['RememberToken' => $token]);
		$user->setUserRememberToken($token);
        // Save the given "remember me" token for the given user
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
		$user = DB::table("lsvrp_admin_accounts")->where(['Name' => $credentials["username"]]);
		if ($user->count() == 0) return null;
		return new User($user->first());
        // Get and return a user by looking up the given credentials
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
		$hashedPassword = md5(md5($credentials["password"]).md5($user->getUserSalt()));
		if ($hashedPassword == $user->getUserPassword()) return true;
		return false;
        // Check that given credentials belong to the given user
    }

}