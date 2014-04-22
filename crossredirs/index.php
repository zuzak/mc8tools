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

$query = <<<SQL
SELECT page_title, rd_namespace, rd_title, page_namespace
FROM page p
INNER JOIN redirect r on r.rd_from = p.page_id
WHERE r.rd_namespace = 2
AND p.page_namespace = 0;
SQL;

$db = new mysqli( "$project.labsdb", $credentials['user'], $credentials['password'], $project.'_p' );
if ($db->connect_error) {
	if ( $db->connect_errno == 2005) { /* "not a valid database" */
		$errmsg = "`$project' is not a valid project identifier.\nExamples: enwiki, dewikiquote, commonswiki";
	} else {
		$errmsg = $db->connect_error;
	}
	require '../error.php';
}

if (!$result = $db->query( $query ))  {
	$errmsg = "Couldn't run query.";
	require '../error.php';
}
?><!DOCTYPE html>
<html>
<head>
	<title>Cross namespace redirects</title>
	<meta charset="utf-8">
	<link href="../vector.css" rel="stylesheet">
	<style>.new, .new a { color: #CC2200; }</style>
</head>
<body>
	<form class="hatnote" method="get" action="">
		<input type="text"  class="tb" name="project" placeholder="Database Name" val="enwikinews"/>
	</form>
	<h1>Cross namespace redirects</h1>
	<p>
		The following is a list of <?php echo $result->num_rows; ?> mainspace&rarr;userspace redirects on <?php echo $project; ?>.
	</p>
	<table class="wikitable">
		<tr>
			<th>Origin</th>
			<th>Destination</th>
		</tr>
<?php
if ( substr( $project, -4 ) == 'wiki' ) {
	$project .= 'pedia';
}
$url = '//' . str_replace( 'wiki', '.wiki', $project ) . '.org';
while ( $row = $result->fetch_assoc() ) {
	echo "\n\t\t\t<tr>\n\t\t\t\t<td>";
	echo "<a href=\"$url/w/index.php?redirect=no&title=" . $row['page_title'] . '">' . str_replace( '_', ' ', $row['page_title'] ) . '</a>';
	echo "\n\t\t\t\t</td>\n\t\t\t\t<td>";
	echo "<a href=\"$url/wiki/" . 'User' . $row['rd_title'] . '">User:' . str_replace( '_', ' ', $row['rd_title'] ) . '</a>';
	echo "\n\t\t\t</tr>";
}
$db->close();
?>
	</table>
</body>

