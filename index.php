<?php

spl_autoload_register( function ( $class_name ) {
    include 'classes/' . $class_name . '.class.php';
} );

$mcviewer = new MCViewer(
    "db/data.json"
);


$page = new MCTemplate("template.html");
$page->set("players", $mcviewer->getPlayers() );
$page->set("isonline", $mcviewer->getOnlineStatus() );
$page->set("version", $mcviewer->getVersion() );
$page->set("address", $mcviewer->getAddress() );
$page->set("hostname", $mcviewer->getHostname() );
$page->set("gametype", $mcviewer->getGametype() );
echo $page->output();


?>

