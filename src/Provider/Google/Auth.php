<?php

namespace Websecret\LaravelGoogleApi\Provider\Google;

use Websecret\LaravelGoogleApi\AbstractProvider;
use Websecret\LaravelGoogleApi\Exceptions\AuthorizationException;
use Websecret\LaravelGoogleApi\Exceptions\InvalidStateException;

class Auth extends AbstractProvider
{
    /**
     * @param array $scopes
     * @param array $parameters
     * @return mixed
     */
    public function authorize($scopes = [], $parameters = [])
    {
        if (count($scopes)) {
            $parameters['scope'] = $scopes;
        }
        if (isset($parameters['prompt'])) {
            $parameters['approval_prompt'] = null;
        }
        $authorizationUrl = $this->provider->getAuthorizationUrl($parameters);
        $this->setAuthSession();
        return redirect($authorizationUrl);
    }

    /**
     * @param array $params
     * @throws AuthorizationException
     * @throws InvalidStateException
     */
    public function handleAuthorization($params = [])
    {
        if (isset($params['error'])) {
            $error = htmlspecialchars($params['error'], ENT_QUOTES, 'UTF-8');
            throw new AuthorizationException($error);
        }
        if (!isset($params['state']) || ($params['state'] !== $this->getAuthSession())) {
            $this->removeAuthSession();
            throw new InvalidStateException();
        }
        $token = $this->provider->getAccessToken('authorization_code', [
            'code' => $params['code'],
        ]);
        $this->setToken($token);
        return $this->provider->getResourceOwner($token)->toArray();
    }

    protected function setAuthSession()
    {
        session()->put('oauth2state', $this->provider->getState());
    }

    protected function getAuthSession()
    {
        return session()->get('oauth2state');
    }

    protected function removeAuthSession()
    {
        return session()->remove('oauth2state');
    }
}