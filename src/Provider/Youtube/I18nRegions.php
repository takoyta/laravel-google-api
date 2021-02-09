<?php

namespace Takoyta\LaravelGoogleApi\Provider\Youtube;

use Takoyta\LaravelGoogleApi\AbstractProvider;

class I18nRegions extends AbstractProvider
{

    protected $url = 'https://www.googleapis.com/youtube/v3/i18nRegions';

    /**
     * @param array $params
     * @return mixed
     */
    public function getListRequest($params = [])
    {
        return $this->getComputedRequest('GET', $this->buildQuery($this->url, $params));
    }
}