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

 	# USUWSANIE SERVERA
	 if (isset($_GET['delete'])) {
		$offerUID = $_GET['delete'];
		 
		# SPRAWDZANIE LICENCJI
		$query="SELECT * FROM `products` WHERE `licenseID` LIKE '$licenseID'";
		$result = mysql_query($query)
		or die(mysqli_error($result));
		$check = false;
		while ($row = mysql_fetch_array($result)){
			if ($offerUID == $row['uid']){
				$check = true;
			}
		}
		if ($check == false){
			header('Location: offerts.php');
			break;
		}
		 
		$deleteIMG = mysql_result( mysql_query("SELECT img FROM `products` WHERE `uid` = $offerUID"), 0 );
		unlink("../uploads/offerts/".$deleteIMG."");
		
		$name = mysql_result( mysql_query("SELECT name FROM `products` WHERE `uid` = $offerUID"), 0 );
		mysql_result( mysql_query("DELETE FROM `products` WHERE `uid` = $offerUID"), 0 );
		mysql_result(mysql_query("INSERT INTO `shop`.`logs` (`uid`, `date`, `info`, `type`, `licenseID`) VALUES  (NULL, CURRENT_TIMESTAMP, 'Usunięto ofertę o nazwie <b>$name</b>', 'deleteoffer', '$licenseID');"), 0 );
		header('Location: offerts.php');
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
    <link href="assets/css/paper-dashboard.css?1233" rel="stylesheet"/>



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
                <li class="active">
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
                            <div class="header col-md-8">
                                <h4 class="title">Lista Twoich Ofert</h4>
                                <p class="category">Tutaj można edytować, usuwać lub dodawać nowe oferty.</p>
                            </div>
                            <div class="text-right header pull-right">
								<?php
									$queryButtonCheck="SELECT * FROM `servers` WHERE `licenseID` LIKE '$licenseID'";
									$resultButtonCheck = mysql_query($queryButtonCheck)
									or die(mysqli_error($resultButtonCheck));
										
									$num_rowsButtonCheck = mysql_num_rows($resultButtonCheck);
									if ($num_rowsButtonCheck == 0){
										echo '<button type="button" class="btn btn-info disabled btn-fill">DODAJ OFERTĘ</button>';
									} else {
										echo '<a href="addOffer.php" class="btn btn-info btn-fill">DODAJ OFERTĘ</a></td>';
									}
								?>
                            </div>
							
						
						
						
							
                            <div style="padding: 50px 15px 10px 15px;" class="content table-responsive table-full-width">
						
						

							
										<?php 
												
											$query1="SELECT * FROM `servers` WHERE `licenseID` LIKE '$licenseID'";
											$result1 = mysql_query($query1)
											or die(mysqli_error($result1));
												
											$num_rows = mysql_num_rows($result1);
											if ($num_rows == 0){
												echo '
												<div style="padding: 0px 37px 18px 34px;">
													<h3><br> Niestety, nie posiadasz dodanego żadnego servera!<h3>
												</div>
												';
											} else {

												while ($row1 = mysql_fetch_array($result1)){
													
													echo '<h2 style="margin-left:20px;"><small>- - - Server '.$row1['name'].' - - -</small></h2>';
													
													$serverUIDTemp = $row1['uid'];
													$query="SELECT * FROM `products` WHERE `serverUID` LIKE '$serverUIDTemp'";
													$result = mysql_query($query)
													or die(mysqli_error($result));
													
													$num_rows = mysql_num_rows($result);
													if ($num_rows == 0){
														echo '
														<div style="padding: 0px 37px 18px 34px;">
															<h3><br> Niestety, brak jakichkolwiek ofert dodanych do tego servera!<h3>
														</div>
														';
													}
													else {
													
													 echo '<table class="table table-striped">';
														echo '<thead>';
															 echo '<th width="5%" ><b>Obrazek</b></th>';
															echo '<th width="25%" ><b>Nazwa</b></th>';
															echo '<th width="10%" ><b>Serwer</b></th>';
															echo '<th width="17%" ><b>Komendy</b></th>';
															echo '<th width="10%" ><b>Kategoria</b></th>';
															echo '<th width="10%" ><b>Cena</b></th>';
															echo '<th style="text-align: right;"><b>Co chcesz zrobić?</b></th>';
														echo '</thead>';
														echo '<tbody>';
													
														while ($row = mysql_fetch_array($result)){
															echo '<tr>';
																echo '<td><img width="60" height="60" src="../uploads/offerts/'.$row['img'].'" /></td>';
																echo '<td>'.$row['name'].'</td>';
																echo '<td>'.$row['server'].'</td>';
																$commandsReplace = str_replace("|.|","<br>",$row['commands']);
																$uidCategory = $row['category'];
																$categoryName = mysql_result( mysql_query("SELECT name FROM `categories` WHERE `uid` = $uidCategory"), 0 ); 
																echo '<td>'.$commandsReplace.'</td>';
																echo '<td>'.$categoryName.'</td>';
																echo '<td>'.$row['price'].'</td>';
																echo '<td style="text-align: right;">
																																
																<a href="editOffer.php?uid='.$row['uid'].'"><btn class="btn btn-md btn-fill btn-success btn-icon">EDYTUJ</btn></a>
																<a href="copyOffer.php?uid='.$row['uid'].'"><btn class="btn btn-md btn-fill btn-primary btn-icon">KOPIUJ</btn></a>'; ?>
																
																<a onclick="return confirm('Czy na pewno chcesz usunąć tą ofertę?')" href="?delete=<?php echo $row['uid']; ?>"><btn class="btn btn-md btn-danger btn-fill btn-icon">USUŃ</btn></a>
																		
														<?php	 echo '</tr>';
													
														}
														
													
															echo '</tbody>';
													  echo '</table> <hr><br>';
													}
													
												}
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
	
	
	<script src="assets/js/CategoryList.js" type="text/javascript"></script>
	
	<!--  Charts Plugin -->
	<script src="assets/js/chartist.min.js"></script>

    <!--  Notifications Plugin    -->
    <script src="assets/js/bootstrap-notify.js"></script>
	
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
