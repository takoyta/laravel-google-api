<?php

namespace Websecret\LaravelGoogleApi\Provider\Youtube;

use Websecret\LaravelGoogleApi\AbstractProvider;

class Channels extends AbstractProvider
{

    protected $url = 'https://www.googleapis.com/youtube/v3/channels';

    /**
     * @param array $params
     * @return mixed
     */
    public function getListRequest($params = [])
    {
        return $this->getComputedRequest('GET', $this->buildQuery($this->url, $params));
    }
}