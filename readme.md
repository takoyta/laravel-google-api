## Laravel 5 Google API Provider

### Install

Require this package with composer using the following command:

```bash
composer require websecret/laravel-google-api
```

### Usage

```php
$youtubeChannelsProvider = new \Websecret\LaravelGoogleApi\Provider\Youtube\Channels([
    'clientId' => config('services.google.client_id'),
    'clientSecret' => config('services.google.client_secret'),
]);

$youtubeChannelsProvider->setTokenAndRefreshIfNeeded([
    'access_token' => 'your access token',
    'refresh_token' => 'your refresh token',
    'expires' => 'time when token expires',
]);

$params = [
    'mine' => 'true',
    'part' => 'snippet,contentDetails,statistics',
];
$request = $youtubeChannelsProvider->getListRequest($params);
$response = $youtubeChannelsProvider->getParsedResponse($request);
```

Or if you have api key

```php
$youtubeChannelsProvider = new \Websecret\LaravelGoogleApi\Provider\Youtube\Channels([
    'apiKey' => config('services.google.api_key'),
]);
```

#### Pagination

```php
$youtubeChannelsPaginator = new \Websecret\LaravelGoogleApi\Paginator($youtubeChannelsProvider);

foreach($youtubeChannelsPaginator->paginate($youtubeChannelsProvider->getListRequest($params)) as $youtubeChannelsResponse) {
    foreach($youtubeChannelsResponse['items'] as $item) {
        dump($item);
    }
}       
```

### Available providers

* Google
    * Auth
        * authorize
        * handleAuthorization
* Plus
    * People
        * getUserIdRequest
* Youtube
    * Analytics
        * getReportsRequest
    * Channels
        * getListRequest
    * ContentID
        * getClaimSearchRequest
        * getAssetRequest
        * getAssetsListRequest
        * getContentOwnerListRequest
        * getContentOwnerRequest
    * I18nLanguages
        * getListRequest
    * I18nRegions
        * getListRequest
    * PlaylistItems
        * getListRequest
    * Playlists
        * getListRequest
    * Search
        * getListRequest
    * VideoCategories
        * getListRequest
    * Videos
        * getListRequest
* YoutubeAnalytics
    * Jobs
        * getListRequest
    * Reports
        * getListRequest
    * ReportTypes
        * getListRequest

