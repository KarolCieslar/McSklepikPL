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

	

	$licenseType = mysql_result( mysql_query("SELECT license FROM `shops` WHERE `nick` = '$user'"), 0 );
	$query="SELECT * FROM `servers` WHERE `licenseID` LIKE '$licenseID'";
	$result = mysql_query($query)
	or die(mysqli_error($result));
	$num_rows = mysql_num_rows($result);
	if ($licenseType == "single"){
		if ($num_rows >= 0){
			header('Location: servers.php');
			break;
		}
	}
	
	
	$name = mysql_real_escape_string($_POST['name']);
	$color = mysql_real_escape_string($_POST['color']);
	$ip = mysql_real_escape_string($_POST['ip']);
	$port = $_POST['port'];
	$rconPort = $_POST['rconPort'];
	$rconPass = mysql_real_escape_string($_POST['rconPass']);

	if (!empty($_POST)){
		if (!empty($_POST['name']) && !empty($_POST['ip']) && !empty($_POST['port']) && !empty($_POST['rconPort']) && !empty($_POST['rconPass'])) {

			# DODAWANIE DO BAZY DANYCH
			mysql_result(mysql_query("INSERT INTO `shop`.`logs` (`uid`, `date`, `info`, `type`, `licenseID`) VALUES  (NULL, CURRENT_TIMESTAMP, 'Dodano nowy server o nazwie <b>$name</b> ($ip:$port)', 'addserver', '$licenseID');"), 0 );
			mysql_result(mysql_query("INSERT INTO `shop`.`servers` (`uid`, `name`, `color`, `ip`, `port`, `rconPass`, `rconPort`, `licenseID`) VALUES (NULL, '$name', '$color', '$ip', '$port', '$rconPass', '$rconPort', '$licenseID');"), 0 );
 			$blad = '<div class="alert alert-success"> <span>Nowy server został dodany pomyślnie! Przejdź do zakładki SERVERY aby nim zarządzać!</span></div>';

	 } else {
			$blad = '<div class="alert alert-warning"> <span>Halo! Nie wszystkie pola formularza zostały wypełnione! Prosimy uzupełnić wpisane dane.</span></div>';
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
    <script src="assets/js/jquery-1.10.2.js" type="text/javascript"></script>

    <!-- Animation library for notifications   -->
    <link href="assets/css/animate.min.css" rel="stylesheet"/>

    <!--  Paper Dashboard core CSS    -->
    <link href="assets/css/paper-dashboard.css?21" rel="stylesheet"/>



    <!--  Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>
    <link href="assets/css/themify-icons.css" rel="stylesheet">
	
	<script type="text/javascript" src="assets/js/farbtastic.js"></script>
	<link rel="stylesheet" href="styles/farbtastic.css?132" type="text/css" />

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
                <li>
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
							<a href="http://www.mcsklepik.pl/<?php echo mysql_result( mysql_query("SELECT name FROM `shops` WHERE `licenseID` = '$licenseID'"), 0 ); ?>">
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
                    
                    <div class="col-lg-6 col-md-6">
                        <div class="card">
                            <div style="padding-bottom:10px;" class="header col-md-10">
                                <h4 class="title">Dodaj serwer</h4>
                                <p class="category">Uzupełnij pola poniżej aby dodać server</p>
                            </div>
							<div style="padding-left:10px;" class="row">
								<div class="header col-md-10">
									<?php echo $blad; ?>
								</div>
                            </div>
                            <div class="content">
                                <form method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-14">
                                            <div class="form-group">
                                                <label>Nazwa servera</label>
                                                <input maxlength="50" name="name" type="text" placeholder="Podaj nazwę serwera!" class="form-control border-input" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
										<div class="col-lg-6 col-md-4">
                                            <div class="form-group">
                                                <label>IP Serwera</label>
                                                <input maxlength="30" name="ip" type="text" placeholder="Podaj IP Serwera!" class="form-control border-input" required>
                                            </div>
                                        </div>
										<div class="col-lg-6 col-md-4">
                                            <div class="form-group">
                                                <label>Port Serwera</label>
                                                <input maxlength="15" min="1" name="port" type="number" placeholder="Podaj port Serwera!" class="form-control border-input" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
										<div class="col-lg-6 col-md-4">
                                            <div class="form-group">
                                                <label>Port RCON Serwera</label>
                                                <input maxlength="15" min="1" name="rconPort" type="number" placeholder="Podaj port RCON do serwera!" class="form-control border-input" required>
                                            </div>
                                        </div>
										<div class="col-lg-6 col-md-4">
                                            <div class="form-group">
                                                <label>Hasło RCON Serwera</label>
                                                <input maxlength="30" name="rconPass" type="text" placeholder="Podaj hasło RCON do serwera!" class="form-control border-input" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
										<div class="col-md-14">
                                            <div class="form-group">
                                                <label>Wybierz kolor, który wyświetlany będzie w sklepie</label>
                                                <input id="color" value="#123456" name="color" type="text" class="form-control border-input">
												<div id="colorpicker"></div>
											</div>
                                        </div>
                                    </div>


                                   
                                    <div class="text-center">
                                        <button name="submit" type="submit" class="btn btn-info btn-fill btn-wd">Dodaj nowy serwer!</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>
					
                    <div class="col-lg-6 col-md-6">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Instrukcja dodawania nowego servera</h4>
                                <p class="category">Przyczytaj te ważne informacje aby dowiedzieć się jak poprawnie dodać server.</p>
                            </div>
                            <div class="content">
                                <br>
									
								<div style="margin-left:10px;"><b><font size="4">1.</font></b> Podaj nazwę serwera. Będzie ona wyświetlana na głównej stronie Twojego sklepu.	<br><br></div>
								<div style="margin-left:10px;"><b><font size="4">2.</font></b> Wprowadź IP serwera. Może być numeryczne lub domenowe.<br><br></div>
								<div style="margin-left:10px;"><b><font size="4">3.</font></b> Podaj PORT serwera. Pamiętaj, że wyżej podawałeś TYLKO IP!	<br><br></div>
								<div style="margin-left:10px;"><b><font size="4">4.</font></b> Ustaw odpowiednie hasło i port RCON do serwera<br></div>
								<div style="margin-left:20px;"><b><font size="3">a)</font></b> Dodaj linijkę "enable-rcon=true" w pliku server.properties w głównym katalogu servera.  <br></div>
								<div style="margin-left:20px;"><b><font size="3">b)</font></b> Dodaj linijkę "rcon.port=TWÓJ_PORT" w pliku server.properties w głównym katalogu servera.  <br> </div>
								<div style="margin-left:20px;"><b><font size="3">c)</font></b> Dodaj linijkę "rcon.password=TWOJE_HASŁO" w pliku server.properties w głównym katalogu servera.  <br></div>  <br>
								<div style="margin-left:10px;"><b><font size="4">5.</font></b> Wpisz chwilę temu ustawione dane do formularza<br><br></div>
								<div style="margin-left:10px;"><b><font size="4">6.</font></b> Po dodaniu servera przejdź do zakładki SERWERY i sprawdź połączenie klikając zieloną ikonę, która znajduje się po prawej stronie.<br></div>
                                
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
	
	<script type="text/javascript">

	  $(document).ready(function() {

		$('#colorpicker').farbtastic('#color');

	  });

	</script>


</html>
