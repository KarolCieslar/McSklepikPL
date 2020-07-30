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
	
	if(isset($_POST["menu"])) {
		$menuName1 = mysql_real_escape_string($_POST['menuName1']); mysql_real_escape_string($menuLink1 = $_POST['menuLink1']); $menuIcon1 = $_POST['menuIcon1'];
		$menuName2 = mysql_real_escape_string($_POST['menuName2']); mysql_real_escape_string($menuLink2 = $_POST['menuLink2']); $menuIcon2 = $_POST['menuIcon2'];
		$menuName3 = mysql_real_escape_string($_POST['menuName3']); mysql_real_escape_string($menuLink3 = $_POST['menuLink3']); $menuIcon3 = $_POST['menuIcon3'];
		$menuName4 = mysql_real_escape_string($_POST['menuName4']); mysql_real_escape_string($menuLink4 = $_POST['menuLink4']); $menuIcon4 = $_POST['menuIcon4'];
		
		if (isset($_POST['menuEnable1'])){
			$menuEnableCheck1 = "true";
		} else{
			$menuEnableCheck1 = "false";
		}
		
		if (isset($_POST['menuEnable2'])){
			$menuEnableCheck2 = "true";
		} else{
			$menuEnableCheck2 = "false";
		}
		
		if (isset($_POST['menuEnable3'])){
			$menuEnableCheck3 = "true";
		} else{
			$menuEnableCheck3 = "false";
		}
		
		if (isset($_POST['menuEnable4'])){
			$menuEnableCheck4 = "true";
		} else{
			$menuEnableCheck4 = "false";
		}

		$blad1 = '<div class="alert alert-success"> <span>Nowe ustawienia zostały zapisane pomyślnie!</span></div>';
		mysql_result(mysql_query("UPDATE `shop`.`settings` SET `menuIcon1` = '$menuIcon1', `menuIcon2` = '$menuIcon2', `menuIcon3` = '$menuIcon3', `menuIcon4` = '$menuIcon4', `menuEnable1` = '$menuEnableCheck1', `menuEnable2` = '$menuEnableCheck2', `menuEnable3` = '$menuEnableCheck3', `menuEnable4` = '$menuEnableCheck4', `menuName1` = '$menuName1', `menuName2` = '$menuName2', `menuName3` = '$menuName3', `menuName4` = '$menuName4', `menuLink1` = '$menuLink1', `menuLink2` = '$menuLink2', `menuLink3` = '$menuLink3', `menuLink4` = '$menuLink4' WHERE `settings`.`licenseID` = '$licenseID';"), 0 );

	}    
	
	# SLIDER
	if(isset($_POST["slider"])) {
		$sliderImg1 = $_POST['sliderImg1']; mysql_real_escape_string($sliderLink1 = $_POST['sliderLink1']);
		$sliderImg2 = $_POST['sliderImg2']; mysql_real_escape_string($sliderLink2 = $_POST['sliderLink2']);
		$sliderImg3 = $_POST['sliderImg3']; mysql_real_escape_string($sliderLink3 = $_POST['sliderLink3']);
		$sliderImg4 = $_POST['sliderImg4']; mysql_real_escape_string($sliderLink4 = $_POST['sliderLink4']);
		
		if (isset($_POST['sliderEnable1'])){
			$sliderEnableCheck1 = "true";
		} else{
			$sliderEnableCheck1 = "false";
		}
		
		if (isset($_POST['sliderEnable2'])){
			$sliderEnableCheck2 = "true";
		} else{
			$sliderEnableCheck2 = "false";
		}
		
		if (isset($_POST['sliderEnable3'])){
			$sliderEnableCheck3 = "true";
		} else{
			$sliderEnableCheck3 = "false";
		}
		
		if (isset($_POST['sliderEnable4'])){
			$sliderEnableCheck4 = "true";
		} else{
			$sliderEnableCheck4 = "false";
		}

		$bladSlider = '<div class="alert alert-success"> <span>Nowe ustawienia zostały zapisane pomyślnie!</span></div>';
		mysql_result(mysql_query("UPDATE `shop`.`settings` SET `sliderEnable1` = '$sliderEnableCheck1', `sliderEnable2` = '$sliderEnableCheck2', `sliderEnable3` = '$sliderEnableCheck3', `sliderEnable4` = '$sliderEnableCheck4', `sliderImg1` = '$sliderImg1', `sliderImg2` = '$sliderImg2', `sliderImg3` = '$sliderImg3', `sliderImg4` = '$sliderImg4', `sliderLink1` = '$sliderLink1', `sliderLink2` = '$sliderLink2', `sliderLink3` = '$sliderLink3', `sliderLink4` = '$sliderLink4' WHERE `settings`.`licenseID` = '$licenseID';"), 0 );

	}    

	# RÓŻNE
	if(isset($_POST["settings"])) {
		$currencyName = $_POST['currencyName'];
		$pageTitle = $_POST['pageTitle'];
		if (!empty($_POST['currencyName']) && !empty($_POST['pageTitle'])) {
			
			if (isset($_POST['payEnable'])){
				$payEnable = "true";
			} else{
				$payEnable = "false";
			}
			if (isset($_POST['maintenance'])){
				$maintenance = "true";
			} else{
				$maintenance = "false";
			}
				if($_FILES['fileToUpload']['error'] > 0) { 
				$uploadOk = 1;
				$linkIMG = "default.png";
			} else {
				$rand = rand(1000,100000);
				$target_dir = "../uploads/logos/";
				$target_file = $target_dir . $rand.basename($_FILES["fileToUpload"]["name"]);
				$uploadOk = 1;
				$linkIMG = $rand.basename($_FILES["fileToUpload"]["name"]);
				$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
				 $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
				 if($check !== false) {
					  $uploadOk = 1;
				 } else {
					  $blad2 = ''.$blad2.' Załadowane logo nie jest zdjęciem...<br>';
					  $uploadOk = 0;
				 }
				if ($_FILES["fileToUpload"]["size"] > 5120000) {
					 $blad2 = ''.$blad2.' Przesłane logo jest za duże! <br>';
					 $uploadOk = 0;
				}
				if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
					 $blad2 = ''.$blad2.' Niepoprawny typ przesłanego logo! <br>';
					 $uploadOk = 0;
				}
				if ($uploadOk == 1) {
					 move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
					 $deleteIMG = mysql_result( mysql_query("SELECT logoIMG FROM `settings` WHERE `licenseID` = '$licenseID'"), 0 );
					 if($deleteIMG != "default.png"){
						unlink("../uploads/logos/".$deleteIMG."");
					 }
				} else {
					$blad2 = '<div class="alert alert-warning"> <span>'.$blad2.'</span></div>';
				}
			}
			
			
			if ($uploadOk == 1) {
				$blad2 = '<div class="alert alert-success"> <span>Nowe ustawienia zostały zapisane pomyślnie!</span></div>';
				mysql_result(mysql_query("UPDATE `shop`.`settings` SET `currencyName` = '$currencyName', `maintenance` = '$maintenance', `logoIMG` = '$linkIMG', `pageTitle` = '$pageTitle', `payEnable` = '$payEnable' WHERE `settings`.`licenseID` = '$licenseID';"), 0 );
			}
					
	 } else {
			$blad2 = '<div class="alert alert-warning"> <span>Halo! Prosimy uzupełnić wpisane dane.</span></div>';
	 }
	}  

	# HASŁO
	if(isset($_POST["password"])) {
		$oldPassword = $_POST['oldPassword'];
		$newPassword = $_POST['newPassword'];
		$newPassword2 = $_POST['newPassword2'];
		if (!empty($_POST['oldPassword']) && !empty($_POST['newPassword']) && !empty($_POST['newPassword2'])) {
			

			$stored_secret = mysql_result(mysql_query("SELECT pass FROM `admins` WHERE `nick` LIKE '$user'"), 0 );
			if (password_verify($oldPassword, $stored_secret)) {
				if($newPassword != $newPassword2){
					$blad3 = '<div class="alert alert-warning"> <span>Podane nowe hasła nie zgadzają się!</span></div>';
				} else {
					$blad3 = '<div class="alert alert-success"> <span>Hasło zostało zmienione pomyślnie!</span></div>';
					$hashPassword = password_hash($newPassword, PASSWORD_DEFAULT);
					mysql_result(mysql_query("UPDATE `admins` SET `pass` = '$hashPassword' WHERE `nick` LIKE '$user'"), 0 );
				}
			} else {
				$blad3 = '<div class="alert alert-warning"> <span>Aktualne hasło jest niepoprawne!</span></div>';
			}
	
		 } else {
			$blad3 = '<div class="alert alert-warning"> <span>Halo! Prosimy uzupełnić wpisane dane.</span></div>';
		 }
	}  

	# INDEX PAGE
	if(isset($_POST["indexPageForm"])) {
		$indexPage = $_POST['indexPage'];
		if (!empty($_POST['indexPage'])) {
			
			$blad4 = '<div class="alert alert-success"> <span>Wygląd strony głównej został pomyślnie zapisany!</span></div>';
			mysql_result(mysql_query("UPDATE `shop`.`settings` SET `indexPage` = '$indexPage' WHERE `licenseID` LIKE '$licenseID'"), 0 );
	
		 } else {
			$blad4 = '<div class="alert alert-warning"> <span>Halo! Prosimy uzupełnić wpisane dane.</span></div>';
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
     <script src="editor/ckeditor.js"></script>

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
                <li class="active">
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
                    <div class="col-lg-7 col-md-12">
                        <div class="card">
                            <div class="header col-md-14">
                                <h4 class="title">Linki w menu</h4>
                                <p class="category">Edytuj górną belkę w menu. Możesz dodać własne linki.<br>Lista ikonek do menu znajduje się tutaj <a href="http://fontawesome.io/icons/"> http://fontawesome.io/icons/ </a></p>
                            </div>
							
						
                            <div style="padding: 5px 15px 10px 15px;" class="content">
						
						
								<div style="padding-left:10px;" class="row">
									<div class="header col-md-14">
										<?php echo $blad1; ?>
									</div>
								</div>

							
								<div class="content">
								<form method="post" enctype="multipart/form-data">
									
								
								<div class="row">
									<div class="col-md-14">
									
										
									<?php 
										for ($x = 1; $x <= 4; $x++) {
											echo '<div class="row">';
												echo '<div class="col-lg-1 col-md-14">';
													echo '<div class="form-group">';
														echo '<label> </label>';
														$menuEnable = mysql_result( mysql_query("SELECT menuEnable$x FROM `settings` WHERE `licenseID` = '$licenseID'"), 0 );
															if ($menuEnable == "true"){
																echo '<input type="checkbox" name="menuEnable'.$x.'" class="form-control border-input" checked>';
															} else {
																echo '<input type="checkbox" name="menuEnable'.$x.'" class="form-control border-input">';
															}
													echo '</div>';
												echo '</div>';
												echo '<div class="col-lg-4 col-md-14">';
													echo '<div class="form-group">';
														echo '<label>Wyświetlana nazwa</label>';
														$MenuName = mysql_result( mysql_query("SELECT MenuName$x FROM `settings` WHERE `licenseID` = '$licenseID'"), 0 );
													   echo '<input maxlength="50" type="text" name="menuName'.$x.'" class="form-control border-input" value="'.$MenuName.'" placeholder="Nazwa Serwera" required>';
													echo '</div>';
												echo '</div>';
												echo '<div class="col-lg-4 col-md-6">';
													echo '<div class="form-group">';
														echo '<label>Link po kliknięciu</label>';
														$MenuLink = mysql_result( mysql_query("SELECT MenuLink$x FROM `settings` WHERE `licenseID` = '$licenseID'"), 0 );
													   echo '<input maxlength="50" type="text" name="menuLink'.$x.'" class="form-control border-input" value="'.$MenuLink.'" placeholder="http://www.mojserwer.pl" required>';
													echo '</div>';
												echo '</div>';
												echo '<div class="col-lg-2 col-md-6">';
													echo '<div class="form-group">';
														echo '<label>Ikonka</label>';
														$MenuIcon = mysql_result( mysql_query("SELECT menuIcon$x FROM `settings` WHERE `licenseID` = '$licenseID'"), 0 );
													   echo '<input maxlength="29" type="text" name="menuIcon'.$x.'" class="form-control border-input" value="'.$MenuIcon.'" placeholder="fa-home" required>';
													echo '</div>';
												echo '</div>';
												echo '<div class="col-lg-1 col-md-6">';
													echo '<div class="form-group">';
														echo '<label> </label>';
														echo '<i class="fa '.$MenuIcon.'" style="padding-top:25px; padding-left:5px;font-size:40px;color:orange;" aria-hidden="true"></i>';
													echo '</div>';
												echo '</div>';
											echo '</div>';
										} ?>
									
									
											
											
										</div>
									</div>
		
                                    <div class="text-center"><br>
                                        <button name="menu" type="submit" class="btn btn-info btn-fill btn-wd">Zapisz ustawienia!</button>
                                    </div>
								</form>
                                    <div class="clearfix"></div>
									

                        </div>
                    </div>                  
				 </div>
				</div>
					
					 <div class="col-lg-5 col-md-12">
                        <div class="card">
                            <div class="header col-md-14">
                                <h4 class="title">Różne ustawenia</h4>
                                <p class="category">Zarządzaj różnymi ustaweniami Twojego sklepu</p>
                            </div>
							
						
                            <div style="padding: 0px 15px 10px 15px;" class="content">
						
					
							<div class="row">
								<div class="header col-md-14">
									<?php echo $blad2; ?>
								</div>
							</div>
								
							<form method="post" enctype="multipart/form-data">
								<div class="content">
								
                                    <div class="row">
                                        <div class="col-md-14">
											<div class="row">
												<div style="margin-top:5px" class="col-lg-12 col-md-12">
													<div class="form-group">
														<label>Nazwa waluty sklepu, która będzie<br> wykorzystywana do kupowania ofert.</label>
													   <input value="<?php echo mysql_result( mysql_query("SELECT currencyName FROM `settings` WHERE `licenseID` = '$licenseID'"), 0 ); ?>" maxlength="20" type="text" name="currencyName" class="form-control border-input" required>
													</div>
												</div>
											</div>
											<div class="row">
												<div style="margin-top:5px" class="col-lg-12 col-md-12">
													<div class="form-group">
														<label>Tytuł strony sklepu</label>
													   <input value="<?php echo mysql_result( mysql_query("SELECT pageTitle FROM `settings` WHERE `licenseID` = '$licenseID'"), 0 ); ?>" maxlength="40" type="text" name="pageTitle" class="form-control border-input" required>
													</div>
												</div>
											</div>
											<div class="row">
												<div style="margin-top:5px" class="col-lg-6 col-md-12">
													<div class="form-group">
														<?php $payEnable = mysql_result( mysql_query("SELECT payEnable FROM `settings` WHERE `licenseID` = '$licenseID'"), 0 );
															if ($payEnable == "true"){
																echo '<label><input type="checkbox" name="payEnable" class="border-input" checked> Czy włączyć możliwość przesyłania waluty między użytkownikami sklepu?</label>';
															} else {
																echo '<label><input type="checkbox" name="payEnable" class="border-input"> Czy włączyć możliwość przesyłania waluty między użytkownikami sklepu?</label>';
															}
														?>
													</div>
												</div>
												<div style="margin-top:5px" class="col-lg-6 col-md-12">
													<div class="form-group">
														<?php $maintenance = mysql_result( mysql_query("SELECT maintenance FROM `settings` WHERE `licenseID` = '$licenseID'"), 0 );
															if ($maintenance == "true"){
																echo '<label><input type="checkbox" name="maintenance" class="border-input" checked> Czy włączyć przerwę techniczną Twojego sklepu?</label>';
															} else {
																echo '<label><input type="checkbox" name="maintenance" class="border-input"> Czy włączyć przerwę techniczną Twojego sklepu?</label>';
															}
														?>
													</div>
												</div>
											</div>
											<div class="row">
												<div style="margin-top:10px" class="col-lg-7 col-md-12">
													<div class="form-group">
														<label>Załaduj nowe logo sklepu</label>
														<input class="form-group border-input" type="file" name="fileToUpload" id="fileToUpload">
													</div>
												</div>
												<div style="margin-top:-5px" class="col-lg-5 col-md-12">
													<div class="form-group">
														<label>Aktualne logo sklepu</label>
														<?php $logoIMG = mysql_result( mysql_query("SELECT logoIMG FROM `settings` WHERE `licenseID` = '$licenseID'"), 0 );
															if ($logoIMG == "" || $logoIMG == "default.png"){
																echo '<img width="173" height="55" src="../uploads/logos/default.png" />';
															} else {
																echo '<img width="173" height="55" src="../uploads/logos/'.$logoIMG.'" />';
															}
														?>
														
													</div>
												</div>
											</div>
											
										</div>
									</div>
		
		
                                   
								   
								   
                                    <div class="text-center">
                                        <button name="settings" type="submit" class="btn btn-info btn-fill btn-wd">Zapisz ustawenia!</button>
                                    </div>
                                    <div class="clearfix"></div>
							</div>
						</form>
						</div>
						</div>

					   
					</div>
					
				
					
					
				</div>
			   
			   
			   
				<div class="row">
					
                    <div class="col-lg-8 col-md-12">
                        <div class="card">
                            <div class="header col-md-14">
                                <h4 class="title">Obrazki na stronie głównej</h4>
                                <p class="category">Możesz edytować tutaj obrazki, które wyświetlane będą na stronie głównej. <br>
								Jeśli dodasz więcej niż jeden obrazek to zostanie włączona funkcja automatycznego przesówania się obrazka.</p>
                            </div>
							
						
                            <div style="padding: 5px 15px 10px 15px;" class="content">
						
						
								<div style="padding-left:10px;" class="row">
									<div class="header col-md-14">
										<?php echo $bladSlider; ?>
									</div>
								</div>

							
								<div class="content">
								<form method="post" enctype="multipart/form-data">
									
								
								<div class="row">
									<div class="col-md-14">
									
										
									<?php 
										for ($x = 1; $x <= 4; $x++) {
											echo '<div class="row">';
												echo '<div class="col-lg-1 col-md-14">';
													echo '<div class="form-group">';
														echo '<label> </label>';
														$sliderEnable = mysql_result( mysql_query("SELECT sliderEnable$x FROM `settings` WHERE `licenseID` = '$licenseID'"), 0 );
															if ($sliderEnable == "true"){
																echo '<input type="checkbox" name="sliderEnable'.$x.'" class="form-control border-input" checked>';
															} else {
																echo '<input type="checkbox" name="sliderEnable'.$x.'" class="form-control border-input">';
															}
													echo '</div>';
												echo '</div>';
												echo '<div class="col-lg-4 col-md-6">';
													echo '<div class="form-group">';
														echo '<label>Link po kliknięciu w obrazek</label>';
														$sliderLink = mysql_result( mysql_query("SELECT sliderLink$x FROM `settings` WHERE `licenseID` = '$licenseID'"), 0 );
													   echo '<input maxlength="50" type="text" name="sliderLink'.$x.'" class="form-control border-input" value="'.$sliderLink.'" placeholder="http://www.google.pl">';
													echo '</div>';
												echo '</div>';
												echo '<div style="margin-top:10px" class="col-lg-3 col-md-6">
													<div class="form-group">
														<label>Załaduj nowy obrazek</label>
														<input class="form-group border-input" type="file" name="fileToUpload" id="fileToUpload">
													</div>
												</div>';
												echo '<div class="col-lg-2 col-md-6">';
													$sliderImg = mysql_result( mysql_query("SELECT sliderImg$x FROM `settings` WHERE `licenseID` = '$licenseID'"), 0 );
													if($sliderImg != ""){
														echo '<img style="width:200px;" src="../uploads/slider/'.$sliderImg.'" />';
													}
												echo '</div>';
											echo '</div>';
											
											
																									

										} ?>
									
									
											
											
										</div>
									</div>
		
                                    <div class="text-center"><br>
                                        <button name="slider" type="submit" class="btn btn-info btn-fill btn-wd">Zapisz ustawienia!</button>
                                    </div>
								</form>
                                    <div class="clearfix"></div>
									

                        </div>
                    </div>                  
				 </div>
				</div>
					
                    <div class="col-lg-4 col-md-12">
                        <div class="card">
                            <div class="header col-md-14">
                                <h4 class="title">Zmień hasło</h4>
                                <p class="category">Ustaw nowe hasło do panelu administratora tego sklepu.</p>
                            </div>
							
						
							<a style="position:relative; bottom:30px;" name="here">&nbsp;</a>
                            <div style="padding: 5px 15px 10px 15px;" class="content">
						
								<div class="row">
									<div class="header col-md-10">
										<?php echo $blad3; ?>
									</div>
								</div>
						
							<form method="post" action="settings.php#here" enctype="multipart/form-data">
								<div class="content">
									
									
                                    <div class="row">
                                        <div class="col-md-14">
											<div class="row">
												<div style="margin-top:5px" class="col-lg-12 col-md-12">
													<div class="form-group">
														<label>Aktualne hasło</label>
													   <input maxlength="20" type="password" name="oldPassword" class="form-control border-input" required>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-lg-12 col-md-12">
													<div class="form-group">
														<label>Nowe hasło</label>
													   <input maxlength="20" type="password" name="newPassword" class="form-control border-input" required>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-lg-12 col-md-12">
													<div class="form-group">
														<label>Powtóz nowe hasło</label>
													   <input maxlength="20" type="password" name="newPassword2" class="form-control border-input" required>
													</div>
												</div>
											</div>
											
										</div>
									</div>
		
								   
                                    <div class="text-center">
                                        <button name="password" type="submit" class="btn btn-info btn-fill btn-wd">Zmień hasło!</button>
                                    </div>
                                    <div class="clearfix"></div>
							</div>
						</form>
						</div>
						</div>

					   
					</div>
					
					
                    <div class="col-lg-12 col-md-12">
                        <div class="card">
                            <div class="header col-md-14">
                                <h4 class="title">Zmień zawartość strony głównej</h4>
                                <p class="category">Zmień wygląd strony głównej Twojego sklepu.</p>
                            </div>
							
						
							<a style="position:relative; bottom:10px;" name="here2">&nbsp;</a>
                            <div style="padding: 0px 15px 10px 15px;" class="content">
						
								<div class="row">
									<div class="header col-md-10">
										<?php echo $blad4; ?>
									</div>
								</div>
						
							<form method="post" action="settings.php#here" enctype="multipart/form-data">
								<div class="content">
									
									
                                    <div class="row">
                                        <div class="col-md-14">
											<div class="row">
												<div class="col-md-14">
													<div class="form-group">
															<textarea name="indexPage" rows="7" class="form-control border-input" required><?php echo mysql_result( mysql_query("SELECT indexPage FROM `settings` WHERE `licenseID` = '$licenseID'"), 0 ); ?></textarea>                                 
														<script> CKEDITOR.replace( 'indexPage' ); </script>
													 </div>
												</div>
											</div>
											
										</div>
									</div>
		
								   
                                    <div class="text-center">
                                        <button name="indexPageForm" type="submit" class="btn btn-info btn-fill btn-wd">Zapisz stronę!</button>
                                    </div>
                                    <div class="clearfix"></div>
							</div>
						</form>
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
