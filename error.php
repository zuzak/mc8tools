<?php require_once('shared.php'); ?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo _('error-heading');?></title>
	<meta charset="utf-8">
	<link href="vector.css" rel="stylesheet">
	<style>.new, .new a { color: #CC2200; }</style>
</head>
<body>
	<h1><?php echo _('error-heading');?></h1>
	<p>
		<?php echo _('error-intro');?>
	</p>
	<?php if( isset( $errmsg ) ) echo "<pre>$errmsg</pre>"; ?>
	<p>
		<?php echo _('error-contact', array('variables' => array(
			'https://meta.wikimedia.org/w/index.php?title=User_talk:Microchip08&action=edit&section=new',
			'Microchip08',
			'"https://github.com/zuzak/mc8tools/issues'
		))); ?>
	</p>
</body>
</html>
<?php die(); ?>
