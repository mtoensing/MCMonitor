<?php

spl_autoload_register( function ( $class_name ) {
	include 'classes/' . $class_name . '.class.php';
} );

$mcmonitor = new MCMonitor(
	"u5xvqz2hcvrlrpgm.myfritz.net",
	"25565",
	1
);

//echo $mcmonitor->getJSON();
$mcmonitor->saveJSON();
