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
		$voucherUID = $_GET['delete'];
		
		# SPRAWDZANIE LICENCJI
		$query="SELECT * FROM `vouchers` WHERE `licenseID` LIKE '$licenseID'";
		$result = mysql_query($query)
		or die(mysqli_error($result));
		$check = false;
		while ($row = mysql_fetch_array($result)){
			if ($voucherUID == $row['uid']){
				$check = true;
			}
		}
		if ($check == false){
			header('Location: voucher.php');
			break;
		}
		
		
		# KASOWANIE
		$code = mysql_result( mysql_query("SELECT code FROM `vouchers` WHERE `uid` = $voucherUID"), 0 );
		mysql_result( mysql_query("DELETE FROM `vouchers` WHERE `uid` = $voucherUID"), 0 );
		mysql_result(mysql_query("INSERT INTO `shop`.`logs` (`uid`, `date`, `info`, `type`, `licenseID`) VALUES  (NULL, CURRENT_TIMESTAMP, 'Usunięto voucher o nazwie <b>$code</b>', 'deletevoucher', '$licenseID');"), 0 );
		header('Location: voucher.php');	 
	 }

	
	
	$code = mysql_real_escape_string($_POST['code']);
	$coins = $_POST['coins'];

	if(isset($_POST["single"])) {
		if (!empty($_POST['code']) && !empty($_POST['coins'])) {

			$query="SELECT * FROM `vouchers` WHERE `licenseID` LIKE '$licenseID'";
			$result = mysql_query($query)
			or die(mysqli_error($result));
			$errorSingle = 0;
			while ($row = mysql_fetch_array($result)){
				if ($row['code'] == $code){
					$errorSingle = 1;
				}
			}
			
			if ($errorSingle == 0){
				# DODAWANIE DO BAZY DANYCH
				mysql_result(mysql_query("INSERT INTO `shop`.`logs` (`uid`, `date`, `info`, `type`, `licenseID`) VALUES  (NULL, CURRENT_TIMESTAMP, 'Dodano nowy voucher <b>$code</b> o wartości <b>$coins</b>', 'addvoucher', '$licenseID');"), 0 );
				mysql_result(mysql_query("INSERT INTO `shop`.`vouchers` (`uid`, `code`, `player`, `coins`, `status`, `licenseID`) VALUES (NULL, '$code', 'jeszcze nikt', '$coins', '0', '$licenseID');"), 0 );
				$bladSingle = '<div class="alert alert-success"> <span>Voucher został poprawnie dodany!</span></div>';
			}else{
				$bladSingle = '<div class="alert alert-warning"> <span>Voucher o takim kodzie już istnieje!</span></div>';
			}
	 } else {
			$bladSingle = '<div class="alert alert-warning"> <span>Halo! Nie wszystkie pola formularza zostały wypełnione! Prosimy uzupełnić wpisane dane.</span></div>';
	 }
}

	if(isset($_POST["multi"])) {
		if (!empty($_POST['code']) && !empty($_POST['coins'])) {

			$query="SELECT * FROM `vouchers` WHERE `licenseID` LIKE '$licenseID'";
			$result = mysql_query($query)
			or die(mysqli_error($result));
			
			for ($x = 0; $x <= $code; $x++){
				$random = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);
				mysql_result(mysql_query("INSERT INTO `shop`.`vouchers` (`uid`, `code`, `player`, `coins`, `status`, `licenseID`) VALUES (NULL, '$random', 'jeszcze nikt', '$coins', '0', '$licenseID');"), 0 );
			}
			
			# DODAWANIE DO BAZY DANYCH							
			mysql_result(mysql_query("INSERT INTO `shop`.`logs` (`uid`, `date`, `info`, `type`, `licenseID`) VALUES  (NULL, CURRENT_TIMESTAMP, 'Dodano <b>$code</b> losowych voucherów o wartości <b>$coins</b>', 'addvoucher', '$licenseID');"), 0 );
			$bladMulti = '<div class="alert alert-success"> <span>Vouchery zostały poprawnie dodane!</span></div>';
			
	 } else {
			$bladMulti = '<div class="alert alert-warning"> <span>Halo! Nie wszystkie pola formularza zostały wypełnione! Prosimy uzupełnić wpisane dane.</span></div>';
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
                <li class="active">
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
                    
                    <div class="col-lg-6 col-md-6">
                        <div class="card">
                            <div class="header col-md-10">
                                <h4 class="title">Dodaj własny</h4>
                                <p class="category">Dodaj włąsny voucher do listy.<br></p>
                            </div>
                            <div style="padding: 90px 15px 10px 15px;" class="content">
							
									<?php echo $bladSingle; ?>
									
                                <form method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-14">
                                            <div class="form-group">
                                                <label>KOD Vouchera</label>
                                                <input maxlength="30" name="code" type="text" placeholder="Podaj kod vouchera!" class="form-control border-input" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-14">
                                            <div class="form-group">
                                                <label>Wartość Vouchera</label>
                                                <input maxlength="10" name="coins" min="1" type="number" placeholder="Podaj ile punktów ma doładować!" class="form-control border-input" required>
                                            </div>
                                        </div>
                                    </div>

                                   
                                    <div class="text-center">
                                        <button name="single" type="submit" class="btn btn-info btn-fill btn-wd">Dodaj Voucher!</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>
					
					 <div class="col-lg-6 col-md-6">
                        <div class="card">
                            <div class="header col-md-10">
                                <h4 class="title">Dodaj kilka</h4>
                                <p class="category">Dodaj kilka losowych voucherów.<br></p>
                            </div>
                            <div style="padding: 90px 15px 10px 15px;" class="content">
							
									<?php echo $bladMulti; ?>
									
                                <form method="post" enctype="multipart/form-data">
								
                                    <div class="row">
										<div class="col-lg-12 col-md-10">
                                            <div class="form-group">
												<label>Wybierz Ilość</label>
													<select name="code" class="form-control border-input" required>
														<option value="">-- nie wybrano --</option>
														<option value="1">1</option>
														<option value="2">2</option>
														<option value="3">3</option>
														<option value="4">4</option>
														<option value="5">5</option>
														<option value="6">6</option>
														<option value="7">7</option>
														<option value="8">8</option>
														<option value="9">9</option>
														<option value="10">10</option>
														<option value="11">11</option>
														<option value="12">12</option>
														<option value="13">13</option>
														<option value="14">14</option>
														<option value="15">15</option>
													</select>
											</div>
										</div>
									</div>
										
                                    <div class="row">
										<div class="col-lg-12 col-md-10">
                                            <div class="form-group">
                                                <label>Wartość Vouchera</label>
                                                <input maxlength="10" min="1" name="coins" type="number" placeholder="Podaj ile punktów ma doładować!" class="form-control border-input" required>
                                            </div>
                                        </div>
                                    </div>

                                   
                                    <div class="text-center">
                                        <button name="multi" type="submit" class="btn btn-info btn-fill btn-wd">Generuj Vouchery!</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                                
                            </div>
                        </div>
                    </div>
					
					
                    <div class="col-lg-12 col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Vouchery</h4>
                                <p class="category">Tu znajdziesz Twoje wszystkie vouchery!</p>
                            </div>
                            <div style="padding: 30px 15px 10px 15px;" class="content">
							
								<?php 
										
									$query="SELECT * FROM `vouchers` WHERE `licenseID` LIKE '$licenseID'";
									$result = mysql_query($query)
									or die(mysqli_error($result)); 
										
											echo    ' <table id="example1" class="table table-bordered table-striped table-hover">';
											 echo '<thead>';
												  echo '<tr>';
														echo '<th>Kod Vouchera</th>';
														echo '<th>Wartość</th>';
														echo '<th>Kto wykorzystał</th>';
														echo '<th>Status</th>';
														echo '<th width="10px"></th>';
												  echo '</tr>';
											 echo '</thead>';
											 echo '<tbody>';
												
											while ($row = mysql_fetch_array($result)){
												echo '<tr style="border: 0px solid black;">';
													echo '<td style="padding: 6px 8px;">'.$row['code'].'</td>';
													echo '<td style="padding: 6px 8px;">'.$row['coins'].'</td>';
													echo '<td style="padding: 6px 8px;">'.$row['player'].'</td>';
													  if ($row['status'] == "0"){
														  echo '<td><span class="label label-warning">Nie wykorzystany</span>';
													  } elseif ($row['status'] == "1"){
														  echo '<td><span class="label label-success">Wykoszystany</span>';
													  }
													echo '<td style="padding: 6px 8px;">';
													echo '<a href="?delete='.$row['uid'].'"><btn class="btn btn-xs btn-danger btn-icon"><i class="fa fa-close"></i></btn></a>';
													echo '</td>';
													
												 echo '</tr>';
											}
												 
											echo '</tbody>';
										echo '</table>';
								?>
														   

							<hr>
							<div class="stats">
								<i class="ti-timer"></i> Lista...
							</div>
                                
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
