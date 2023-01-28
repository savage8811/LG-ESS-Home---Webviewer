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
	echo DoJsonRequest($urlToLGESS.$_REQUEST["path"], $data);
}
else 
{
	echo DoGetRequest($urlToLGESS.$_REQUEST["path"]);	
}
	
	
?>
