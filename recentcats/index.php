<?php
$select = array(
	'rc_namespace', 'rc_title'
);

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

/**
 * Now need to build the query itself
 * We use DISTINCT because we only want one of each page
 * rc_new is set to 1 when a new page is created
 * NS 14 is equivalent to Category:
 */
$query = 'SELECT DISTINCT ' . implode( ',', $select ) . ' FROM recentchanges ';
$query .= 'WHERE rc_namespace = 14 AND rc_new = 1';
$select = array(
	'rc_namespace', 'rc_title'
);

$db = new mysqli( "$project.labsdb", $credentials['user'], $credentials['password'], $project.'_p' );
if ($db->connect_error) {
	if ( $db->connect_errno == 2005) { /* "not a valid database" */
		$errmsg = "`$project' is not a valid project identifier.\nExamples: enwiki, dewikiquote, commonswiki";
	} else {
		$errmsg = $db->connect_error;
	}
	require '../error.php';
}

$cats = array();

if ( $result = $db->query( $query ) ) {
	while ( $row = $result->fetch_assoc() ) {
		if ( !isset($cats[$row['rc_title']]) ) {
			$cats[$row['rc_title']] = array( $row['rc_namespace'] );
		} else {
			array_push( $cats[$row['rc_title']], $row['rc_namespace'] );
		}
	}
	$result->close();
}

$db->close();

?><!DOCTYPE html>
<html>
<head>
	<title>Recent Categories</title>
	<meta charset="utf-8">
	<link href="../vector.css" rel="stylesheet">
	<style>.new, .new a { color: #CC2200; }</style>
</head>
<body>
	<form class="hatnote" method="get" action="">
		<input type="text"  class="tb" name="project" placeholder="Database Name" val="enwikinews"/>
	</form>
	<h1>Special:NewCategories</h1>
	<p>
		The following is a list of newly created categories on <strong><?php echo $project; ?></strong>.
	</p>
	<ul><?php

foreach ( array_reverse( $cats ) as $title => $namespaces ) {
	if ( substr( $project, -4) == 'wiki') {
		$project .= 'pedia'; /* most (all?) wikimedia.org ones redirect from wikipedia.org */
	}
	$url = 'https://' . str_replace( 'wiki', '.wiki', $project ) . ".org";
	$title = str_replace( '_', ' ', $title );
	echo "\n\t\t<li>\n\t\t\t<a href=\"$url/wiki/Category:$title\">$title</a>";
	if ( isset( $_GET['links'] ) ) {
		echo "\n\t\t\t<small>(";
		$actions = array( 'edit', 'protect', 'delete', 'watch' );
		foreach ( $actions as $action ) {
			echo "\n\t\t\t\t<a href=\"$url/w/index.php?title=Category:$title&amp;action=$action\">$action</a>";
		}
		echo "\n\t\t\t\t<a href=\"../topiccat/#$title\">tc</a>";
		echo "\n\t\t\t\t/ <a href=\"$url/wiki/$title\">ns:0</a>";
		echo "\n\t\t\t)</small>";
	}
	echo "\n\t\t</li>";
}
?>
	</table>
</body>

