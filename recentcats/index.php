<?php
$select = array(
	'rc_namespace', 'rc_title'
);

$query = 'SELECT DISTINCT ' . implode( ',', $select ) . ' FROM recentchanges ';
$query .= 'WHERE rc_namespace = 14 AND rc_new = 1';

$credentials = parse_ini_file('../../replica.my.cnf');
if ( !$credentials ) {
	$errmsg = 'Unable to access database credentials.';
	require '../error.php';
}
if ( isset( $_GET['project'] ) ) {
	$project = $_GET['project'];
} else {
	$project = 'enwikinews';
}	
$db = new mysqli( "$project.labsdb", $credentials['user'], $credentials['password'], $project.'_p' );
if ($db->connect_error) {
	if ( $db->connect_errno == 2005) {
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
		The following is a list of newly created categories on <?php echo $project; ?>.
	</p>
	<ul>
<?php
foreach ( array_reverse( $cats ) as $title => $namespaces ) {
	if ( substr( $project, -4) == 'wiki') {
		$project .= 'pedia';
	}
	$url = 'https://' . str_replace( 'wiki', '.wiki', $project ) . ".org/wiki/Category:$title";
	$title = str_replace( '_', ' ', $title );
	echo "\t\t<li>\n\t\t\t<a href=\"$url\">$title</a>\n\t\t</li>";
}
?>
	</table>
</body>

