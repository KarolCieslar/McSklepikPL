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

	$name = mysql_real_escape_string($_POST['name']);
	$serverTemp = explode("|.|", $_POST['server']);
	$server = $serverTemp[0];
	$serverUID = $serverTemp[1];
	$img = $_POST['img'];
	$categoryTemp = explode("|.|", $_POST['category']);
	$category = $categoryTemp[0];
	$categoryUID = $categoryTemp[1];
	$desc = mysql_real_escape_string($_POST['desc']);
	$price = $_POST['price'];
	$cmds = implode("|.|", (array)$_REQUEST['commands']);

	if (!empty($_POST)){
		if (!empty($_POST['name']) && !empty($_POST['server']) && !empty($_POST['desc']) && !empty($_POST['price'])) {
			
				if($_FILES['fileToUpload']['error'] > 0) { 
					$uploadOk = 1;
					$linkIMG = "default.png";
				} else {
					$rand = rand(1000,100000);
					$target_dir = "../uploads/offerts/";
					$target_file = $target_dir . $rand.basename($_FILES["fileToUpload"]["name"]);
					$uploadOk = 1;
					$linkIMG = $rand.basename($_FILES["fileToUpload"]["name"]);
					$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
					 $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
					 if($check !== false) {
						  $uploadOk = 1;
					 } else {
						  $blad = ''.$blad.' Podany plik nie jest zdjęciem! <br>';
						  $uploadOk = 0;
					 }
					if ($_FILES["fileToUpload"]["size"] > 5120000) {
						 $blad = ''.$blad.' Zdjęcie jest zbyt duże! <br>';
						 $uploadOk = 0;
					}
					if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
						 $blad = ''.$blad.' Niepoprawny typ pliku! <br>';
						 $uploadOk = 0;
					}
					if ($uploadOk == 1) {
						 move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
					
					} else {
						$blad = '<div class="alert alert-warning"> <span>'.$blad.'</span></div>';
					}
				}
				
				
				if ($uploadOk == 1) {
					
					
					$blad = '<div class="alert alert-success"> <span>Nowy oferta został dodana pomyślnie! Przejdź do zakładki USŁUGI aby nią zarządzać!</span></div>';
					mysql_result(mysql_query("INSERT INTO `shop`.`products` (`uid`, `name`, `server`, `commands`, `img`, `price`, `category`, `description`, `serverUID`, `licenseID`) VALUES (NULL, '$name', '$server', '$cmds', '$linkIMG', '$price', '$categoryUID', '$desc', '$serverUID', '$licenseID');"), 0 );
					mysql_result(mysql_query("INSERT INTO `shop`.`logs` (`uid`, `date`, `info`, `type`, `licenseID`) VALUES  (NULL, CURRENT_TIMESTAMP, 'Dodano nową ofertę <b>$name</b> na serverze <b>$server</b>', 'addoffer', '$licenseID');"), 0 );
					
					
					
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
                    
                    <div class="col-md-14">
                        <div class="card">
                            <div style="padding-bottom:10px;" class="header col-md-10">
                                <h4 class="title">Dodaj Usługę</h4>
                                <p class="category">Uzupełnij pola poniżej aby dodać nową usługę</p>
                            </div>
                            <div class="header col-md-9">
								<?php echo $blad; ?>
                            </div>
                            <div class="content">
						
								 <script>
									function showfield(name){
										
										alert(name);
									  // if(name=='single')
										  // document.getElementById('price').innerHTML='<label><span>Czas licencji</span><select name="time" class="form-control border-input" required><option value="">-- nie wybrano --</option><option value="1">1 miesiąc (10 PLN)</option><option value="2">2 miesiące (19 PLN)</option><option value="3">5 miesiący (45 PLN)</option><option value="4">12 miesiący (100 PLN)</option></select></label>';
									  // else if(name=='multi') 
										  // document.getElementById('price').innerHTML='<label><span>Czas licencji</span><select name="time" class="form-control border-input" required><option value="">-- nie wybrano --</option><option value="1">1 miesiąc (24 PLN)</option><option value="2">2 miesiące (40 PLN)</option><option value="3">5 miesiący (99 PLN)</option><option value="4">12 miesiący (260 PLN)</option></select></label>';
									 // else if(name=='none') 
										  // document.getElementById('price').innerHTML='';
									// }

								</script>
								
                                <form method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Nazwa produktu</label>
                                                <input maxlength="50" name="name" type="text" placeholder="Podaj nazwę produktu!" class="form-control border-input" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
											<?php $query="SELECT * FROM `servers` WHERE `licenseID` LIKE '$licenseID'";
											$result = mysql_query($query)
											or die(mysqli_error($result)); ?>
											
                                            <label>Wybierz server</label>
							                <select onchange="showfield(this.options[this.selectedIndex].value)" name="server" class="form-control border-input" required>
							            	 <option value="">-- nie wybrano --</option>
											<?php
												while ($row = mysql_fetch_array($result)){
													echo '<option value="'.$row['name'].'|.|'.$row['uid'].'">'.$row['name'].'</option>';
												}
											?>
											
											
											</select> 
							            </div>
                                        <div class="col-md-3">
											<?php $query="SELECT * FROM `categories` WHERE `licenseID` LIKE '$licenseID'";
											$result = mysql_query($query)
											or die(mysqli_error($result)); ?>
											
                                            <label>Wybierz Kategorię</label>
							                <select name="category" class="form-control border-input">
							            	 <option value="">-- brak kategorii --</option>
											<?php
												while ($row = mysql_fetch_array($result)){
													echo '<option value="'.$row['name'].'|.|'.$row['uid'].'">'.$row['name'].'</option>';
												}
											?>
											
											
											</select> 
							            </div>
                                        <div class="col-md-3">
                                            <div class="form-group border-input">
                                                <label>Wybierz zdjęcie główne</label>
																
													<input class="form-group border-input" type="file" name="fileToUpload" id="fileToUpload">
																																	
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-14">
                                            <div class="form-group">
                                                <label>Opis produktu</label>
																<textarea name="desc" rows="7" class="form-control border-input" required></textarea>                                 
															<script> CKEDITOR.replace( 'desc' ); </script>
														 </div>
                                        </div>
                                    </div>
									
                                    <div class="row">
                                        <div class="col-md-7">
											<label style="margin-top:2px">Dodaj komendy usług<br>Zmiena <b>%nick%</b> = nick gracza</label>
											
											<div class="text-right header col-md-7 pull-right">
												 <div class="input-group-btn"> 
													<button class="btn btn-success add-more" type="button">DODAJ KOLEJNĄ KOMENDĘ</button>
												  </div>
											   </div>
											   
												<div class="control-group after-add-more">
												   <input maxlength="50" type="text" name="commands[]" class="form-control border-input" placeholder="/give %nick% 278 1" required>
													 
												</div>
											

											<div class="copy-fields hide">
											  <div class="control-group input-group" style="margin-top:10px">
												<input maxlength="50" type="text" name="commands[]" class="form-control border-input" placeholder="Wpisz komendę tutaj">
												<div class="input-group-btn"> 
												  <button class="btn btn-danger remove" type="button">USUŃ</button>
												</div>
											  </div>
											</div>
										</div>
                                        <div style="margin-top:26px" class="col-md-5">
                                            <div class="form-group">
                                                <label>Ustal cenę (Ile waluy z portfela zostanie pobrane po zakupie)</label>
                                                <input maxlength="10" name="price" min="1" type="number" placeholder="Podaj cene produktu!" class="form-control border-input" required>
                                            </div>
                                        </div>
									</div>
		
		
                                   
                                    <div class="text-center">
                                        <button name="submit" type="submit" class="btn btn-info btn-fill btn-wd">Dodaj ofertę!</button>
                                    </div>
                                    <div class="clearfix"></div>
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

		$(document).ready(function() {

		//here first get the contents of the div with name class copy-fields and add it to after "after-add-more" div class.
		  $(".add-more").click(function(){ 
			  var html = $(".copy-fields").html();
			  $(".after-add-more").after(html);
		  });
	//here it will remove the current value of the remove button which has been pressed
		  $("body").on("click",".remove",function(){ 
			  $(this).parents(".control-group").remove();
		  });

		});

	</script>


</html>
