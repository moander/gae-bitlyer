<?php 
  
use google\appengine\api\app_identity\AppIdentityService;

function setAuthHeader() {
  $access_token = AppIdentityService::getAccessToken('https://www.googleapis.com/auth/urlshortener');
  return [sprintf('Authorization: OAuth %s', $access_token['access_token'])];
}

$headers = setAuthHeader();
$headers[] = "Content-Type: application/json";
	
$data = ["longUrl" => @$_GET['url']];

$context = [
  'http' => [
    'method' => 'POST',
    'header' => implode("\r\n", $headers),
    'content' => json_encode($data)
  ]
];
$context = stream_context_create($context);
$result = file_get_contents('https://www.googleapis.com/urlshortener/v1/url', false, $context);

foreach ($http_response_header as $h) {
    header($h);
}
echo $result;
