<?php
//  header('Content-type: application/json');

$wikidata = 'https://www.wikidata.org/w/api.php?';

if ( isset( $_GET['name'] ) ) {
	$name = $_GET['name'];
} else {
	err( 400, 'no name specified' );
}

$url = $wikidata . http_build_query( array(
  'action' => 'wbgetentities',
  'format' => 'json',
  'languages' => 'en',
  'sites' => 'enwiki',
  'titles' => $name
) );

$curl = curl_init();
curl_setopt( $curl, CURLOPT_URL, $url );
curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
$json = curl_exec( $curl );
curl_close( $curl );

$data = json_decode( $json );
if ( $data === null ) {
	err( 500, 'unable to decode json' );
}
$data = $data->entities;
$data = (array)$data;
$out = array();
foreach ( $data as $k => $v ) {
	if ( $k == -1 ) {
		err( 404, 'no hits' );
	}
	$current = $data[$k];
	$output = array();
	foreach ( $current->descriptions as $lang ) {
		if ( $lang->language == 'en' ) {
			$output['desc'] = $lang->value;
			break;
		}
	}
	$sites = array();
	foreach ( $current->sitelinks as $site ) {
    	$sites[$site->site] = $site->title;
	}
	$output['sites'] = $sites;

	foreach ( $current->claims as $claim ) {
		if ( $claim[0]->mainsnak->property == 'P18' ) {
			$output['image'] = $claim[0]->mainsnak->datavalue->value;
			break;
		}
	}

	$out[$k] = $output;
}

echo json_encode( $out );

function err( $code, $str ) {
	switch ( $code ) {
	case 400:
		header( 'HTTP/1.1 400 Bad Request' );
		break;
	case 404:
	  	header( 'HTTP/1.1 404 Not Found' );
		break;
	case default:
	  	header( 'HTTP/1.1 500 Internal Server Error' );
	}
	die( json_encode( array( 'error' => $str ) ) );
}

