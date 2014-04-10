<?php
if ( isset( $_GET["oauth_token"] ) ) {
  require "oauth.php";
} else {
  require "home.php";
}
