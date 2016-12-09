<?php

	require("/home/egenoor/config.php");

    session_start();
	
	$database = "if16_ege";
	$mysqli = new mysqli($serverHost, $serverUsername, $serverPassword, $database);
	
	require("class/Helper.class.php");
	$Helper = new Helper();

?>