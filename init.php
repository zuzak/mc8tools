<?php
require 'settings.php';
require 'utilities.php';
$curl = curl_init();
$url = $baseUrl . "/initiate&" . http_build_query( array(
  'format' => 'json',
  'oauth_callback' => 'oob',
  'oauth_consumer_key' => $consumerID,
  'oauth_version' => '1.0',
  'oauth_nonce' => md5( microtime() . mt_rand() ),
  'oauth_timestamp' => time(),
  'oauth_signature_method' => 'HMAC-SHA1'
) );

$sig = sign_request( 'GET', $url, $consumerSecret );
$url .= '&oauth_signature=' . urlencode( $sig );

curl_setopt( $curl, CURLOPT_URL, $url );
curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
$data = curl_exec( $curl );
curl_close( $curl );
$data = json_decode( $data );

session_start();
$_SESSION['tokenKey'] = $data->key;
$_SESSION['tokenSecret'] = $data->secret;
session_write_close();

$authUrl = $baseUrl . '/authorize&';
$authUrl .= http_build_query( array(
  'oauth_token' => $data->key,
  'oauth_consumer_key' => $consumerID
) );

header( "HTTP/1.1 302 Found" );
header( "Location: $authUrl" );
?>
<!DOCTYPE html>
<html>
<head>
  <title>Topic Cat</title>
  <link href="vector.css" rel="stylesheet">
  <meta charset="utf-8">
</head>
<body>
  <span class="hatnote">Step Two of Four</span>
  <h1>Redirection required</h1>
  <p>Please visit <a href="<?php echo $authUrl; ?>">this link</a> to continue.</p>
</body>
</html>
