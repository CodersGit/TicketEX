<?php
class Ticket {
	public static function Send ($text, $account, $adm = false) {
		global $db, $tickets, $user;
		if (!$user) return 1;
		if(!$adm)
			$type = 0;
		elseif ($user->lvl() >= $tickets['minimal_admin_lvl'])
			$type = 3;
		elseif ($user->lvl() >= $tickets['minimal_moderate_lvl'])
			$type = 4;
		else
			return 1;
		$db->execute("INSERT INTO `tickets` (`user`, `author`, `message`, `type`, `time`) VALUES (" . $account->id() . ", " . $user->id() . ", '$text', $type, NOW())");
		return 0;
	}
}