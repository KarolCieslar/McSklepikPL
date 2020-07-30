<?php 
	session_start(); if (!isset($_SESSION['logged'])) { header('Location: index.php'); exit(); }
	include 'config.php'; 
	$user = $_SESSION['user'];
	include 'config.php'; 
	$shopName = mysql_result( mysql_query("SELECT name FROM `shops` WHERE `licenseID` = '$licenseID'"), 0 );
	$coins = mysql_result( mysql_query("SELECT coins FROM `admins` WHERE `nick` = '$user'"), 0 );

	if (!empty($_POST)){
		if (!empty($_POST['name']) && !empty($_POST['type']) && !empty($_POST['time'])) {
			$name = mysql_real_escape_string($_POST['name']);
			$licenseType = $_POST['type'];
			$licenseTime = $_POST['time'];
				
			# BLOKADA TWORZENIA SKLEPU O KONKRETNYCH NAZWACH
			if($name == "demo" || $name == "admin" || $name == "sklep"){
				$blad = '<div class="alert alert-warning"> <span>Przykro nam, ale nie można stworzyć sklepu o takiej nazwie.</div>';
			} else {
			
				# SPRAWDZANIE CZY NAZWA SKLEPU JEST W BAZIE
				$result = mysql_query("SELECT name FROM `shops` WHERE `name` LIKE '$name'") or die(mysqli_error($result));
				$num_rows = mysql_num_rows($result);
				if ($num_rows == 1){
					$blad = '<div class="alert alert-warning"> <span>Nazwa sklepu, która została podana jest już używana przez kogoś innego!</div>';
				} else {
					if ($licenseType == "single"){
						if ($licenseTime == "1"){
							$licensePrice = 10;
							$licenseInfo = 'Licencja SIGNLE na 30 dni dla sklepu '.$name.'';
						} else if ($licenseTime == "2"){
							$licensePrice = 19;
							$licenseInfo = 'Licencja SIGNLE na 60 dni dla sklepu '.$name.'';
						} else if ($licenseTime == "3"){
							$licensePrice = 45;
							$licenseInfo = 'Licencja SIGNLE na 150 dni dla sklepu '.$name.'';
						} else if ($licenseTime == "4"){
							$licensePrice = 100;
							$licenseInfo = 'Licencja SIGNLE na 360 dni dla sklepu '.$name.'';
						}
					}else if ($licenseType == "multi"){
						if ($licenseTime == "1"){
							$licensePrice = 24;
							$licenseInfo = 'Licencja MULTI na 30 dni dla sklepu '.$name.'';
						} else if ($licenseTime == "2"){
							$licensePrice = 40;
							$licenseInfo = 'Licencja MULTI na 60 dni dla sklepu '.$name.'';
						} else if ($licenseTime == "3"){
							$licensePrice = 99;
							$licenseInfo = 'Licencja MULTI na 150 dni dla sklepu '.$name.'';
						} else if ($licenseTime == "4"){
							$licensePrice = 260;
							$licenseInfo = 'Licencja MULTI na 360 dni dla sklepu '.$name.'';
						}
					}
					
					if($coins >= $licensePrice){
					
						# LICENSJA CZAS
						if ($licenseTime == "1"){
							$expireDate = date('d-m-Y', strtotime("+30 days"));
						} else if ($licenseTime == "2"){
							$expireDate = date('d-m-Y', strtotime("+61 days"));
						} else if ($licenseTime == "3"){
							$expireDate = date('d-m-Y', strtotime("+152 days"));
						} else if ($licenseTime == "4"){
							$expireDate = date('d-m-Y', strtotime("+365 days"));
						}
						
						# LICENCJA HASŁO
						$hashPassword = password_hash($password, PASSWORD_DEFAULT);
						
						# LICENCJA ID
						$licenseID = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 45);
						
						mysql_result(mysql_query("INSERT INTO `shop`.`shops` (`uid`, `name`, `license`, `licenseID`, `expire`, `servers`, `nick`, `views`) VALUES (NULL, '$name', '$licenseType', '$licenseID', '$expireDate', '0', '$user', '0');"), 0 );
						mysql_result(mysql_query("INSERT INTO `shop`.`settings` (`uid`, `menuName1`, `menuName2`, `menuName3`, `menuName4`, `menuLink1`, `menuLink2`, `menuLink3`, `menuLink4`, `menuEnable1`, `menuEnable2`, `menuEnable3`, `menuEnable4`, `menuIcon1`, `menuIcon2`, `menuIcon3`, `menuIcon4`, `currencyName`, `pageTitle`, `payEnable`, `indexPage`, `maintenance`, `licenseID`) VALUES (NULL, 'FORUM', 'INFORMACJE', 'KONTAKT', 'INNE', 'http://www.google.pl', 'http://www.google.pl', 'http://www.google.pl', 'http://www.google.pl', 'true', 'false', 'false', 'false', 'fa-forumbee', 'fa-info', 'fa-book', 'fa-bolt', 'PKT', 'Automatyczny Sklep Minecraft', 'true', '<p>To moja strona główna!</p>', 'false', '$licenseID');"), 0 );
						
						# ZABIERANIE COINSÓW
						$coinsNickSQL = $coins - $licensePrice;
						$updateNewCoins = mysql_result(mysql_query("UPDATE `admins` SET `coins` = '$coinsNickSQL' WHERE `nick` LIKE '$user'"), 0 );
						
						$blad = '<div class="alert alert-success"> <span>Twój sklep został pomyślnie założony! <br> Za chwilę zostaniesz przekierowany do panelu administratora!</span></div>';
						echo '<meta http-equiv="refresh" content="4; URL=http://mcsklepik.pl/admin/dashboard.php">';
				
					} else {
						$blad = '<div class="alert alert-warning"> <span>Nie posiadasz wystarczającej ilości coinsów! Doładuj je za pomocą SMS, PAYSAFECARD lub PRZELEWU.</span></div>';
					}
				}
			}
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

	<title>Panel Administratora McSklepik.pl McSklepik.pl</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />


    <!-- Bootstrap core CSS     -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="assets/css/animate.min.css" rel="stylesheet"/>

    <!--  Paper Dashboard core CSS    -->
    <link href="assets/css/paper-dashboard.css?122332" rel="stylesheet"/>

    <!--  Fonts and icons     -->
    <link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>
    <link href="assets/css/themify-icons.css" rel="stylesheet">
	
	<script type="text/javascript">
		function showfield(name){
			
		  if(name=='single')
			  document.getElementById('price').innerHTML='<label><span>Czas licencji</span><select name="time" class="form-control border-input" required><option value="">-- nie wybrano --</option><option value="1">1 miesiąc (10 PKT)</option><option value="2">2 miesiące (19 PKT)</option><option value="3">5 miesiący (45 PKT)</option><option value="4">12 miesiący (96 PKT)</option></select></label>';
		  else if(name=='multi') 
			  document.getElementById('price').innerHTML='<label><span>Czas licencji</span><select name="time" class="form-control border-input" required><option value="">-- nie wybrano --</option><option value="1">1 miesiąc (24 PKT)</option><option value="2">2 miesiące (42 PKT)</option><option value="3">5 miesiący (100 PKT)</option><option value="4">12 miesiący (228 PKT)</option></select></label>';
		 else if(name=='none') 
			  document.getElementById('price').innerHTML='';
		}
		
		// function updateText(text){
			// document.getElementById('nameShop').innerHTML='www.mcsklepik.pl/' + text;
		// }
		
		var input = document.getElementById('name'); 
		alert(input.value);
		input.onkeyup = function() {
			document.getElementById('nameShop').innerHTML = input.value;    
		}
	</script>
	
	<?php $randomName = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, 7); ?>
	
	
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


			<?php if(!isset($_SESSION['licenseID'])){
				echo '<br><br><center><font color="white">
					Nie wybrałeś sklepu<br>
					którym chcesz zarządzać.<br><br>
					Aby to zrobić kliknij
					przycisk <br>"STRONA GŁÓWNA" poniżej.<br>
					Jeśli nie masz jeszcze żadnej
					licencji musisz najpierw
					ją wykupić aby mieć dostęp
					do panelu admina.<br><br></font></center>';
				echo '				
				<ul class="nav">
					<li>
						<a href="dashboard.php">
							<i class="ti-home" style="color: white;"></i>
							<p  style="color: white;">STRONA GŁÓWNA</p>
						</a>
					</li>
					<li class="active-pro">
						<a href="logout.php">
							<i class="ti-export" style="color: white;"></i>
							<p  style="color: white;">WYLOGUJ SIĘ</p>
						</a>
					</li>
				<ul>';
			} else {
			?>
            <ul class="nav">
                <li class="active">
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
			<?php } ?>
    	</div>
    </div>

    <div class="main-panel">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">Panel Administratora McSklepik.pl</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
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
                  
			   	<script type="text/javascript">
					function showfield(name){
						
					  if(name=='single')
						  document.getElementById('price').innerHTML='<label><span>Czas licencji</span><select name="time" class="form-control border-input" required><option value="">-- nie wybrano --</option><option value="1">1 miesiąc (10 PKT)</option><option value="2">2 miesiące (19 PKT)</option><option value="3">5 miesiący (45 PKT)</option><option value="4">12 miesiący (96 PKT)</option></select></label>';
					  else if(name=='multi') 
						  document.getElementById('price').innerHTML='<label><span>Czas licencji</span><select name="time" class="form-control border-input" required><option value="">-- nie wybrano --</option><option value="1">1 miesiąc (24 PKT)</option><option value="2">2 miesiące (42 PKT)</option><option value="3">5 miesiący (100 PKT)</option><option value="4">12 miesiący (228 PKT)</option></select></label>';
					 else if(name=='none') 
						  document.getElementById('price').innerHTML='';
					}
					
					var input = document.getElementById('name'); 
					alert(input.value);
					input.onkeyup = function() {
						document.getElementById('nameShop').innerHTML = input.value;    
					}
				</script>
				
				<?php $randomName = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, 7); ?>
						   
                <div class="row">

					 <div class="col-lg-7 col-md-12">
                        <div class="card">
                            <div class="header col-md-5">
                                <h4 class="title">Zakup nowej licencji</img></h4>
                                <p class="category">Wypełnij fomrularz aby kupić licencję!</p>
                            </div>
                            <div class="text-right header col-md-7 pull-right">
								<h4 class="title" id="nameShop"><b>Twój link do sklepu</b><br><?php echo 'www.<font size="5" color="blue">';echo $randomName; echo '</font>.mcsklepik.pl';?></h4>
                            </div>
                            <div style="padding: 50px 15px 10px 15px;" class="content">
							</div>
                             <div class="content">
									
                                <div style="margin-left:5px; margin-right:5px;" class="row">
									
								<div class="row">
											<div class="header col-md-14">
												<?php echo $blad; ?>
											</div>
										</div>
												
											<form method="post" enctype="multipart/form-data">
												<div class="content">
												
													<div class="row">
														<div class="col-md-14">
															<div class="row">
																<div style="margin-top:5px" class="col-lg-6 col-md-6">
																	<div class="form-group">
																		<label>Wybierz typ licencji</label>
																		<select class="form-control border-input" name="type" id="type" onchange="showfield(this.options[this.selectedIndex].value)" required>
																			<?php if ($licenseType == "single"){
																				echo '<option value="none">-- nie wybrano --</option>';
																				echo '<option value="single" selected>Tylko jeden server</option>';
																				echo '<option value="multi">Nielimitowana ilość serverów</option>';
																			} elseif ($licenseType == "multi"){
																				echo '<option value="none">-- nie wybrano --</option>';
																				echo '<option value="single">Tylko jeden server</option>';
																				echo '<option value="multi" selected>Nielimitowana ilość serverów</option>';	
																			} else {
																				echo '<option value="none">-- nie wybrano --</option>';
																				echo '<option value="single">Tylko jeden server</option>';
																				echo '<option value="multi">Nielimitowana ilość serverów</option>';
																			}
																			
																			?>
																		</select>
																	</div>
																</div>
																<div style="margin-top:5px" class="col-lg-6 col-md-6">
																	<div id="price" class="form-group">
																		<?php echo '<script type="text/javascript"> showfield("'.$licenseType.'"); </script>' ?>
																	</div>
																</div>
															</div>
															<div class="row">
																<div style="margin-top:5px" class="col-lg-12 col-md-12">
																	<div class="form-group">
																		<label>Nazwa sklepu</label>
																	   <input pattern="([A-z0-9]){2,}" value="<?php if (!isset($name)) {echo $randomName; } else {echo $name;}?>" maxlength="20" maxlength="20" type="text" name="name" id="name" class="form-control border-input" required>
																	</div>
																</div>
															</div>
															<div class="row">
																<div style="margin-top:5px" class="col-lg-12 col-md-12">
																	<div class="form-group">
																		<input type="checkbox" name="checkbox" required>
																		<span>Zapoznałem się i potwierdzam <a href="dashboard.php#reg">REGULAMIN SERWISU</a></span>
																	</div>
																</div>
															</div>
															
															
															
														</div>
													</div>
						
						
												   
												   
												   
													<div class="text-center">
														<button name="settings" type="submit" class="btn btn-info btn-fill btn-wd">ZAŁÓŻ SKLEP</button>
													</div>
													<div class="clearfix"></div>
											</div>
										</form>
											
								

									
								
								
								</div>
                            </div>
                        </div>
                    </div>
					
					 <div class="col-lg-5 col-md-12">
                        <div class="card">
                            <div class="header col-md-7">
                                <h4 class="title">Twój portfel: <?php echo $coins; ?></h4>
                                <p class="category">Aby zakupić licencje musisz posiadać coinsy.</p>
                            </div>
                            <div class="text-right header col-md-5 pull-right">
								<a style="margin-left:30px;" href="dashboard.php#coins"><btn class="btn btn-lg btn-success btn-fill btn-icon"><i class="fa fa-money" aria-hidden="true"></i> DOŁADUJ COINSY</btn></a>
                            </div>
                            <div style="padding: 50px 15px 10px 15px;" class="content">
							</div>
                            <div class="content">
                                
								
									
									<div >
									 <hr />
										<font size="5">Typy licencji sklepu</font></b><br><br>
										<ul>
											<li><font size="4">Licencja <b>SINGLE</b>, umożliwia Ci zarządzanie jednym serwerem w Twoim sklepie.<br>
											Możesz dodać nielimitowaną ilość ofert oraz kategorii, masz całkowity dostęp do wszystkich funkcji panelu admina.<br></font></li>
											<br>
											<li><font size="4">Licencja <b>MULTI</b>, która umożliwia Ci zarządzanie nielimitowaną ilością serwerów w Twoim sklepie.<br>
											Możesz dodać nielimitowaną ilość ofert oraz kategorii, masz całkowity dostęp do wszystkich funkcji panelu admina.<br></font></li>
										</ul>
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

	
	<script type="text/javascript">
		var input = document.getElementById('name'); 
		input.onkeyup = function() {
			document.getElementById('nameShop').innerHTML = '<b>Twój link do sklepu</b><br>www.<font color="blue">' + input.value + '</font>.mcsklepik.pl';    
		}
	</script>
	
	
	<script type="text/javascript">
		function openPage(pageName, elmnt, color) {
		// Hide all elements with class="tabcontent" by default */
		var i, tabcontent, tablinks;
		tabcontent = document.getElementsByClassName("tabcontent");
		for (i = 0; i < tabcontent.length; i++) {
			tabcontent[i].style.display = "none";
		}

		// Remove the background color of all tablinks/buttons
		tablinks = document.getElementsByClassName("tablink");
		for (i = 0; i < tablinks.length; i++) {
			tablinks[i].style.backgroundColor = "";
		}

		// Show the specific tab content
		document.getElementById(pageName).style.display = "block";

		// Add the specific color to the button used to open the tab content
		elmnt.style.backgroundColor = color;
	}

		// Get the element with id="defaultOpen" and click on it
		document.getElementById("defaultOpen").click();
	</script>
	
	
	<script type="text/javascript">
	function buyinfo(setting) {
		myOption = setting;
		if (myOption == "1") {
			document.getElementById("koszt").innerHTML = "2.46";
			document.getElementById("numer").innerHTML = "7255";
			document.getElementById("ilecoinsow").innerHTML = "1x";
		}else if (myOption == "2") {
			document.getElementById("koszt").innerHTML = "3.69";
			document.getElementById("numer").innerHTML = "7355";
			document.getElementById("ilecoinsow").innerHTML = "2x";
		}else if (myOption == "3") {
			document.getElementById("koszt").innerHTML = "6.15";
			document.getElementById("numer").innerHTML = "7555";
			document.getElementById("ilecoinsow").innerHTML = "3x";
		}else if (myOption == "4") {
			document.getElementById("koszt").innerHTML = "11.07";
			document.getElementById("numer").innerHTML = "7955";
			document.getElementById("ilecoinsow").innerHTML = "5x";
		}else if (myOption == "5") {
			document.getElementById("koszt").innerHTML = "17.22";
			document.getElementById("numer").innerHTML = "91455";
			document.getElementById("ilecoinsow").innerHTML = "7x";
		}else if (myOption == "6") {
			document.getElementById("koszt").innerHTML = "23.37";
			document.getElementById("numer").innerHTML = "91955";
			document.getElementById("ilecoinsow").innerHTML = "10x";
		}else if (myOption == "7") {
			document.getElementById("koszt").innerHTML = "30.75";
			document.getElementById("numer").innerHTML = "92520";
			document.getElementById("ilecoinsow").innerHTML = "13x";
		}
	}

	</script>
</html>