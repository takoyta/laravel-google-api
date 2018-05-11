<?php

namespace Websecret\LaravelGoogleApi;

use Psr\Http\Message\RequestInterface;

class Paginator
{
    protected $provider;
    protected $nextPageToken = null;
    protected $itemsKey;
    protected $total = 0;

    /**
     * Paginator constructor.
     * @param AbstractProvider $provider
     * @param null $nextPageToken
     * @param string $itemsKey
     */
    public function __construct(AbstractProvider $provider, $nextPageToken = null, $itemsKey = 'items')
    {
        $this->provider = $provider;
        $this->itemsKey = $itemsKey;
        if (!is_null($nextPageToken)) {
            $this->nextPageToken = $nextPageToken;
        }
    }

    /**
     * @param RequestInterface $request
     * @return \Generator|RequestInterface[]
     */
    public function paginate(RequestInterface $request)
    {
        do {
            if ($this->nextPageToken) {
                $request = $this->addPageTokenToRequest($request);
            }
            $response = $this->provider->getParsedResponse($request);
            $count = count(array_get($response, $this->itemsKey, []));
            if (!$count) break;
            $this->total += $count;
            $total = array_get($response, 'pageInfo.totalResults');
            $this->nextPageToken = array_get($response, 'nextPageToken');
            yield $request;
        } while ($this->nextPageToken && ($this->total < $total));
    }

    /**
     * @param RequestInterface $request
     * @return RequestInterface
     */
    protected function addPageTokenToRequest(RequestInterface $request)
    {
        $uri = $request->getUri();
        parse_str($uri->getQuery(), $query);
        $query['pageToken'] = $this->nextPageToken;
        return $request->withUri($uri->withQuery(http_build_query($query)));
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }
}