<?php

namespace Takoyta\LaravelGoogleApi\Provider\Youtube;

use Takoyta\LaravelGoogleApi\AbstractProvider;

class ContentID extends AbstractProvider
{

    protected $url = 'https://content.googleapis.com/youtube/partner/v1';

    /**
     * @return array
     */
    public static function getAuthScopes()
    {
        return array_merge(parent::getAuthScopes(), [
            'https://www.googleapis.com/auth/youtubepartner',
        ]);
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function getClaimSearchRequest($params = [])
    {
        return $this->getComputedRequest('GET', $this->buildQuery($this->url . '/claimSearch', $params));
    }

    /**
     * @param mixed $id
     * @param array $params
     * @return mixed
     */
    public function getAssetRequest($id, $params = [])
    {
        return $this->getComputedRequest('GET', $this->buildQuery($this->url . '/assets/' . $id, $params));
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function getAssetsListRequest($params = [])
    {
        return $this->getComputedRequest('GET', $this->buildQuery($this->url . '/assets', $params));
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function getContentOwnerListRequest($params = [])
    {
        return $this->getComputedRequest('GET', $this->buildQuery($this->url . '/contentOwners', $params));
    }

    /**
     * @param mixed $id
     * @param array $params
     * @return mixed
     */
    public function getContentOwnerRequest($id, $params = [])
    {
        return $this->getComputedRequest('GET', $this->buildQuery($this->url . '/contentOwners/' . $id, $params));
    }
}