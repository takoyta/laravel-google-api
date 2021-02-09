<?php

namespace Takoyta\LaravelGoogleApi\Provider\YoutubeAnalytics;

use Takoyta\LaravelGoogleApi\AbstractProvider;

class ReportTypes extends AbstractProvider
{

    protected $url = 'https://youtubereporting.googleapis.com/v1/reportTypes';

    /**
     * @param array $params
     * @return mixed
     */
    public function getListRequest($params = [])
    {
        return $this->getComputedRequest('GET', $this->buildQuery($this->url, $params));
    }
}