<?php 

if (isset($_POST['uuid'])){
	$uuid = $_POST['uuid'];
	$user = $_POST['user'];
	date_default_timezone_set('America/New_York');
	$datedel = date('m/d/Y h:i:s a', time());
	$db = new SQLite3('/usr/lib/archivematica/automation-tools/transfers/transfers.db');
	$db->exec('UPDATE unit SET dateDeleted="'.$datedel.'" WHERE uuid="'.$uuid.'"');
	$db->exec('UPDATE unit SET deletedBy="'.$user.'" WHERE uuid="'.$uuid.'"');
	print 'Deleted by '.$user." on ".$datedel;
}


?>