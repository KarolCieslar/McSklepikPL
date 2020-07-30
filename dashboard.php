<?php 
	session_start(); if (!isset($_SESSION['logged'])) { header('Location: index.php'); exit(); }
	$user = $_SESSION['user'];
	include 'config.php';
	
	if(isset($_POST["edit"])) {
		$shop = $_POST['shopname'];
		$licenseID = mysql_result(mysql_query("SELECT licenseID FROM `shops` WHERE `nick` LIKE '$user' AND `name` LIKE '$shop'"), 0 );
		$_SESSION['licenseID'] = $licenseID;
	}
	
	# SPRAWDZANIE CZY MA JAKĄKOLWIEK LICENCJĘ
	if(isset($_SESSION['licenseID'])){
		$licenseID = $_SESSION['licenseID'];
	}
	$shopName = mysql_result( mysql_query("SELECT name FROM `shops` WHERE `licenseID` = '$licenseID'"), 0 );
	$coins = mysql_result( mysql_query("SELECT coins FROM `admins` WHERE `nick` = '$user'"), 0 );
	
	
	
	
	# --------- #
	#  PRZELEW  #
	# --------- #
				
	if(isset($_POST["przelew"])) {	
		$coins = $_POST['coins'];
		$key = '7812d793ff9969508495636075a45ba1'; // KLUCZ PRYWATNY, HOMEPAY > PANEL PARTNERA > PAYSAFECARD > USTAWIENIA
		$data = array(
			'uid' => 13302,
			'public_key' => '955286ee29752f474afaa58728f48bbc',
			'amount' => ( $coins * 100 ),
			'mode' => 0,
			'label' => 'Doładowanie '.$coins.'x coins do konta '.$user.'',
			'control' => ''.$coins.'+'.$user.'',
			'success_url' => urlencode('http://mcsklepik.pl/admin'),
			'failure_url' => urlencode('http://mcsklepik.pl/admin'),
			'notify_url' => urlencode('http://mcsklepik.pl/admin/HomePayAPI/przelew.php') 
		); 
		$data['crc'] = md5(join('', $data) . $key); 

		echo '<form method="post" name="przelew" action="https://homepay.pl/przelew/">'; 
		foreach ($data as $field => $value) {
			echo '<input type="hidden" name="' . $field . '" value="' . $value . '">';
		}
		echo '<img style="position: absolute;margin: auto;top: 0;left: 0;right: 0;bottom: 0;" src="http://www.minecraftpolska.net/img/gifload.gif"';
		echo '</form>
		<script>setTimeout(function() { document.przelew.submit(); }, 0);</script>';
	}
	
	# ------------- #
	#  PAYSAFECARD  #
	# ------------- #
	
	if(isset($_POST["paysafecard"])) {	
		$coins = $_POST['coins'];
		$label  = "".$user." ".$coins."";
		$key = '21ec83b19d5d2dc3cd22a0627363fe0a'; // KLUCZ PRYWATNY, HOMEPAY > PANEL PARTNERA > PAYSAFECARD > USTAWIENIA
		$data = array(
			'uid' => 11865, // IDENTYFIKATOR UŻYTKOWNIKA HOMEPAY
			'public_key' => 'cb2dc3bd20583adadb9fbcea2b17bb74', // KLUCZ PUBLICZNY, HOMEPAY > PANEL PARTNERA > PAYSAFECARD > USTAWIENIA
			'amount' => ($coins * 100), // KWOTA W GROSZACH
			'label' => ''.$label.'',
			'control' => 'Zakup'.$coins.'xcoinsownanick'.$user.'',
			'success_url' => urlencode('http://mcsklepik.pl/admin'),
			'failure_url' => urlencode('http://mcsklepik.pl/admin'),
			'notify_url' => urlencode('http://mcsklepik.pl/admin/HomePayAPI/psc.php') 
		);

		$data['crc'] = md5(join('', $data) . $key);

		echo '<form method="post" name="paysafecard" action="https://ssl.homepay.pl/paysafecard/">';
		foreach($data as $field => $value)
			echo '<input type="hidden" name="' . $field . '" value="' . $value . '">';

		echo '<img style="position: absolute;margin: auto;top: 0;left: 0;right: 0;bottom: 0;" src="http://www.minecraftpolska.net/img/gifload.gif"';
		echo '</form>
		<script>setTimeout(function() { document.paysafecard.submit(); }, 0);</script>';
	}
	
	
	
	# ----------------- #
	#  ZMIANA LICENCJI  #
	# ----------------- #
	if(isset($_POST["change"])) {
		$shopnamechangelicense = $_POST['shopname'];
		#dni * 0.46
		$day   = date('Y/m/d'); // data
		function roznica_data($data_poczatek, $date_koniec, $jednostka_czasu="sekund") 
		{ 
		 $tablica = array(minut=>60, godzin=>3600, dni=>86400, sekund=>1);
		 return "".round(((strtotime($date_koniec) - strtotime($data_poczatek)) / $tablica[$jednostka_czasu]))." ".$jednostka_czasu; 
		}
		$expireDate = mysql_result( mysql_query("SELECT expire FROM `shops` WHERE `name` = '$shopnamechangelicense'"), 0 );
		$licenseDays = intval(roznica_data($expireDate, $day, "dni"));
		$dni = str_replace("-","",$licenseDays);
		$koszt = round($dni * 0.46, 0, PHP_ROUND_HALF_UP);
		
		$blad = '
           <div class="row">
			<div class="col-lg-8 col-md-14">
				<div style="background-color: moccasin;" class="card">
					<div class="header col-md-7">
						<h4 class="title">Jesteś pewny, że chcesz zmienić licencję dla sklepu <b>'.$shopname.'</b>?</img></h4>
						<p class="category">Zanim zmienimy Twoją licencję potrzebujemy potwierdzenia.</p><hr />
					</div>
					<div style="padding: 50px 15px 10px 15px;" class="content">
					</div>
					 <div class="content">
						<br><br>
						<font size="4">Licencja <b>MULTI</b> umożliwia Ci zarządzanie nielimitowaną ilością serwerów w Twoim sklepie.<br>
						Możesz dodać nielimitowaną ilość ofert oraz kategorii, masz całkowity dostęp do wszystkich funkcji panelu admina.<br>
						Pamiętaj, że po zmianie licencji nie ma możliwości powrotu do poprzedniej licencji, zastanów się więc nad swoim zakupem!</font>
						
						<font size="5">Koszt zmiany licencji dla tego sklepu wynosi '.$koszt.'x coins!<br></font>
						<br><br>
						<form style="margin-left:260px; display: inline; text-align: right; padding-bottom:4px;" method="post"> <input type="hidden" name="confirmchangelicensevalue" value="'.$koszt.'"><input type="hidden" name="shopnamechangelicense" value="'.$shopnamechangelicense.'">
						<button name="confirmchangelicense" type="submit" class="btn btn-md btn-success btn-fill btn-icon"><i class="fa fa-cogs" aria-hidden="true"></i>Zgadzam się na zmianę licencji!</button></form>
						<a style="margin-left:50px;" href="dashboard.php"><btn class="btn btn-md btn-danger btn-fill btn-icon">REZYGNUJĘ</btn></a>
								
						
					</div>
				</div>
				</div>
			</div> ';
	}
	
	# POTWIERDZENEI ZMIANY
	if(isset($_POST["confirmchangelicense"])) {
		$koszt = $_POST['confirmchangelicensevalue'];
		$shopnamechangelicense = $_POST['shopnamechangelicense'];
		if($coins >= $koszt){
			
			# ZABIERANIE COINSÓW i DAWANIE LICKI
			$coinsNickSQL = $coins - $koszt;
			#$updateNewCoins = mysql_result(mysql_query("UPDATE `admins` SET `coins` = '$coinsNickSQL' WHERE `nick` LIKE '$user'"), 0 );
			$changeShopLicense = mysql_result(mysql_query("UPDATE `shops` SET `license` = 'multi' WHERE `name` LIKE '$shopnamechangelicense'"), 0 );
			
			$blad = '<div class="alert alert-success"> <span>Licencja Twojego sklepu została pomyślnie zmieniona!</span></div>';

		} else {
			$blad = '<div class="alert alert-warning"> <span>Nie posiadasz wystarczającej ilości coinsów! Doładuj je za pomocą SMS, PAYSAFECARD lub PRZELEWU.</span></div>';
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

			<?php if(!isset($_SESSION['licenseID'])){
				echo '<br><br><center><font color="white">
					Nie wybrałeś sklepu<br>
					którym chcesz zarządzać.<br><br>
					Aby to zrobić kliknij
					przycisk <br>"ZARZĄDZAJ SKLEPEM" z<br>
					tabelki po prawej stronie.<br>
					Jeśli nie posiadasz tam żadnych<br> licencji musisz najpierw wykupić nową klikając w przycisk "ZAKUP NOWĄ LICENCJĘ".<br><br>
					<br></font></center>';
				echo '				
				<ul class="nav">
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
			<?php } ?>
    	</div>
    </div>

    <div class="main-panel">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
					<?php if(isset($_SESSION['licenseID'])){
						echo '<a class="navbar-brand" href="#">Zarządzasz sklepem o nazwie: '.$shopName.'</a>';
					} else {
						echo '<a class="navbar-brand" href="#"><font color="red">Nie wybrałeś sklepu, którym chcesz zarządzać!</font></a>';
					} ?>
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
               
			                  
					<?php echo $blad; ?>
                <div class="row">
                    
					
                    <div class="col-md-14">
                        <div class="card">
                            <div class="header col-md-7">
                                <h4 class="title">Twoje wykupione licencje.</img></h4>
                                <p class="category">Poniżej znajduje się lista wszystkich Twoich sklepów.</p>
                            </div>
                            <div class="text-right header col-md-5 pull-right">
								<a style="margin-left:30px;" href="#coins"><btn class="btn btn-lg btn-success btn-fill btn-icon"><i class="fa fa-money" aria-hidden="true"></i> DOŁADUJ COINSY</btn></a>
								<a style="margin-left:30px;" href="newlicense.php"><btn class="btn btn-lg btn-primary btn-fill btn-icon"><i class="fa fa-plus" aria-hidden="true"></i>ZAKUP NOWĄ LICENCJĘ</btn></a>
                            </div>
                            <div style="padding: 50px 15px 10px 15px;" class="content">
							</div>
                             <div class="content">
                                <br>
									
								<?php 
										
									$query="SELECT * FROM `shops` WHERE `nick` LIKE '$user'";
									$result = mysql_query($query)
									or die(mysqli_error($result)); 
									$licensesNumber = mysql_num_rows($result);
									if ($licensesNumber <= 0){
										echo '<font size="5"><b><br>Nie posiadasz wykupionego żadnego sklepu na naszej witrynie.<br>Aby mieć dostęp do panelu administratora zakup licencję. <br>Kliknij przycisk powyżej aby to zrobić.</b></font>';
									} else {
										
											echo    ' <table class="table table-striped">';
											 echo '<thead>';
												  echo '<tr>';
														echo '<th><b>Nazwa sklepu</b></th>';
														echo '<th><b>Typ licencji</b></th>';
														echo '<th><b>Data wygaśniecia</b></th>';
														echo '<th><b>Serwery</b></th>';
														echo '<th><b>Wyświetlenia</b></th>';
														echo '<th style="width:auto;text-align: right;"><b>Wybierz co chcesz zrobić?</b></th>';
												  echo '</tr>';
											 echo '</thead>';
											 echo '<tbody>';
												
											while ($row = mysql_fetch_array($result)){
												echo '<tr style="border: 0px solid black;">';
													echo '<td style="padding: 6px 8px;">'.$row['name'].'</td>';
													if ($row['license'] == "single"){
														echo '<td style="padding: 6px 8px;">SINGLE</td>';
													} else {
														echo '<td style="padding: 6px 8px;">MULTI</td>';
													}
													echo '<td style="padding: 6px 8px;">'.$row['expire'].'</td>';
													echo '<td style="padding: 6px 8px;">'.$row['servers'].'</td>';
													echo '<td style="padding: 6px 8px;">'.$row['views'].'</td>';
													
													echo '<td style="padding: 6px 8px;">';
														echo '<div style="float:right;">';
														echo '<form style="display: inline; text-align: right; padding-bottom:4px;" method="post" action="expirelicense.php"> <button name="expire" type="submit" class="btn btn-sm btn-info btn-fill btn-icon"><i class="fa fa-clock-o" aria-hidden="true"></i>PRZEDŁUŻ LICENCJE</button></form>';
														if ($row['license'] == "single"){
															echo '<form style="display: inline; text-align: right; padding-bottom:4px;" method="post"> <input name="shopname" type="hidden" id="shopname" value="'.$row['name'].'"> <button name="change" type="submit" class="btn btn-sm btn-fill btn-info btn-icon"><i class="fa fa-recycle" aria-hidden="true"></i>ZMIEŃ NA MULTI</button></form>';
														}else{
															echo '<span style="display: inline; text-align: right; padding-bottom:4px;" method="post" action="#"> <button name="change" type="submit" class="btn disabled btn-sm btn-fill btn-info btn-icon"><i class="fa fa-recycle" aria-hidden="true"></i>ZMIEŃ NA MULTI</button></span>';
														}
														echo '<form style="display: inline; text-align: right; padding-bottom:4px;" method="post"> <input type="hidden" name="shopname" value="'.$row['name'].'" value="Norway"><button name="edit" type="submit" class="btn btn-sm btn-info btn-fill btn-icon"><i class="fa fa-cogs" aria-hidden="true"></i>ZARZĄDZAJ SKLEPEM</button></form>';
														echo '</div>';
													echo '</td>';
													
												 echo '</tr>';
											}
												 
											echo '</tbody>';
										echo '</table>';
									}
								?>	
								
								
                            </div>
                        </div>
                    </div>
					
					<div id="reg" class="col-lg-7 col-md-7">
                        <div class="card">
                            <div class="content">
								<br>
								<b>Regulamin - Ogólne postanowienia</b><br><br>
								<li>Przed zakupem klient powinien sprawdzić, czy jego hosting obsługuje zewnętrzne połączenie RCON. <br>    (W przypadku jego braku nie ma możliwości korzystania ze sklepu)</li>
								<li>Zezwalam na odbieranie maili wysyłanych w celu powiadamiania o akcjach w panelu oraz w celu reklamy.</li>
								<li>Wszelkie typu licencje użytkownik kupuje za pomocą wirtualnej waluty (COINS), którą może zakupić za reklne pieniądze.</li>
								<li>Właściciel nie odpowiada za błędy w konfiguracji serwera lub sklepu. Możemy jedynie pomóc w rozwiązaniu problemu.</li>
								<li>Rejestracja w serwisie dobrowolna. Do rejestracji wymagany jest login, hasło oraz adres e-mail.</li>
								<li>Właściciel dokłada wszelkich starań aby strona nie sprawiała problemów i trudności w użytkowaniu.</li>
								<li>Kupując / przedłużając usługę klient wyraża zgodę na rozpoczęcie wykonywania usługi w okresie na odstąpienie od umowy i przyjmuje do wiadomości, że w związku z tym utracił prawo do odstąpienia od umowy. </li>
								<li>Pieniądze zamienione na wirtualną walutę tracą wartość materialną i zamiana punktów na pieniądze nie jest możliwa.</li>
								<li>W wypadku braku dostępu do strony wina nie leży po stronie serwisu tylko hostingu <br>(tj. Firmy, która udostępnia przestrzeń dyskową dla mcSklepik.pl)</li>
								<li>Wyrażam zgodę na gromadzenie oraz przetważanie danych w tym loginu, hasła i oraz adresu e-mail.</li>
								</ul><br><br>
								
								<hr />
								 Właścicielem serwisu jest<br><br>
								GloBAJT Karol Cieślar<br>
								ul. Podgórze 52a<br>
								58-420 Lubawka<br>
								NIP: 6141609551<br><br>
								
											
											
                            </div>
                        </div>
                    </div>
					
					 <div id="coins" class="col-lg-5 col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Twój portfel: <?php echo $coins; ?></h4>
                                <p class="category">Aby zakupić licencje musisz posiadać coinsy.</p>
                            </div>
                            <div class="content">
                                
								<button class="btn btn-success btn-fill tablink" onclick="openPage('przelew', this, 'green')">DOŁADUJ PRZELEWEM</button>
								<button class="btn btn-success btn-fill tablink" onclick="openPage('sms', this, 'green')" id="defaultOpen">DOŁADUJ SMS'EM</button>
								<button class="btn btn-success btn-fill tablink" onclick="openPage('psc', this, 'green')">DOŁADUJ PAYSAFECARDEM</button>
								
							
								<div id="przelew" class="tabcontent">
								  <hr />
								  <h3>Wybrany sposób doładowania: PRZELEW</h3>
								  <p>Wpisz w formularzu poniżej ile coinsów chcesz doładować a następnie kliknij przycisk DOŁADUJ COINSY. Później postepuj zgodnie z informacjami na stronie.</p>
								  
								  
								  
												
											<form method="post" enctype="multipart/form-data">
												<div class="content">
												
													
													<div class="row">
														<div class="col-md-7">
															<div class="row">
																<div style="margin-top:5px" class="col-lg-12 col-md-12">
																	<div class="form-group">
																		<label>Ilość coinsów</label>
																	   <input min="1" placeholder="Wpisz tutaj ile chcesz doładować..." maxlength="20" type="number" name="coins" id="coins" class="form-control border-input" required>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-md-5">
															<div class="row">
																<div style="margin-top:10px" class="col-lg-12 col-md-12">
																	<div class="form-group">
																			<label> </label>
																			<div class="text-center">
																				<button name="przelew" type="submit" class="btn btn-info btn-fill btn-wd">DOŁADUJ COINSY</button>
																			</div>
																		</div>
																</div>
															</div>
														</div>
													</div>
											</div>
										</form>
											<center> 
											<hr />
											Doładowując coinsy akceptujesz <a style='text-decoration:none;' href='http://mcsklepik.pl/admin/dashboard.php#reg'>regulamin McSklepik.pl</a><br>
											Właściciel serwisu: GloBAJT Karol Cieślar | NIP: 6141609551<br>
											E-Mail kontaktowy: skrinszot[somehere]wp.pl<br>
										 
										 </center>
								</div>

								<div id="sms" class="tabcontent form-group has-feedback">
								  <hr />
								  <h3>Wybrany sposób doładowania: SMS</h3>
								  <p>Aby doładować coinsy za pomocą swojego telefonu komórkowego wybierz ile coinsów chcesz doładować a następnie wyślij sms na odpowiedni numer.</p>
											
											
								<center> 
										<br>
										<font size='4'>
										Aby doładować <b><text id="ilecoinsow">1x</text></b> coins na konto wyślij sms o treści<br>
										<b>MCPL.MCSKLEPIK.<?php echo $user;?></b> na numer <b><text id="numer">7255</text></b><br>
										Całkowity koszt SMS to <b><text id="koszt">2.46</text>&nbsp;zł</b><br>
										<font color="red"> Pamiętaj aby wpisać poprawną wielkość liter nicku!</font><br><br>
										</font>
									 
									 </center>
											
										<div class="form-group">
											<label style="width:200px;" >Wybierz ilość coinsów</label>
											<select class="form-control border-input" name="type" id="type" onchange="buyinfo(this.options[this.selectedIndex].value)" required>
												
												<option value="1" selected>1 coins za 2.46 zł z VAT</option>
												<option value="2">2 coinsy za 3.69 zł z VAT</option>
												<option value="3">3 coins za 6.15 zł z VAT</option>
												<option value="4">5 coins za 11.07 zł z VAT</option>
												<option value="5">7 coins za 17.22 zł z VAT</option>
												<option value="6">10 coins za 23.37 zł z VAT</option>
												<option value="7">13 coins za 30.75 zł z VAT</option>
											
											</select>
										</div>
											<center> 
											<hr />
											Wysyłając SMS akceptujesz <a style='text-decoration:none;' href='https://homepay.pl/regulamin/regulamin_uslug_premium/'>regulamin płatności SMS HomePay</a> oraz <a style='text-decoration:none;' href='http://mcsklepik.pl/admin/dashboard.php#reg'>regulamin McSklepik.pl</a><br>
											Zgłoszenie reklamacji płatności SMS dostępne pod <a style='text-decoration:none;' href='https://homepay.pl/reklamacje'>tym linkiem</a><br>
											Usługa dostępna dla T-Mobile, Plus, Orange oraz Play<br>
											Właściciel serwisu: GloBAJT Karol Cieślar | NIP: 6141609551<br>
											E-Mail kontaktowy: skrinszot[somehere]wp.pl<br>
											Infolinia płatności SMS HomePay: +48 (22) 266 85 25<br>
										 
										 </center>
										
								</div>

							
								<div id="psc" class="tabcontent">
								  <hr />
								  <h3>Wybrany sposób doładowania: PAYSAFECARD</h3>
								  <p>Wpisz w formularzu poniżej ile coinsów chcesz doładować a następnie kliknij przycisk DOŁADUJ COINSY. Później postepuj zgodnie z informacjami na stronie.</p>

										<form method="post" enctype="multipart/form-data">
											<div class="content">
											
												<div class="row">
													<div class="col-md-7">
														<div class="row">
															<div style="margin-top:5px" class="col-lg-12 col-md-12">
																<div class="form-group">
																	<label>Ilość coinsów</label>
																   <input min="1" placeholder="Wpisz tutaj ile chcesz doładować..." maxlength="20" type="number" name="coins" id="coins" class="form-control border-input" required>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-5">
														<div class="row">
															<div style="margin-top:10px" class="col-lg-12 col-md-12">
																<div class="form-group">
																		<label> </label>
																		<div class="text-center">
																			<button name="paysafecard" type="submit" class="btn btn-info btn-fill btn-wd">DOŁADUJ COINSY</button>
																		</div>
																	</div>
															</div>
														</div>
													</div>
												</div>
										</div>
									</form>
										<center> 
										<hr />
										Doładowując coinsy akceptujesz <a style='text-decoration:none;' href='http://mcsklepik.pl/admin/dashboard.php#reg'>regulamin McSklepik.pl</a><br>
										Właściciel serwisu: GloBAJT Karol Cieślar | NIP: 6141609551<br>
										E-Mail kontaktowy: skrinszot[somehere]wp.pl<br>
									 
									 </center>
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
