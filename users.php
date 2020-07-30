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
	
	if (!empty($_POST)){
		$userUID = $_GET['euser'];
		$coins = $_POST['coins'];
		mysql_result(mysql_query("UPDATE `shop`.`users` SET `coins` = '$coins' WHERE `users`.`uid` = $userUID;"), 0 );
		
		if (!empty($_POST['password'])){
			$password = $_POST['password'];
			$hashPassword = password_hash($password, PASSWORD_DEFAULT);
			mysql_result(mysql_query("UPDATE `shop`.`users` SET `pass` = '$hashPassword' WHERE `users`.`uid` = $userUID;"), 0 );
		}
		
		$blad = '<div class="alert alert-success"> <span>Zmiany zostały zapisane! Kliknij <a href="users.php">TUTAJ</a>aby wrócić do listy użytkowników.</span></div>';

	}
	
	 # USUWSANIE USERA
	 if (isset($_GET['deluser'])) {
		$userUID = $_GET['deluser'];
		 
		# SPRAWDZANIE LICENCJI
		$query="SELECT * FROM `users` WHERE `shopname` LIKE '$shopName'";
		$result = mysql_query($query)
		or die(mysqli_error($result));
		$check = false;
		while ($row = mysql_fetch_array($result)){
			if ($userUID == $row['uid']){
				$check = true;
			}
		}
		if ($check == false){
			header('Location: users.php');
			break;
		}
		 
		$nick = mysql_result( mysql_query("SELECT nick FROM `users` WHERE `uid` = $userUID"), 0 );
		mysql_result( mysql_query("DELETE FROM `users` WHERE `uid` = $userUID"), 0 );
		mysql_result(mysql_query("INSERT INTO `shop`.`logs` (`uid`, `date`, `info`, `type`, `licenseID`) VALUES  (NULL, CURRENT_TIMESTAMP, 'Usunięto użytkownika o nazwie <b>$nick</b>', 'deleteuser', '$licenseID');"), 0 );
		header('Location: users.php');
		
	  }
	
	 # EDYCJA USERA
	 if (isset($_GET['euser'])) {
		$userUID = $_GET['euser'];
		 
		# SPRAWDZANIE LICENCJI
		$query="SELECT * FROM `users` WHERE `shopname` LIKE '$shopName'";
		$result = mysql_query($query)
		or die(mysqli_error($result));
		$checkEditUserOk = false;
		while ($row = mysql_fetch_array($result)){
			if ($userUID == $row['uid']){
				$checkEditUserOk = true;
			}
		}
		if ($checkEditUserOk == false){
			header('Location: users.php');
			break;
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

	<title>Panel Administratora McSklepik.pl McSklepik.pl</title>

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
                <li class="active">
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

					<?php if (isset($_GET['euser'])) {
						$nick = mysql_result( mysql_query("SELECT nick FROM `shops` WHERE `name` = '$shopName'"), 0 );
						$coins = mysql_result( mysql_query("SELECT coins FROM `users` WHERE `nick` = '$nick'"), 0 );
						
						echo '<div class="col-md-14">';
							echo '<div class="card">';
								echo '<div class="header">';
									echo '<h4 class="title">Edycja użytkownika '.$nick.'</img></h4>';
									echo '<p class="category">Edytuj ilość posiadanej waluty, hasło lub email usera.</p>';
								echo '</div>';
								echo '<div class="header col-md-9">';
									echo $blad;
								echo '</div>';
								 echo '<div class="content">';
										
									echo '<div style="margin-left:5px; margin-right:5px; margin-top:20px;" class="row">';
										
									echo '<form method="post" enctype="multipart/form-data">';
                                    echo '<div class="row">';
                                        echo '<div class="col-md-4">';
                                            echo '<div class="form-group">';
                                               echo ' <label>Wprowadź nową liczbę</label>';
                                                echo '<input maxlength="50" name="coins" value="'.$coins.'" type="text" placeholder="Podaj ilość..." class="form-control border-input">';
                                           echo ' </div>';
                                        echo '</div>';
                                        echo '<div class="col-md-4">';
                                            echo '<div class="form-group">';
                                               echo ' <label>Zmień hasło</label>';
                                                echo '<input maxlength="30" name="password" type="password" placeholder="Podaj nowe hasło..." class="form-control border-input">';
                                           echo ' </div>';
                                        echo '</div>';
                                        echo '<div class="col-md-4">';
                                            echo '<div style="margin-top:25px;" class="form-group">';
											echo '<button name="submit" type="submit" class="btn btn-info btn-fill btn-wd">Zapisz ustawienia!</button>';
                                           echo ' </div>';
                                        echo '</div>';
									echo '</div>';
									echo '</form>';
											
												
									echo '</div>';
										echo '<br><br>';
								echo '</div>';
							echo '</div>';
						echo '</div>';
					} else {
					
						echo '<div class="col-md-14">';
							echo '<div class="card">';
								echo '<div class="header">';
									echo '<h4 class="title">Użytkownicy sklepu</img></h4>';
									echo '<p class="category">Znajdziesz tutaj listę wszystkich użytkowników Twojego sklepu.</p>';
								echo '</div>';
								 echo '<div class="content">';
										
									echo '<div style="margin-left:5px; margin-right:5px; margin-top:20px;" class="row">';
										
												
												$query="SELECT * FROM `users` WHERE `shopname` = '$shopName'"; 
												$result = mysql_query($query)
												or die("Query failed");
													
												
												echo    ' <table id="example1" class="table table-bordered">';
												 echo '<thead >';
													  echo '<tr >';
															echo '<th style="width:150px;"><b>Nick</b></th>';
															echo '<th style="width:200px;"><b>E-Mail</b></th>';
															echo '<th style="width:100px;"><b>Stan Konta</b></th>';
															echo '<th style="width:150px;"><b>Ilość zakupów</b></th>';
															echo '<th style="width:20px;"><b><center>Co zrobić?</center></b></th>';
													  echo '</tr>';
												 echo '</thead>';
												 echo '<tbody>';
													
												while ($row = mysql_fetch_array($result)){
													echo '<tr>';
													 
														$nickBuyer = $row['nick'];
														$buyersQuery="SELECT * FROM `buylogs` WHERE `licenseID` LIKE '$licenseID' AND `nick` LIKE '$nickBuyer'";
														$buyersResult = mysql_query($buyersQuery)
														or die(mysqli_error($buyersResult));

														$buyCount = mysql_num_rows($buyersResult);
														
																
														echo '<td><font size="3px"> '.$row['nick'].'</font></td>';
														echo '<td><font size="3px"> '.$row['email'].'</font></td>';
														echo '<td><font size="3px"> '.$row['coins'].'</font></td>';
														echo '<td><font size="3px">'.$buyCount.'</font></td>';
														echo '<td style="text-align: right;">
																														
														<a style="padding-right: 10px;" href="?euser='.$row['uid'].'"><btn class="btn btn-fill btn-md btn-success btn-icon">EDYTUJ</btn></a>';  ?>
														
														<a onclick="return confirm('Czy na pewno chcesz usunąć tego użytkownika?')" style="padding-right: 10px;" href="?deluser=<?php echo $row['uid']; ?>"><btn class="btn btn-md btn-fill btn-danger btn-icon">USUŃ</btn></a>
											
											<?php
													echo '</tr>';
												}
													 
												echo '</tbody>';
											echo '</table>';
											
												
									echo '</div>';
										echo '<br><br>';
								echo '</div>';
							echo '</div>';
						echo '</div>';
					}
					?>
					
					
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
