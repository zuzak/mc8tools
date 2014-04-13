<!DOCTYPE html>
<html>
<head>
	<title>New Categories</title>
	<meta charset="utf-8">
	<link href="../vector.css" rel="stylesheet">
	<style>.new, .new a { color: #CC2200; }</style>
</head>
<body>
	<h1>Error encountered</h1>
	<?php if( isset( $errmsg ) ) echo "<pre>$errmsg</pre>"; ?>
	<p>
		A problem was encountered whilst processing that last directive.
	</p>
	<p>
		You may wish to attempt your request again. If you continue having problems,
		contact <a href="https://meta.wikimedia.org/wiki/User talk:Microchip08">Microchip08</a>,
		or open an <a href="https://github.com/zuzak/mc8tools/issues">issue on Github</a>.
	</p>
</body>
</html>
<?php die(); ?>
