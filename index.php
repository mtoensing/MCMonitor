<?php

spl_autoload_register( function ( $class_name ) {
    include 'classes/' . $class_name . '.class.php';
} );

$mcviewer = new MCViewer(
    "db/data.json"
);


$page = new MCTemplate("template.html");
$page->set("playerlist", $mcviewer->getPlayerList() );
$page->set("isonline", $mcviewer->getOnlineStatus() );
$page->set("version", $mcviewer->getVersion() );
$page->set("address", $mcviewer->getAddress() );
$page->set("hostname", $mcviewer->getHostname() );
$page->set("gametype", $mcviewer->getGametype() );
$page->set("players_online", $mcviewer->getPlayersOnline() );
$page->set("max_seen_online", $mcviewer->getMaxSeenOnline() );
echo $page->output();


?>

