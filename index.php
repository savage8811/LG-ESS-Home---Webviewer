<?php

require("config.php");
require("functions.php");


if($_REQUEST["path"] == "")
{
	require("loginpage.php");	
}
else if (startsWith($_REQUEST["path"], 'v1')) 
{
	$json = file_get_contents('php://input');
	$data = json_decode($json);
	header('Content-type: application/json');
	// forward the browser's real method (PUT for writes, POST for reads);
	// hardcoding POST made the device silently ignore setting changes.
	echo DoJsonRequest($urlToLGESS.$_REQUEST["path"], $data, $_SERVER['REQUEST_METHOD']);
}
else 
{
	echo DoGetRequest($urlToLGESS.$_REQUEST["path"]);	
}
	
	
?>
