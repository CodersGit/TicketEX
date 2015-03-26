<?php
if (!defined('MCR')) exit;
if (!defined('EX')) exit ("К сожалению, модуль несовместим с webMCR. Установите его форк <a href=\"http://git.worldsofcubes.net/webmcrex\">webMCRex</a>");
if (empty($user) or $user->lvl() <= 0) { accss_deny(); }
$path = 'tickets/';
loadTool("ticketsmod.class.php", $path);
if (isset($_REQUEST['action'])) $action = $_REQUEST['action'];
if($tickets['install']) $action = 'install';