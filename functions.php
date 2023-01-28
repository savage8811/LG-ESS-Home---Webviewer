<?php

function DoAuth($loginAsInstaller = false) {
	global $urlToLGESS, $userPassword, $installerPassword;
	$url = $urlToLGESS.'v1/user/setting/login';
	$data = array(
		'password' => $userPassword   
	);	
	if ($loginAsInstaller)
	{
		$url = $urlToLGESS.'v1/login';
		$data['password'] = $installerPassword;
	}
	
	return DoJsonRequest($url, $data, 'PUT', true); //login need PUT instead of POST
}

function startsWith( $haystack, $needle ) 
{
     $length = strlen( $needle );
     return substr( $haystack, 0, $length ) === $needle;
}

function DoJsonRequest($url, $data, $reqtype = 'POST', $returnEncodedJson = false) {	
	$payload = json_encode($data);
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $reqtype);	
	curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Skip SSL Verification
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // Skip SSL Verification
	$response = curl_exec($ch);		
	$error_message = curl_error($ch);  
	if(!empty($error_message)) die("error: ".$error_message);
	
	if($returnEncodedJson)
		$resp = (json_decode($response, true));
	else
		$resp = $response;
	curl_close($ch);
	return $resp;
}

function DoGetRequest($url) 
{	
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, 1);	
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');	
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Skip SSL Verification
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // Skip SSL Verification
	$response = curl_exec($ch);	
	$error_message = curl_error($ch);  		
	if(!empty($error_message)) die("error: ".$error_message);
	$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	$header = substr($response, 0, $header_size);
	$body = substr($response, $header_size);
	curl_close($ch);	
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $header) as $line){
		header($line);
	} 	
	//$body = str_replace('"/v1/', '"../v1/', $body); //replace absolute with rel path	
	
	//manipulate html for some modifications 
	if(strpos($url, "system/index.html") !== false)
	{		
		$body = str_replace(".toFixed(1)", ".toFixed(2)", $body); 
		$body = str_replace(".toFixed(0)", ".toFixed(1)", $body); //battery
	} 
	else if(strpos($url, "system/analysis_month.html") !== false 
		|| strpos($url, "system/analysis_month_battery.html") !== false
		|| strpos($url, "system/analysis_month_load.html") !== false
		) 
	{
		//like the old barRenderer more than lineRenderer on month-view
		$body = str_replace("//renderer:$.jqplot.BarRenderer,", "renderer:$.jqplot.BarRenderer,", $body); 
		$body = str_replace('/*,
						barPadding: 2,
						barMargin: 3*/', ",barPadding: 0, barMargin: 2", $body); 				
	}
	
	if(strpos($url, "system/analysis_month.html") !== false 
	|| strpos($url, "system/analysis_day.html") !== false
	|| strpos($url, "system/analysis_week.html") !== false
	|| strpos($url, "system/analysis_year.html") !== false
	)
	{
		//calculate some stuff
		$body = str_replace("indexArray;", "indexArray;  document.getElementById('mydata').innerText =  Math.round(Math.abs((100/total_generation.replace('kWh','')*total_Feed_in.replace('kWh',''))-100));", $body); 
		$body = str_replace('<div id="chart1" class="Graph"></div>', '<div id="chart1" class="Graph"></div><div style="margin-top:10px;text-align:center;font-weight: bold;">Eigenverbrauch: <span id="mydata"></span>%</div>', $body); 
	}
	
	if(strpos($url, "system/analysis_month_load.html") !== false 
	|| strpos($url, "system/analysis_day_load.html") !== false
	|| strpos($url, "system/analysis_week_load.html") !== false
	|| strpos($url, "system/analysis_year_load.html") !== false
	)
	{
		//calculate some stuff
		$body = str_replace("indexArray;", "indexArray;  document.getElementById('mydata').innerText =  Math.round(Math.abs((100/total_consumption.replace('kWh','')*total_purchase.replace('kWh',''))-100));", $body); 
		$body = str_replace('<div id="chart1" class="Graph"></div>', '<div id="chart1" class="Graph"></div> <div style="margin-top:10px;text-align:center;font-weight: bold;">Ersparnis: <span id="mydata"></span>%</div>', $body); 
	}
	
	return $body;
}





?>