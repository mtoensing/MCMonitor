<?php

spl_autoload_register( function ( $class_name ) {
	include 'classes/' . $class_name . '.class.php';
} );

$mcmonitor = new MCMonitor(
	"u5xvqz2hcvrlrpgm.myfritz.net",
	"25565",
	1
);

$mcmonitor->saveJSON();

$mcviewer = new MCViewer(
	"db/data.json"
);

$mcviewer->setOverviewerPath( 'iso' );

file_put_contents( 'index.php', $mcviewer->getOutput( "https://mc.marc.tv/iso/" ) );



