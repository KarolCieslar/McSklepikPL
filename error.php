<?php 
	session_start(); if (!isset($_SESSION['logged'])) { header('Location: index.php'); exit(); }
	
	# SPRAWDZANIE CZY MA JAKĄKOLWIEK LICENCJĘ
	if(!isset($_SESSION['licenseID'])){
		header('Location: dashboard.php');
		exit;
	}
	?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Wykryto błąd...</title>
		<link href="styles/style.css?2" rel="stylesheet" type="text/css"  media="all" />
		<meta charset="utf-8">
	</head>
	<body>
		<!--start-wrap--->
		<div class="wrap">
			<!---start-header---->
				<div class="header">
					<div class="logo">
						<h1><a href="#">Oooo ja Cię kręce... Błąd!</a></h1>
					</div>
				</div>
			<!---End-header---->
			<!--start-content------>
			<div class="content">
				<img src="images/error-img.png" title="error" />
				<p><span><label>O kurcze... </label> </span>Próbowałeś zdobyć dostęp do servera lub produktu, który nie należy do Ciebie! .</p>
				<a href="dashboard.php">POWROT DO PANELU</a>
   			</div>
			<!--End-Cotent------>
		</div>
		<!--End-wrap--->
	</body>
</html>

