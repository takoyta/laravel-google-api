<?php

namespace Websecret\LaravelGoogleApi\Provider\YoutubeAnalytics;

use Websecret\LaravelGoogleApi\AbstractProvider;

class Reports extends AbstractProvider
{

    protected $url = 'https://youtubereporting.googleapis.com/v1/jobs';

    /**
     * @param string $jobId
     * @param array $params
     * @return mixed
     */
    public function getListRequest($jobId, $params = [])
    {
        return $this->getComputedRequest('GET', $this->buildQuery($this->url . '/' . $jobId . '/reports', $params));
    }

    public function download($url, $size = 8 * 1024)
    {
        $request = $this->getComputedRequest('GET', $url);
        $response = $this->getResponse($request);
        $responseBody = $response->getBody();
        while (!$responseBody->eof()) {
            yield $responseBody->read($size);
        }
    }
}