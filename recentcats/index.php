<?php
ini_set('error_reporting', E_ALL);

$select = array(
	'rc_namespace', 'rc_title'
);

$query = 'SELECT DISTINCT ' . implode( ',', $select ) . ' FROM recentchanges ';
$query .= 'WHERE rc_namespace = 14 OR rc_namespace = 0 AND rc_new = 1';

$credentials = parse_ini_file('../../../replica.my.cnf');
if ( !$credentials ) {
	$errmsg = 'Unable to access database credentials.';
	require '../error.php';
}
$db = new mysqli( 'enwikinews.labsdb', $credentials['user'], $credentials['password'], 'enwikinews_p' );
if ($db->connect_error) {
	$errmsg = $db->connect_error;
	require '../error.php';
}

$cats = array();

if ( $result = $db->query( $query ) ) {
	echo '<table>';
	while ( $row = $result->fetch_assoc() ) {
		var_dump($row);
		if ( !isset($cats[$row['rc_title']]) ) {
			$cats[$row['rc_title']] = array( $row['rc_namespace'] );
		} else {
			array_push( $cats[$row['rc_title']], $row['rc_namespace'] );
		}
	}
	echo '</table>';
	$result->close();
}

$db->close();

?><!DOCTYPE html>
<html>
<head>
	<title>New Categories</title>
	<meta charset="utf-8">
	<link href="../vector.css" rel="stylesheet">
	<style>.new, .new a { color: #CC2200; }</style>
</head>
<body>
	<span class="hatnote">on English Wikinews</span>
	<h1>Recent Categories</h1>

	<table class="wikitable">
		<tr>
			<th>
				Category
			</th>
			<th>
				Mainspace
			</th>
			<th>
				Protection
			</th>
		</tr>
<?php
foreach ( $cats as $title => $namespaces ) {
	if( in_array( 14, $namespaces ) ) {
		echo "\t\t<tr>\n\t\t\t<td>\n\t\t\t\t$title\n";
		echo "\t\t\t</td>\n\t\t\t";
		if ( in_array( 0, $namespaces ) ) {
			echo "<td>";
		} else {
			echo '<td class="new">';
		}
		echo "\n\t\t\t\t$title\n\t\t\t</td>\n\t\t</tr>\n";
	}
}
?>
	</table>
</body>

