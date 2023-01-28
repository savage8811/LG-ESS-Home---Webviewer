<?php

	if(isset($_GET["loginAsUser"]))
	{
		$authinfo = DoAuth();
	}
	else if(isset($_GET["loginAsInstaller"]))
	{
		$authinfo = DoAuth(true);
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<title>my LG ESS</title>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<script>
		function OnLoad()
		{
			sessionStorage.setItem("auth_key","<?php echo $authinfo["auth_key"]; ?>");
			sessionStorage.setItem("role","installer");
			window.location.href = "<?php echo $redirectToUrl; ?>";
		}	
		var sec2start = 3;
		function OnLoadCountdown()
		{
			document.getElementById("countdown").innerHTML = sec2start;
			setInterval(function () {
				sec2start--;
				document.getElementById("countdown").innerHTML = sec2start;
				if(sec2start <= 0)
				{
					location.href = "?loginAsUser=1";
				}
			}, 1000);
		}
		<?php
			if(isset($authinfo)) { echo 'OnLoad()'; }
		?>
		</script>
	</head>
	<body onload="OnLoadCountdown();">
		<a href="?loginAsUser=1">Login as USER</a> (<span id="countdown"></span>)
		<br/>
		<br/>
		<a href="?loginAsInstaller=1">Login as INSTALLER</a>
	</body>
</html>