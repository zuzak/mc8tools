<?php
require 'settings.php';
require 'utilities.php';
$curl = curl_init();
$url = $baseUrl . "/initiate&" . http_build_query( array(
  'format' => 'json',
  'oauth_callback' => 'oob',
  'oauth_consumer_key' => $consumerID,
  'oauth_version' => '1.0',
  'oauth_nonce' => md5(microtime() . mt_rand()),
  'oauth_timestamp' => time(),
  'oauth_signature_method' => 'HMAC-SHA1'
));

$sig = sign_request( 'GET', $url );
$url .= '&oauth_signature=' . urlencode( $sig );

curl_setopt( $curl, CURLOPT_URL, $url );
curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true);
$data = curl_exec( $curl );
curl_close( $curl );
$data = json_decode($data);

session_start();
$_SESSION['tokenKey'] = $data->key;
$_SESSION['tokenSecret'] = $data->secret;
session_write_close();

$authUrl = $baseUrl . '/authorize&';
$authUrl .= http_build_query( array(
  'oauth_token' => $data->key,
  'oauth_consumer_key' => $consumerID
));
?>
<!DOCTYPE html>
<html>
<head>
  <title>Topic Cat</title>
  <link href="vector.css" rel="stylesheet">
  <meta charset="utf-8">
</head>
<body>
  <h1>Topic cat tool</h1>
  <p>
    This tool attempts to automate some of the more tedious parts of creating a
    new category on <a href="https://en.wikinews.org/wiki/">English Wikinews</a>.
  </p>
  <p>
    It will attempt to perform the following actions on your behalf.
    <ul>
      <li>Create a category page.</li>
      <li>Create a mainspace redirect to that category.</li>
      <li>Protect that redirect, where possible.</li>
      <li>Prompt for a <code>{{<a href="https://en.wikinews.org/wiki/Template:Topic cat">topic cat</a>}}</code></li>.
    </ul>
  </p>
  <p>
    In order for it to do that, it needs certain rights on your account.
  </p>
  <p>
    Please authenticate with OAuth to begin.
  </p>

  <a href="<?php echo $authUrl; ?>" class="progress button">Sign in with Wikinews</a>

  <p class="footer">
    Created by <a href="https://en.wikinews.org/wiki/User:Microchip08">Microchip08</a>
    for <a href="https://en.wikinews.org/">English Wikinews</a>.

    Powered by <a href="https://wikitech.wikimedia.org">Wikimedia Labs</a>.
  </p>
</body>
</html>
