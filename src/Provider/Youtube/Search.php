<?php

namespace Websecret\LaravelGoogleApi\Provider\Youtube;

use Websecret\LaravelGoogleApi\AbstractProvider;
use Psr\Http\Message\RequestInterface;

class Search extends AbstractProvider
{

    protected $url = 'https://www.googleapis.com/youtube/v3/search';

    /**
     * @param array $params
     * @return RequestInterface
     */
    public function getListRequest($params = [])
    {
        return $this->getComputedRequest('GET', $this->buildQuery($this->url, $params));
    }
}