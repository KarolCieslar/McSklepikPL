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
	 if (isset($_GET['delcat'])) {
		$categoryUID = $_GET['delcat'];
		 
		# SPRAWDZANIE LICENCJI
		$query="SELECT * FROM `categories` WHERE `licenseID` LIKE '$licenseID'";
		$result = mysql_query($query)
		or die(mysqli_error($result));
		$check = false;
		while ($row = mysql_fetch_array($result)){
			if ($categoryUID == $row['uid']){
				$check = true;
			}
		}
		if ($check == false){
			header('Location: categories.php');
			break;
		}
		 
		$deleteIMG = mysql_result( mysql_query("SELECT img FROM `categories` WHERE `uid` = $categoryUID"), 0 );
		unlink("../uploads/categories/".$deleteIMG."");
		 
		$name = mysql_result( mysql_query("SELECT name FROM `categories` WHERE `uid` = $categoryUID"), 0 );
		mysql_result( mysql_query("DELETE FROM `categories` WHERE `uid` = $categoryUID"), 0 );
		mysql_result(mysql_query("INSERT INTO `shop`.`logs` (`uid`, `date`, `info`, `type`, `licenseID`) VALUES  (NULL, CURRENT_TIMESTAMP, 'Usunięto kategorie o nazwie <b>$name</b>', 'deletecategory', '$licenseID');"), 0 );
		header('Location: categories.php');
	  }
	  
	  
	  
	# DODAWANIE KATEGORII
	if (!empty($_POST)){
		if (!empty($_POST['categoryName'])) {
			$category = mysql_real_escape_string($_POST['categoryName']);

			$query1="SELECT * FROM `categories` WHERE `name` LIKE '$category' AND `licenseID` LIKE '$licenseID'";
			$result1 = mysql_query($query1)
			or die(mysqli_error($result1));
				
			$num_rows = mysql_num_rows($result1);
			if ($num_rows == 1){
				$blad = '<div class="alert alert-warning"> <span>Istnieje już kategoria z taką nazwą!</span></div>';
			} else {

				if($_FILES['fileToUpload']['error'] > 0) { 
					$uploadOk = 1;
					$linkIMG = "default.png";
				} else {
					$rand = rand(1000,100000);
					$target_dir = "../uploads/categories/";
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
						 $deleteIMG = mysql_result( mysql_query("SELECT img FROM `categories` WHERE `licenseID` = '$licenseID'"), 0 );
						 if($deleteIMG != "default.png"){
							unlink("../uploads/categories/".$deleteIMG."");
						 }
					} else {
						$blad = '<div class="alert alert-warning"> <span>'.$blad.'</span></div>';
					}
				}
				
				
				if ($uploadOk == 1) {
					# DODAWANIE DO BAZY DANYCH
					$blad = '<div class="alert alert-success"> <span>Nowy kategoria został dodana pomyślnie!</span></div>';
					mysql_result(mysql_query("INSERT INTO `shop`.`logs` (`uid`, `date`, `info`, `type`, `licenseID`) VALUES  (NULL, CURRENT_TIMESTAMP, 'Dodano nową kategorię <b>$category</b>.', 'addcategory', '$licenseID');"), 0 );
					mysql_result(mysql_query("INSERT INTO `shop`.`categories` (`uid`, `name`, `img`, `licenseID`) VALUES (NULL, '$category', '$linkIMG', '$licenseID');"), 0 );
				}
			}
					
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
    <link href="assets/css/paper-dashboard.css?123" rel="stylesheet"/>



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
                <li class="active">
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
                    <div class="col-lg-8 col-md-8">
                        <div class="card">
                            <div class="header col-md-8">
                                <h4 class="title">Lista kategorii sklepu</h4>
                                <p class="category">Tutaj można edytować lub usuwać kategorie.</p>
                            </div>
							
						
                            <div style="padding: 50px 15px 10px 15px;" class="content table-responsive table-full-width">
						
						

							
										<?php 
										$query="SELECT * FROM `categories` WHERE `licenseID` LIKE '$licenseID'";
										$result = mysql_query($query)
										or die(mysqli_error($result));
										
										$num_rows = mysql_num_rows($result);
										if ($num_rows == 0){
											echo '
											<div style="padding: 0px 37px 18px 34px;">
												<h3><br> Niestety, brak jakichkolwiek kategorii dodanych do tego sklepu!<h3>
											</div>
											';
										}
										else {
											
										
											 echo '<table class="table table-striped">';
												echo '<thead>';
													 echo '<th width="5%" ><b>Obrazek</b></th>';
													echo '<th width="25%" ><b>Nazwa</b></th>';
													echo '<th width="60%" ><b>Produkty</b></th>';
													echo '<th style="text-align: right;"><b>Akcja</b></th>';
												echo '</thead>';
												echo '<tbody>';
										
											while ($row = mysql_fetch_array($result)){
												echo '<tr>';
													echo '<td><img width="60" height="60" src="../uploads/categories/'.$row['img'].'" /></td>';
													echo '<td>'.$row['name'].'</td>';
													
													$categoryUIDTemp = $row['uid'];
													$queryProducts="SELECT * FROM `products` WHERE `category` LIKE '$categoryUIDTemp'";
													$resultProducts = mysql_query($queryProducts)
													or die(mysqli_error($resultProducts));
													
													$num_rows_Products = mysql_num_rows($resultProducts);
													if ($num_rows_Products == 0){
														echo '<td>Brak ofert dodanych do tej kategorii</td>';
													} else {
														echo '<td>';
														while ($rowProducts = mysql_fetch_array($resultProducts)){
															echo $rowProducts['name'];
															echo ', ';
														}
														echo '</td>';
													}
												
													echo '<td style="text-align: right;">'; ?>
																													
													<a onclick="return confirm('Czy na pewno chcesz usunąć tą kategorię?')" href="?delcat=<?php echo $row['uid']; ?>"><btn class="btn btn-lg btn-danger btn-fill btn-icon">USUN</btn></a>
										
										<?php		
												 echo '</tr>';
										
											}
											
										
												echo '</tbody>';
										  echo '</table> <hr><br>';
										}
								
										  
										?>
                                        
													
													 

                            </div>

                   
					</div>
				</div>
                <form method="post" enctype="multipart/form-data">
				 <div class="col-lg-4 col-md-4">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Dodaj nową kategorię</h4>
                                <p class="category">Dodaj kategorię do sklepu. Pamiętaj, że każdy server ma te same kategorie.</p>
                            </div>
                            <div class="header col-md-9">
								<?php echo $blad; ?>
                            </div>
                            <div class="content">
                                <br>
									
								
								<div class="row">
									<div class="col-lg-12 col-md-10">
										<div class="form-group">
											<label>Nazwa kategorii</label>
											<input maxlength="50" name="categoryName" type="text" placeholder="Podaj nazwę produktu!" class="form-control border-input" required>
										</div>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-lg-12 col-md-10">
									
										<div class="form-group border-input">
											<label>Załaduj ikonkę, która będzie wyświetlana na stronie</label>
												<input class="form-group border-input" type="file" name="fileToUpload" id="fileToUpload">
																																
										</div>
									</div>
								</div>
								<div class="row">
									 <div class="text-center">
										<br>
                                        <button name="submit" type="submit" class="btn btn-info btn-fill btn-wd">Dodaj kategorię!</button>
                                    </div>
								</div>
								
                            </div>
                        </div>
                    </div>
				</form>
				
				
				
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
	  
	  function ConfirmForm() {
    return confirm("Are you sure you want to submit?");
}
	  
    </script>
	
	<script>
		$(document).ready(function(){
			$('[data-toggle="tooltip"]').tooltip();   
		});
	</script>

</html>
