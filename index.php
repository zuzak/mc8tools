<?php require 'shared.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title><?php echo $I18N->msg('name');?></title>
  <link href="vector.css" rel="stylesheet">
  <meta charset="utf-8">
</head>
<body>
  <a class="hatnote"><?php echo $I18N->msg('poweredby');?></a>
  <h1><?php echo $I18N->msg('name');?></h1>
  <h2><?php echo $I18N->msg('tools');?></h2>
  <ul class="projects">
    <li class="wikinews">
	<?php echo $I18N->msg('dashboard-desc', array('variables'=>array('./dashboard')));?>
    </li>
    <li>
	<?php echo $I18N->msg('recentcats-desc', array('variables'=>array('./recentcats')));?>
    </li>
    <li>
	<?php echo $I18N->msg('crossredirs-desc', array('variables'=>array('./crossredirs')));?>
    </li>
    <li>
	<?php echo $I18N->msg('topiccat-desc', array('variables'=>array('./topiccat', '//en.wikinews.org/wiki/Template:Topic cat')));?>
    </li>
  </ul>
  <h2><?php echo $I18N->msg('source');?></h2>
  <p><?php echo $I18N->msg('source-desc', array('variables'=>array('//github.com/zuzak/mc8tools', 'http://opensource.org/ISC')));?>
</body>
</html>
