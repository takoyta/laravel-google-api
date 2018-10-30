<?php

namespace Websecret\LaravelGoogleApi\Provider\YoutubeAnalytics;

use Websecret\LaravelGoogleApi\AbstractProvider;

class Jobs extends AbstractProvider
{

    protected $url = 'https://youtubereporting.googleapis.com/v1/jobs';

    /**
     * @param array $params
     * @return mixed
     */
    public function getListRequest($params = [])
    {
        return $this->getComputedRequest('GET', $this->buildQuery($this->url, $params));
    }
}