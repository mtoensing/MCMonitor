<?php

spl_autoload_register( function ( $class_name ) {
    include 'classes/' . $class_name . '.class.php';
} );

$mcviewer = new MCViewer(
    "db/data.json"
);

echo $mcviewer->getOutput();


?>
