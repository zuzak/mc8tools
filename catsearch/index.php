<?php
/**
 * Database username/password is stored in a configuration
 * file in ~/replica.my.cnf, so we need to access it
 */

$credentials = parse_ini_file('../../replica.my.cnf');
if ( !$credentials ) {
	$errmsg = 'Unable to access database credentials.';
	require '../error.php'; /* this just renders $errmsg nicely, and dies */
}

if ( isset( $_GET['project'] ) ) {
	$project = $_GET['project'];
} else {
	$project = 'enwikinews';
}
if ( isset( $_GET['term'] ) ) {
	$term = $_GET['term'];
} else {
	$errmsg = "No term provided.";
	require '../error.php';
}

$db = new mysqli( "$project.labsdb", $credentials['user'], $credentials['password'], $project.'_p' );
if ($db->connect_error) {
	if ( $db->connect_errno == 2005) { /* "not a valid database" */
		$errmsg = "`$project' is not a valid project identifier.\nExamples: enwiki, dewikiquote, commonswiki";
	} else {
		$errmsg = $db->connect_error;
	}
	require '../error.php';
}
$term = $db->real_escape_string( $term );
$query = <<<SQL
	SELECT page_id, page_namespace FROM `page`, `text` WHERE page_id = si_page
	AND MATCH(old_text) AGAINST('+$term' IN BOOLEAN MODE)
	AND page_is_redirect=0
	AND page_namespace IN (0) LIMIT 50
SQL;

$hits = array();
if ( $result = $db->query( $query ) ) {
	while ( $row = $result->fetch_assoc() ) {
		array_push( $hits, $row );
	}
	var_dump($result);
} else {
	$errmsg = "Unable to run query.\n" . $db->error;
	require '../error.php';
}
?><!DOCTYPE html>
<html>
<head>
	<title>Recent Categories</title>
	<meta charset="utf-8">
	<link href="../vector.css" rel="stylesheet">
	<style>.new, .new a { color: #CC2200; }</style>
</head>
<body>
	<h1>Hits</h1>
	<pre>
	<?php var_dump( $hits ); ?>
	</pre>
</body>

