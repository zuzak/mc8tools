<?php
require '../shared.php';
/**
 * Database username/password is stored in a configuration
 * file in ~/replica.my.cnf, so we need to access it
 */

$credentials = parse_ini_file('../../replica.my.cnf');
if ( !$credentials ) {
	$errmsg = _( 'db-connectfail' );
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
		_( 'db-nodatabase', array( 'variables' => array( $project ) )  );
	} else {
		$errmsg = _( 'db-error', array( 'variables' => array( $db->connect_error ) ) );
	}
	require '../error.php';
}

if (!$result = $db->query( $query ))  {
	$errmsg = _( 'db-queryfail' );
	require '../error.php';
}
?><!DOCTYPE html>
<html>
<head>
	<title><?php _e( 'crossredirs' ); ?></title>
	<meta charset="utf-8">
	<link href="../vector.css" rel="stylesheet">
	<style>.new, .new a { color: #CC2200; }</style>
	<script>
		window.onload = function () {
			if ( window.location.hash ) {
				window.location.replace('?project=' + window.location.hash.substring(1));
			}
		}
	</script>
</head>
<body>
	<form class="hatnote" method="get" action="">
		<input type="text"  class="tb" name="project" placeholder="<?php _e('db-name');?>"/>
	</form>
	<h1><?php _e( 'crossredirs' ); ?></h1>
	<p>
		<?php _e( 'crossredirs-intro', array( 'parsemag' => true, 'variables' => array( $result->num_rows, $project ) )  ); ?>
	</p>
	<table class="wikitable">
		<tr>
			<th><?php _e( 'crossredirs-origin' );?></th>
			<th><?php _e( 'crossredirs-destination' );?></th>
		</tr>
<?php
if ( substr( $project, -4 ) == 'wiki' ) {
	$project .= 'pedia';
}
$url = '//' . str_replace( 'wiki', '.wiki', $project ) . '.org';
while ( $row = $result->fetch_assoc() ) {
	echo "\n\t\t\t<tr>\n\t\t\t\t<td>";
	echo "<a href=\"$url/w/index.php?redirect=no&title=" . urlencode( $row['page_title'] ) . '">' . str_replace( '_', ' ', $row['page_title'] ) . '</a>';
	echo "\n\t\t\t\t</td>\n\t\t\t\t<td>";
	echo "<a href=\"$url/wiki/User:" . urlencode( $row['rd_title'] ) . '">User:' . str_replace( '_', ' ', $row['rd_title'] ) . '</a>';
	echo "\n\t\t\t</tr>";
}
$db->close();
?>
	</table>
</body>

