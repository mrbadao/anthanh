<?php

// This is the database connection configuration.
return array(
	'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
	// uncomment the following lines to use a MySQL database

	'connectionString' => 'mysql:host=db05.serverhosting.vn;dbname=ant5b7af_anthanh',
	'emulatePrepare' => true,
	'username' => 'ant5b7af_anthanh',
	'password' => '123456',
	'charset' => 'utf8',

);