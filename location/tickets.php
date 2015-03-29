<?php
if (!defined('MCR')) exit;
if (!defined('EX')) exit ("I'm sorry, but  this mod not compable with webMCR. Please, install its fork <a href=\"http://git.worldsofcubes.net/webmcrex\">webMCRex</a>");
if (empty($user) or $user->lvl() <= 0) { accss_deny(); }
$path = 'tickets/';
loadTool("ticketsmod.class.php", $path);
if (isset($_REQUEST['action'])) $action = $_REQUEST['action'];
	else $action = 'list';
$ticket_mod = new TicketMod();
if($tickets['install']) $action = 'install';
$p = (isset($_REQUEST['page']))? (int) $_REQUEST['page']:1;
switch ($action) {
	case 'install':
		print($ticket_mod->install());
		break;
	case 'list':
		if(isset($_POST['ticket'])) {
			loadTool("tickets.class.php", $path);
			Ticket::Send($_POST['ticket'], $user);
		}
		$first = ($p - 1) * $tickets['tickets_by_page'];
		$query = $db->execute("SELECT * FROM `tickets`, `{$bd_names['users']}` WHERE `tickets`.`user`=" . $user->id() . " AND `tickets`.`author`=`{$bd_names['users']}`.`{$bd_users['id']}` ORDER BY `time` DESC LIMIT $first, {$tickets['tickets_by_page']}");
		ob_start();
		include View::Get('user/tickets_head.html', $path);
		while ($temp_ticket = $db->fetch_array($query)) {
			switch($temp_ticket['type']) {
				case 4:
					$before = '<div class="label label-danger">' . lng('TICKET_ADMINISTRATORS') . '</div>';
					break;
				case 3:
					$before = '<div class="label label-success">' . lng('TICKET_MODERATORS') . '</div>';
					break;
				case 2:
					$before = lng('TICKET_CLOSED');
					break;
				case 1:
					$before = lng('TICKET_VIEWED');
					break;
				case 0:
					$before = lng('TICKET_UNVIEWED');
					break;
			}
			include View::Get('user/ticket.html', $path);
		}
		$content_main = ob_get_clean();
		break;
}