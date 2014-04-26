<?php require 'shared.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title><?php _e('name');?></title>
  <link href="vector.css" rel="stylesheet">
  <meta charset="utf-8">
</head>
<body>
  <span class="hatnote"><?php _e('poweredby'); ?></span>
  <h1><?php _e('name');?></h1>
  <h2><?php _e('tools');?></h2>
  <ul class="projects">
    <li class="wikinews">
	<?php _e('dashboard-desc', array('variables'=>array('./dashboard')));?>
    </li>
    <li>
	<?php _e('recentcats-desc', array('variables'=>array('./recentcats')));?>
    </li>
    <li>
	<?php _e('crossredirs-desc', array('variables'=>array('./crossredirs')));?>
    </li>
    <li>
	<?php _e('topiccat-desc', array('variables'=>array('./topiccat', '//en.wikinews.org/wiki/Template:Topic cat')));?>
    </li>
  </ul>
  <h2><?php _e('source');?></h2>
  <p><?php _e('source-desc', array('variables'=>array('//github.com/zuzak/mc8tools', 'http://opensource.org/ISC')));?>
</body>
</html>
