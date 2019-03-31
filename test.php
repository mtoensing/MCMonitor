<?php
/**
 * Created by PhpStorm.
 * User: mtoe
 * Date: 2019-03-31
 * Time: 15:09
 */


spl_autoload_register( function ( $class_name ) {
	include 'classes/' . $class_name . '.class.php';
} );

