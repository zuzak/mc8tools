<?php
header( 'Content-type: application/json' );

$output = array();
$cats = array( 'developing', 'review', 'published', 'disputed', 'under-review' );

$endpoint = 'https://en.wikinews.org/w/api.php?action=query&format=json';
$endpoint .= '&list=categorymembers&cmdir=desc&cmsort=timestamp&cmlimit=10';
$endpoint .= '&cmtitle=';

foreach ( $cats as $category ) {

	global $endpoint, $output;
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $endpoint . 'Category:' . str_replace( '-', '_', $category ) );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

	$json = curl_exec( $ch );

	if (!$json) {
		die( '{"error": "'.curl_error( $ch ) . '"}' );
	}
	$data = json_decode( $json );
	$data = $data->query;

	$output[$category] = array();
	
	foreach ( $data->categorymembers as $datum ) {
		array_push( $output[$category], $datum->title );
	}

}

die( json_encode( $output ) );
