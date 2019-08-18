<?php

spl_autoload_register( function ( $class_name ) {
	include 'classes/' . $class_name . '.class.php';
} );

$mcmonitor = new MCMonitor(
	"mc.marc.tv",
	"25565",
	1
);

$mcmonitor->saveJSON();
