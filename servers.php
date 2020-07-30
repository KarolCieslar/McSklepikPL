<?php
	session_start(); if (!isset($_SESSION['logged'])) { header('Location: index.php'); exit(); }
	
	# SPRAWDZANIE CZY MA JAKĄKOLWIEK LICENCJĘ
	if(!isset($_SESSION['licenseID'])){
		header('Location: dashboard.php');
		exit;
	}
	$licenseID = $_SESSION['licenseID'];
	$user = $_SESSION['user'];
	include 'config.php'; 
	$shopName = mysql_result( mysql_query("SELECT name FROM `shops` WHERE `licenseID` = '$licenseID'"), 0 );
	$errorMsg = "";
	
 	# USUWSANIE SERVERA
	 if (isset($_GET['delete'])) {
		$serverUID = $_GET['delete'];
		
		# SPRAWDZANIE LICENCJI
		$query="SELECT * FROM `servers` WHERE `licenseID` LIKE '$licenseID'";
		$result = mysql_query($query)
		or die(mysqli_error($result));
		$check = false;
		while ($row = mysql_fetch_array($result)){
			if ($serverUID == $row['uid']){
				$check = true;
			}
		}
		if ($check == false){
			header('Location: servers.php');
			break;
		}
		
		
		# KASOWANIE
		$licenseID = mysql_result( mysql_query("SELECT licenseID FROM `servers` WHERE `uid` = $serverUID"), 0 );
		$name = mysql_result( mysql_query("SELECT name FROM `servers` WHERE `uid` = $serverUID"), 0 );
		mysql_result( mysql_query("DELETE FROM `servers` WHERE `uid` = $serverUID"), 0 );
		mysql_result( mysql_query("DELETE FROM `products` WHERE `serverUID` = $serverUID"), 0 );
		mysql_result( mysql_query("DELETE FROM `categories` WHERE `serverUID` = $serverUID"), 0 );
		mysql_result(mysql_query("INSERT INTO `shop`.`logs` (`uid`, `date`, `info`, `type`, `licenseID`) VALUES  (NULL, CURRENT_TIMESTAMP, 'Usunięto server o nazwie <b>$name</b> oraz wszystkie jego oferty', 'deleteserver', '$licenseID');"), 0 );
		header('Location: servers.php');
	  }

	  
	 if (isset($_GET['testRCON'])) {
		require_once("inc/MinecraftRcon.class.php"); // klasa do połączenia RCON
		$serverUID = $_GET['testRCON'];
		
		# SPRAWDZANIE LICENCJI
		$query="SELECT * FROM `servers` WHERE `licenseID` LIKE '$licenseID'";
		$result = mysql_query($query)
		or die(mysqli_error($result));
		$check = false;
		while ($row = mysql_fetch_array($result)){
			if ($serverUID == $row['uid']){
				$check = true;
			}
		}
		if ($check == false){
			header('Location: servers.php');
			break;
		}

		$name = mysql_result( mysql_query("SELECT name FROM `servers` WHERE `uid` = $serverUID"), 0 );
		$rconPass = mysql_result( mysql_query("SELECT rconPass FROM `servers` WHERE `uid` = $serverUID"), 0 );
		$rconPort = mysql_result( mysql_query("SELECT rconPort FROM `servers` WHERE `uid` = $serverUID"), 0 );
		$ip = mysql_result( mysql_query("SELECT ip FROM `servers` WHERE `uid` = $serverUID"), 0 );
		$TIMEOUT=1;
		$error = 0;

		 try{
			$rconHandle = new MinecraftRcon( );
			$rconHandle->Connect( $ip, $rconPort, $rconPass, $TIMEOUT );
			$rconHandle->Command('RconTestConnection');
			$error = 0;
		 } catch(Exception $e){
			$error = 1;
		 }
		 
		if ($error == 1){
			$errorMsg = '<div class="alert alert-warning"> <span>Nie możemy nawiązać połączenia z serverem <b>'.$name.'</b>... <br><br> <b>Co mam zrobić?</b><br> 1. Sprawdź poprawność wpisanego hasła oraz portu RCON<br> 2. Sprawdź czy w server.properties znajduje się linijka "rcon-enable=true"<br> 3. Odblokuj porty RCON jeśli Twój server stoi na VPS</span></div>';

		} elseif ($error == 0) {
			$errorMsg = '<div class="alert alert-success"> <span>Połączenie z serverem <b>'.$name.'</b> nawiązane! <br> Możesz bez przeszkód kontynuować prowadzenie sklepu.</span></div>';
		} 
	 }
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
	<link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicon.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Panel Administratora McSklepik.pl</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />


    <!-- Bootstrap core CSS     -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="assets/css/animate.min.css" rel="stylesheet"/>

    <!--  Paper Dashboard core CSS    -->
    <link href="assets/css/paper-dashboard.css?21" rel="stylesheet"/>



    <!--  Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>
    <link href="assets/css/themify-icons.css" rel="stylesheet">

</head>
	 

<body>

<div class="wrapper">
    <div class="sidebar" data-background-color="white" data-active-color="danger">

    <!--
		Tip 1: you can change the color of the sidebar's background using: data-background-color="white | black"
		Tip 2: you can change the color of the active button using the data-active-color="primary | info | success | warning | danger"
	-->

	
	<div class="sidebar-wrapper" style=" background: url(inc/bang.png); "> 
            <div class="logo">
               <a href="dashboard.php"><a href="dashboard.php"><img src="inc/logos.png" alt="www.McSklepik.pl" style="width: 210px;"></a></a>
            </div>

            <ul class="nav">
                <li>
                    <a href="dashboard.php">
                        <i class="ti-home" style="color: white;"></i>
                        <p  style="color: white;">STRONA GŁÓWNA</p>
                    </a>
                </li>
                <li>
                    <a href="logs.php">
                        <i class="ti-book" style="color: white;"></i>
                        <p  style="color: white;">Logi Panelu</p>
                    </a>
                </li>
                <li>
                    <a href="payments.php">
                        <i class="ti-wallet" style="color: white;"></i>
                        <p  style="color: white;">Historia Zakupów</p>
                    </a>
                </li>
				 <br>
                <li class="active">
                    <a href="servers.php">
                        <i class="ti-server" style="color: white;"></i>
                        <p  style="color: white;">Serwery</p>
                    </a>
                </li>
                <li>
                    <a href="offerts.php">
                        <i class="ti-shopping-cart" style="color: white;"></i>
                        <p  style="color: white;">Usługi</p>
                    </a>
                </li>
                <li>
                    <a href="categories.php">
                        <i class="ti-filter" style="color: white;"></i>
                        <p  style="color: white;">Kategorie</p>
                    </a>
                </li>
				<br>
                <li>
                    <a href="voucher.php">
                        <i class="ti-gift" style="color: white;"></i>
                        <p  style="color: white;">Vouchery</p>
                    </a>
                </li>
                <li>
                    <a href="users.php">
                        <i class="ti-user" style="color: white;"></i>
                        <p  style="color: white;">Użytkownicy</p>
                    </a>
                </li>
                <li>
                    <a href="settings.php">
                        <i class="ti-settings" style="color: white;"></i>
                        <p  style="color: white;">Ustawienia</p>
                    </a>
                </li>
				<li class="active-pro">
                    <a href="logout.php">
                        <i class="ti-export"  style="color: white;"></i>
                        <p  style="color: white;">WYLOGUJ SIĘ</p>
                    </a>
                </li>
            </ul>
    	</div>
    </div>

    <div class="main-panel">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">Zarządzasz sklepem o nazwie: <b><?php echo mysql_result( mysql_query("SELECT name FROM `shops` WHERE `licenseID` = '$licenseID'"), 0 ); ?></b></a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
							<a href="http://<?php echo mysql_result( mysql_query("SELECT name FROM `shops` WHERE `licenseID` = '$licenseID'"), 0 ); ?>.mcsklepik.pl">
                                <i class="ti-panel"></i>
								<p>PRZEJDŹ DO SKLEPU</p><br>
                            </a>
                        </li>
						<li>
                            <a href="#">
                                <i class="ti-user"></i>
								<p><?php echo $_SESSION['user'];?></p>
                            </a>
                        </li>
                    </ul>

                </div>
            </div>
        </nav>

        <div class="content">
            <div class="container-fluid">
               
			                  
			                   <div class="row">
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="content">
                                <div class="row">
                                    <div class="col-xs-5">
                                        <div class="icon-big icon-warning text-center">
                                            <i class="ti-server"></i>
                                        </div>
                                    </div>
                                    <div class="col-xs-7">
                                        <div class="numbers">
                                            <p>Twoje zarobki</p>
                                            <?php echo mysql_result( mysql_query("SELECT SUM(coins) FROM buylogs WHERE licenseID='$licenseID'"), 0 ); + 0; ?> zł
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <hr />
                                    <div class="stats">
                                        <i class="ti-reload"></i> Ta wartość odświeżana jest natychmiastowo...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="content">
                                <div class="row">
                                    <div class="col-xs-5">
                                        <div class="icon-big icon-success text-center">
                                            <i class="ti-user"></i>
                                        </div>
                                    </div>
                                    <div class="col-xs-7">
                                        <div class="numbers">
                                            <p>Zarejestrowani</p>
                                            <?php
												$buyersQuery="SELECT * FROM `users` WHERE `shopname` LIKE '$shopName'";
												$buyersResult = mysql_query($buyersQuery)
												or die(mysqli_error($buyersResult));

												$buyCount = mysql_num_rows($buyersResult);
												echo $buyCount;
											?>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <hr />
                                    <div class="stats">
                                        <i class="ti-reload"></i> Ta wartość odświeżana jest co kilka minut...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="content">
                                <div class="row">
                                    <div class="col-xs-5">
                                        <div class="icon-big icon-danger text-center">
                                            <i class="ti-pulse"></i>
                                        </div>
                                    </div>
                                    <div class="col-xs-7">
                                        <div class="numbers">
                                            <p>Wyświetlenia sklepu</p>
                                            <?php echo mysql_result( mysql_query("SELECT views FROM shops WHERE licenseID='$licenseID'"), 0 ); + 0; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <hr />
                                    <div class="stats">
                                        <i class="ti-reload"></i> Ta wartość odświeżana jest co kilka minut...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="content">
                                <div class="row">
                                    <div class="col-xs-5">
                                        <div class="icon-big icon-info text-center">
                                            <i class="ti-world"></i>
                                        </div>
                                    </div>
                                    <div class="col-xs-7">
                                        <div class="numbers">
                                            <p>Sprzedanych usług</p>
                                            <?php
												$buyersQuery="SELECT * FROM `buylogs` WHERE `licenseID` LIKE '$licenseID'";
												$buyersResult = mysql_query($buyersQuery)
												or die(mysqli_error($buyersResult));

												$buyCount = mysql_num_rows($buyersResult);
												echo $buyCount;
											?>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <hr />
                                    <div class="stats">
                                        <i class="ti-reload"></i> Ta wartość odświeżana jest natychmiastowo...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
			   
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="card">
                            <div class="header col-md-7">
                                <h4 class="title">Lista Twoich serverów</h4>
                                <p class="category">Tutaj można edytować, usuwać lub dodawać nowe servery.</p>
                            </div>
							
                            <div class="text-right header col-md-5 pull-right">
							<?php 
								
								$licenseType = mysql_result( mysql_query("SELECT license FROM `shops` WHERE `nick` = '$user'"), 0 );
								$query="SELECT * FROM `servers` WHERE `licenseID` LIKE '$licenseID'";
								$result = mysql_query($query)
								or die(mysqli_error($result));
								$num_rows = mysql_num_rows($result);
								if ($licenseType == "single"){
									if ($num_rows == 0){
										echo '<a href="addServer.php" class="btn btn-info btn-fill">DODAJ SERVER</a></td>';
									} else { 
										echo '<br><font color="red"><b>Posiadasz licencję, która umożliwa obsługę TYLKO jednego servera. <br>Przejdź do sekcji USTAWIENIA aby zmienić licencję na nielimitowaną ilość serverów.</b></font>';
									}
								} else {
									echo '<a href="addServer.php" class="btn btn-info btn-fill">DODAJ SERVER</a></td>';
								}
								
								
								?>
							
                            </div>
                            <div style="padding: 100px 15px 10px 15px;" class="content">
								<?php echo $errorMsg; ?>
							</div>
                            <div style="padding: 5px 15px 10px 15px;" class="content table-responsive table-full-width">
										<?php 
													
											$query="SELECT * FROM `servers` WHERE `licenseID` LIKE '$licenseID'";
											$result = mysql_query($query)
											or die(mysqli_error($result));
											
											$num_rows = mysql_num_rows($result);
											if ($num_rows == 0){
												echo '
												<div style="padding: 2px 5px 25px 34px;">
													<h3><br> Niestety, brak jakichkolwiek serverów! Dodaj jakiś używając przycisku po prawej stronie! <h3>
												</div>
												';
											}
											else {
											
											 echo '<table class="table table-striped">';
												echo '<thead>';
													echo '<th width="5%" ><b>Kolor</b></th>';
													echo '<th width="25%" ><b>Nazwa</b></th>';
													echo '<th width="25%" ><b>IP Serwera</b></th>';
													echo '<th width="20%" ><b>Port Serwera</b></th>';
													echo '<th style="text-align: right;"><b>Co chcesz zrobić?</b></th>';
												echo '</thead>';
												echo '<tbody>';
											
												while ($row = mysql_fetch_array($result)){
													echo '<tr>';
														echo '<td><div style="width: 40px; height: 40px; background-color: '.$row['color'].'"</td>';
														echo '<td>'.$row['name'].'</td>';
														echo '<td>'.$row['ip'].'</td>';
														echo '<td>'.$row['port'].'</td>';
														echo '<td style="text-align: right;">
														
														<a href="?testRCON='.$row['uid'].'"><btn class="btn btn-md btn-success btn-fill btn-icon">SPRAWDŹ</btn></a>
														<a href="editServer.php?uid='.$row['uid'].'"><btn class="btn btn-md btn-fill btn-primary btn-icon">EDYTUJ</btn></a>'; ?>
														
														<a onclick="return confirm('Czy na pewno chcesz usunąć ten serwer?')" href="?delete=<?php echo $row['uid']; ?>"><btn class="btn btn-md btn-danger btn-fill btn-icon">USUŃ</btn></a>
														
												<?php
													 echo '</tr>';
											
												}
												
											
													echo '</tbody>';
											  echo '</table>';
											}
											
										  
										?>
                                        
													
													 

                            </div>

                   
					</div>
				</div>
			</div>
					
				
            </div>
        </div>


        <footer class="footer">
            <div class="container-fluid">
                <nav class="pull-left">
                    <ul>
                        <li>
                          Potrzebujesz pomocy? Napisz: skrinszot[monkey]wp.pl lub wejdź na TeamSpeak: ts-pl.net&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;www.mcSklepik.pl
                        </li>
                    </ul>
                </nav>
                <div class="copyright pull-right">
                    Design by Creative-Tim | Code: <a href="http://www.globoox.pl">GlobooX</a> &copy; <script>document.write(new Date().getFullYear())</script>
                </div>
            </div>
        </footer>

    </div>
</div>


</body>

    <!--   Core JS Files   -->
    <script src="assets/js/jquery-1.10.2.js" type="text/javascript"></script>
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>

	<!--  Checkbox, Radio & Switch Plugins -->
	<script src="assets/js/bootstrap-checkbox-radio.js"></script>

	<!--  Charts Plugin -->
	<script src="assets/js/chartist.min.js"></script>

    <!--  Notifications Plugin    -->
    <script src="assets/js/bootstrap-notify.js"></script>

    <!--  Google Maps Plugin    -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>

    <!-- Paper Dashboard Core javascript and methods for Demo purpose -->
	<script src="assets/js/paper-dashboard.js"></script>

	<!-- Paper Dashboard DEMO methods, don't include it in your project! -->
	<script src="assets/js/demo.js"></script>

	
    <!-- DATA TABES SCRIPT -->
    <script src="plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
    <script src="plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
	    <!-- page script -->
    <script type="text/javascript">
      $(function () {
        $("#example1").dataTable();
        $('#example2').dataTable({
          "bPaginate": true,
          "bLengthChange": false,
          "bFilter": false,
          "bSort": true,
          "bInfo": true,
          "bAutoWidth": false
        });
      });
    </script>

</html>
