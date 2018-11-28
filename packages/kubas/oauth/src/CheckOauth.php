<?php

namespace Kubas\Oauth;

use Closure;
use League\OAuth2\Client\Token\AccessToken;

class CheckOauth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var AccessToken $authCode */
        $authCode = $request->session()->get("oauth_session_code");
        if (!$authCode)
        {
            return $this->RedirectToAuth();
        }

        // exit(var_export($authCode, true));
        // $newToken = OauthProvider::Instance()->getProvider()->getAccessToken("refresh_token", [
        //     "refresh_token" => $authCode->getRefreshToken()
        // ]);
        // session()->put("oauth_session_code", $newToken);

        if ($authCode->hasExpired())
        {

            $newToken = OauthProvider::Instance()->getProvider()->getAccessToken("refresh_token", [
                "refresh_token" => $authCode->getRefreshToken()
            ]);
            session()->put("oauth_session_code", $newToken);

        }
        return $next($request);
    }

    protected function RedirectToAuth()
    {
        $authUrl = OauthProvider::Instance()->getProvider()->getAuthorizationUrl();
        session()->put("oauth_session_key_state", OauthProvider::Instance()->getProvider()->getState());
        return redirect()->guest($authUrl);
    }
}
