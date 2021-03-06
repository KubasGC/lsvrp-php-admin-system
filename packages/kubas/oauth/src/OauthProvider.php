<?php
/**
 * Created by PhpStorm.
 * User: Kubas
 * Date: 2018-08-20
 * Time: 10:29
 */

namespace Kubas\Oauth;


use Illuminate\Http\Request;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Request as R2;

class OauthProvider
{

    /** @var \League\OAuth2\Client\Provider\GenericProvider */
    private $m_provider;

    /*
     * Singleton
     */
    public static function Instance()
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new OauthProvider();
        }
        return $inst;
    }

    public function getProvider()
    {
        return $this->m_provider;
    }

    private function __construct()
    {

    }

    public function SetProvider()
    {
        $this->m_provider = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId'                => '616d1fa291402ec1d8f1',    // The client ID assigned to you by the provider
            'clientSecret'            => 'b007a40cce23d925935512187d4b0558',   // The client password assigned to you by the provider
            'redirectUri'             => 'https://admin.lsvrp.ga/kubas/oauth/callback',
            'urlAuthorize'            => 'https://lsvrp.pl/applications/oauth2server/interface/oauth/authorize.php',
            'urlAccessToken'          => 'https://lsvrp.pl/applications/oauth2server/interface/oauth/token.php',
            'urlResourceOwnerDetails' => 'https://lsvrp.pl/applications/oauth2server/interface/oauth/me.php',
        ]);
    }

    public function SetAuth(Request $request)
    {
        if (!$request->has("state") || $request->get("state") !== $request->session()->get("oauth_session_key_state"))
        {
            return response("Niepoprawny status.", 400);
        }

        try {
            $accessToken = $this->m_provider->getAccessToken("authorization_code", [
                "code" => $request->get("code")
            ]);

            // $resourceOwner = $this->m_provider->getResourceOwner($accessToken);
            // var_dump($resourceOwner->toArray());
            // exit();



            session()->put("oauth_session_code", $accessToken);
            // exit(var_export($accessToken, true));
            return redirect()->guest("https://admin.lsvrp.ga");

        }
        catch (IdentityProviderException $e)
        {
            exit($e->getMessage());
        }
    }

    public function getUserInfo()
    {
        $authCode = R2::session()->get("oauth_session_code");
        return $this->m_provider->getResourceOwner($authCode);
    }

}
