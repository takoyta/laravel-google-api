<?php

namespace Websecret\LaravelGoogleApi;

use League\OAuth2 as OAuth2;
use Psr\Http\Message\RequestInterface;

abstract class AbstractProvider
{
    protected $provider;
    protected $token;
    protected $useAuthenticatedRequest;

    protected $apiKey = null;

    /**
     * AbstractProvider constructor.
     * @param array $options
     * @param array $collaborators
     * @param bool $offline
     */
    public function __construct($options = [], $collaborators = [], $offline = true)
    {
        $options = array_merge($options, $offline ? [
            'accessType' => 'offline',
        ] : []);
        $this->useAuthenticatedRequest = false;
        if (array_has($options, ['clientId', 'clientSecret'])) {
            $this->useAuthenticatedRequest = true;
        }
        $this->apiKey = array_get($options, 'apiKey');
        $this->provider = new OAuth2\Client\Provider\Google($options, $collaborators);
    }

    /**
     * @param mixed $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $this->getTokenObject($token);
        return $this;
    }

    /**
     * @param $token
     * @param null $refreshToken
     * @return OAuth2\Client\Token\AccessToken
     */
    protected function getTokenObject($token, $refreshToken = null)
    {
        if ($token instanceof OAuth2\Client\Token\AccessToken) return $token;
        if (!is_array($token)) {
            $token = ['access_token' => $token];
        }
        if ($refreshToken) {
            $token['refresh_token'] = $refreshToken;
        }
        return new OAuth2\Client\Token\AccessToken($token);
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $refreshToken
     * @return $this
     */
    public function refreshToken($refreshToken)
    {
        $grant = new OAuth2\Client\Grant\RefreshToken();
        $token = $this->provider->getAccessToken($grant, ['refresh_token' => $refreshToken]);
        $newToken = $this->getTokenObject($token->jsonSerialize(), $refreshToken);
        $this->setToken($newToken);
        return $this;
    }

    /**
     * @param $token
     * @return bool
     */
    public function checkToken($token)
    {
        try {
            return $this->getTokenInfo($token) ? true : false;
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * @param $token
     * @return mixed
     */
    public function getTokenInfo($token)
    {
        if ($token instanceof OAuth2\Client\Token\AccessToken) $token = $token->getToken();
        $url = 'https://www.googleapis.com/oauth2/v1/tokeninfo?' . http_build_query(['access_token' => $token]);
        $request = $this->getRequest('GET', $url);
        return $this->getParsedResponse($request);
    }

    /**
     * @param $token
     * @param null $refreshToken
     * @param bool $forceRemoteCheck
     * @return $this
     */
    public function setTokenAndRefreshIfNeeded($token, $refreshToken = null, $forceRemoteCheck = false)
    {
        $token = $this->getTokenObject($token, $refreshToken);
        try {
            $hasExpired = $token->hasExpired();
        } catch (\Throwable $e) {
            $forceRemoteCheck = true;
            $hasExpired = true;
        }
        if ($forceRemoteCheck) {
            $hasExpired = $this->checkToken($token) ? false : true;
        }
        if ($hasExpired) {
            $this->refreshToken($token->getRefreshToken());
        } else {
            $this->setToken($token);
        }
        return $this;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $options
     * @return RequestInterface
     */
    protected function getRequest($method, $url, $options = [])
    {
        $request = $this->provider->getRequest($method, $url, $options);
        if (is_null($this->apiKey)) {
            return $request;
        }
        $uri = $request->getUri();
        parse_str($uri->getQuery(), $query);
        $query['key'] = $this->apiKey;
        return $request->withUri($uri->withQuery(http_build_query($query)));
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $options
     * @return RequestInterface
     */
    protected function getAuthenticatedRequest($method, $url, $options = [])
    {
        return $this->provider->getAuthenticatedRequest($method, $url, $this->token, $options);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $options
     * @return RequestInterface
     */
    protected function getComputedRequest($method, $url, $options = [])
    {
        return $this->useAuthenticatedRequest
            ? $this->getAuthenticatedRequest($method, $url, $options)
            : $this->getRequest($method, $url, $options);
    }

    /**
     * @param RequestInterface $request
     * @return mixed
     */
    public function getResponse($request)
    {
        return $this->provider->getResponse($request);
    }

    /**
     * @param RequestInterface $request
     * @return mixed
     */
    public function getParsedResponse($request)
    {
        return $this->provider->getParsedResponse($request);
    }

    /**
     * @param $url string
     * @param array $params
     * @return string
     */
    protected function buildQuery($url, $params = [])
    {
        if (count($params)) {
            $url .= '?' . http_build_query($params);
        }
        return $url;
    }

    /**
     * @return array
     */
    public static function getAuthParameters()
    {
        return [
            'access_type' => 'offline',
            'include_granted_scopes' => 'true',
            'prompt' => 'consent select_account',
        ];
    }

    /**
     * @param array $parameters
     * @return array
     */
    public static function mergeAuthParameters($parameters)
    {
        return array_unique(array_merge(static::getAuthParameters(), $parameters));
    }

    /**
     * @return array
     */
    public static function getAuthScopes()
    {
        return [
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/userinfo.profile',
        ];
    }

    /**
     * @param array $scopes
     * @return array
     */
    public static function mergeAuthScopes($scopes)
    {
        return array_unique(array_merge(static::getAuthScopes(), $scopes));
    }

    /**
     * @param $scopes
     * @return bool
     */
    public static function checkAuthScopes($scopes)
    {
        if (is_string($scopes)) {
            $scopes = explode(' ', $scopes);
        }
        return count(array_diff(static::getAuthScopes(), $scopes)) ? false : true;
    }
}