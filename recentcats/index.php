<?php
require_once '../shared.php';
$select = array(
	'rc_namespace', 'rc_title'
);

/**
 * Database username/password is stored in a configuration
 * file in ~/replica.my.cnf, so we need to access it
 */

$credentials = parse_ini_file('../../replica.my.cnf');
if ( !$credentials ) {
	$errmsg = $I18N->msg('db-nocredentials');
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
		$errmsg = $I18N->msg('db-nodatabase', array('variables'=>array($project)));
	} else {
		$errmsg = $I18N->msg('db-error', array('variables'=>array($db->connect_error)));
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
	<title><?php echo $I18N->msg('recentcats');?></title>
	<meta charset="utf-8">
	<link href="../vector.css" rel="stylesheet">
	<style>.new, .new a { color: #CC2200; }</style>
</head>
<body>
	<form class="hatnote" method="get" action="">
		<input type="text"  class="tb" name="project" placeholder="<?php echo $I18N->msg('db-name');?>" val="enwikinews"/>
	</form>
	<h1><?php echo $I18N->msg('recentcats');?></h1>
	<p>
		<?php echo $I18N->msg('recentcats-intro', array('variables'=>array($project))); ?>
	</p>
	<ul><?php

foreach ( array_reverse( $cats ) as $title => $namespaces ) {
	if ( substr( $project, -4) == 'wiki') {
		$project .= 'pedia'; /* most (all?) wikimedia.org ones redirect from wikipedia.org */
	}
	$url = 'https://' . str_replace( 'wiki', '.wiki', $project ) . ".org";
	$title = str_replace( '_', ' ', $title );
	echo "\n\t\t<li>\n\t\t\t<a href=\"$url/wiki/Category:$title\">$title</a>";
	echo "\n\t\t</li>";
}
?>
	</table>
</body>

