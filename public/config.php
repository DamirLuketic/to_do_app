<?php

@session_start();

// Net
//$path = '/pages/to_do_app/public';
//
//$mysql_host = "localhost";
//$mysql_database = "consiliu_to_do_app";
//$mysql_user = "consiliu_damir";
//$mysql_password = "post1982";

// Local
//$path = '/to_do_app/public/';
$path = '';

$mysql_host = "localhost";
$mysql_database = "to_do_app";
$mysql_user = "Luketic";
$mysql_password = "Damir";

$GLOBALS['path'] = $path;

try{

	$GLOBALS['con'] = new PDO(
	"mysql:host=" . $mysql_host . ";dbname=" . $mysql_database,
	$mysql_user,
	$mysql_password,
	array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		  PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
	);
}catch(PDOException $e){
		switch ($e->getCode()) {
			case 2002:
				echo 'MySQL server is not on given address';
				break;
			case 1049:
				echo 'Wrong database name';
				break;
			case 1045:
				echo 'Wrong user name and/or password';
				break;
			default:
				echo $e->getCode();
				break;
		}
	exit;
}

?>
