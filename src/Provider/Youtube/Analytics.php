<?php

namespace Takoyta\LaravelGoogleApi\Provider\Youtube;

use Takoyta\LaravelGoogleApi\AbstractProvider;

class Analytics extends AbstractProvider
{

    protected $url = 'https://youtubeanalytics.googleapis.com/v2';

    /**
     * @return array
     */
    public static function getAuthScopes()
    {
        return array_merge(parent::getAuthScopes(), [
            'https://www.googleapis.com/auth/yt-analytics.readonly',
            'https://www.googleapis.com/auth/yt-analytics-monetary.readonly',
            'https://www.googleapis.com/auth/youtube',
            'https://www.googleapis.com/auth/youtubepartner',
        ]);
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function getReportsRequest($params = [])
    {
        return $this->getComputedRequest('GET', $this->buildQuery($this->url . '/reports', $params));
    }
}