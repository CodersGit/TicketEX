<?php
class TicketMod {
	public $modName = "TicketEX";
	public $author = "Sergey Zhiov";
	public $version = "1.0 indev";
	public $year = "2015";
	public function TicketMod(){
		global $MCR_LANG, $MCR_LANG_TPL, $tickets;

		if(!file_exists(MCR_ROOT.'tickets.cfg.php')) require (MCR_ROOT . 'instruments/tickets/default.cfg.php');
			else include (MCR_ROOT.'tickets.cfg.php');

		require(MCR_ROOT.'instruments/tickets/locale/'.MCR_LANG.'.php');
		$MCR_LANG = array_merge($MCR_LANG, $MCR_TICKETS_LANG);
		$MCR_LANG_TPL = array_merge($MCR_LANG_TPL, $MCR_TICKETS_LANG_TPL);
	}
	public function install() {
		global $db, $tickets;
		$db->execute("CREATE TABLE IF NOT EXISTS `tickets` (
				`id` BIGINT(20) NOT NULL AUTO_INCREMENT,
				`user` BIGINT(20) NOT NULL,
				`author` BIGINT(20) NOT NULL,
				`message` TEXT NULL ,
				`time` DATETIME NOT  NULL,
				PRIMARY KEY (`id`)
			) DEFAULT CHARSET=utf8 ENGINE=MyISAM;");
		$db->execute("CREATE TABLE IF NOT EXISTS `tickets_user_comments` (
				`id` BIGINT(20) NOT NULL AUTO_INCREMENT,
				`user` BIGINT(20) NOT NULL,
				`message` TEXT NULL ,
				`time` DATETIME NOT  NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY (`user`)
			) DEFAULT CHARSET=utf8 ENGINE=MyISAM;");
		$tickets['install'] = false;
		return $this->UpdateConfig();
	}

	public function UpdateConfig()
	{
		global $tickets;

		$txt = '<?php' . PHP_EOL;
		$txt .= '$tickets = ' . var_export($tickets, true) . ';' . PHP_EOL;
		$txt .= '/* Этот файл сгенерирован модулем ' . $this->modName . ' ' . $this->version . ' */' . PHP_EOL;
		$txt .= '?>';

		if (file_put_contents(MCR_ROOT . 'tickets.cfg.php', $txt) === false) return false;
		return true;
	}
}