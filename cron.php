<?php

spl_autoload_register( function ( $class_name ) {
	include 'classes/' . $class_name . '.class.php';
} );

$mcmonitor = new MCMonitor(
	"u5xvqz2hcvrlrpgm.myfritz.net",
	"25565",
	1
);


//echo $mcmonitor->getOnlineStatus();
//echo $mcmonitor->gametype;
//echo $mcmonitor->version;

//var_dump($mcmonitor->players);

//echo json_encode($mcmonitor->players,JSON_PRETTY_PRINT);


echo $mcmonitor->getJSON();
$mcmonitor->saveJSON();
