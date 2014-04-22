<?php
/**
 * shared functionality
 */

/* internationalisation */
require_once '/data/project/intuition/src/Intuition/ToolStart.php';

$I18N = new TsIntuition( array(
	'domain' => 'mc8',
) );
$I18N->loadTextdomainFromFile( __DIR__ . '/mc8.i18n.php', 'mc8' );
