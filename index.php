<?php

spl_autoload_register( function ( $class_name ) {
    include 'classes/' . $class_name . '.class.php';
} );

$mcviewer = new MCViewer(
    "db/data.json"
);

$mcviewer ->setOverviewerPath('iso');

echo $mcviewer->getOutput("https://mc.marc.tv/iso/");

?>
