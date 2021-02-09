<?php

namespace Takoyta\LaravelGoogleApi\Provider\Youtube;

use Takoyta\LaravelGoogleApi\AbstractProvider;

class PlaylistItems extends AbstractProvider
{

    protected $url = 'https://www.googleapis.com/youtube/v3/playlistItems';

    /**
     * @param array $params
     * @return mixed
     */
    public function getListRequest($params = [])
    {
        return $this->getComputedRequest('GET', $this->buildQuery($this->url, $params));
    }
}