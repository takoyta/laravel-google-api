<?php

namespace Takoyta\LaravelGoogleApi\Provider\Youtube;

use Takoyta\LaravelGoogleApi\AbstractProvider;

class VideoCategories extends AbstractProvider
{

    protected $url = 'https://www.googleapis.com/youtube/v3/videoCategories';

    /**
     * @param array $params
     * @return mixed
     */
    public function getListRequest($params = [])
    {
        return $this->getComputedRequest('GET', $this->buildQuery($this->url, $params));
    }
}