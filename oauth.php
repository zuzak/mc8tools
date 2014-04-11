<?php
error_reporting( -1 );
session_start();
$tokenId = $_SESSION['tokenKey'];
$tokenSecret = $_SESSION['tokenSecret'];
session_write_close();
if ( $_GET['oauth_token'] != $tokenId ) {
  $flash = "Something went wrong with the authentication. Please try again.";
  require 'home.php';
  die();
}
if ( !isset( $_SESSION['tokenSecret'] ) ) {
  $flash = 'There was a loss of session data. Please try again.';
  require 'home.php';
  die();
}
require 'utilities.php';
require 'settings.php';
$url = $baseUrl . "/token&" . http_build_query( array(
//  'format' => 'json',
  'oauth_verifier' => $_GET['oauth_verifier'],
  'oauth_consumer_key' => $consumerID,
  'oauth_token' => $tokenId,
  'oauth_version' => '1.0',
  'oauth_nonce' => md5( microtime() . mt_rand() ),
  'oauth_timestamp' => time(),
  'oauth_signature_method' => 'HMAC-SHA1'
) );
$sig = sign_request( 'GET', $url, $consumerSecret, $tokenSecret );
$url .= '&oauth_signature=' . urlencode( $sig );
$curl = curl_init();
curl_setopt( $curl, CURLOPT_URL, $url );
curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );
curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
$data = curl_exec( $curl );
curl_close( $curl );
$flash = $data;
require "home.php";
die();
if ( isset( json_decode( $data )->error ) ) {
  $flash = "Something went wrong (" . json_decode( $data )->error . ")";
  require "home.php";
  die();
}
require "main.php";
?>
