<?php 
	session_start(); if (isset($_SESSION['logged'])) { header('Location: dashboard.php'); exit(); }
	
	include 'config.php'; 


$nick = mysql_real_escape_string($_POST['nick']);
$mail = mysql_real_escape_string($_POST['mail']);
$password = mysql_real_escape_string($_POST['password']);
$password_repeat = mysql_real_escape_string($_POST['password_repeat']);
	
	
	if (!empty($_POST)){
		if (!empty($_POST['mail']) && !empty($_POST['nick']) && !empty($_POST['password']) && !empty($_POST['password_repeat'])) {
			
			# SPRAWDZANIE CZY NICK ADMINA JEST W BAZIE
			$result = mysql_query("SELECT * FROM `admins` WHERE `nick` LIKE '$nick'") or die(mysqli_error($result));
			$num_rows = mysql_num_rows($result);
			if ($num_rows == 1){
				if (isset($blad)){
					$blad = ''.$blad.'<br>Podany nick jest już zajęty!';
				} else {
					$blad = 'Podany nick jest już zajęty!';
				}
			}	

			if($password != $password_repeat){
				if (isset($blad)){
					$blad = ''.$blad.'<br>Podane hasła nie zgadzają się!';
				} else {
					$blad = 'Podane hasła nie zgadzają się!';
				}
			}
				
			if (!isset($blad)){
				# LICENCJA HASŁO
				$hashPassword = password_hash($password, PASSWORD_DEFAULT);

				mysql_result(mysql_query("INSERT INTO `admins` (`uid`, `nick`, `pass`, `email`, `coins`) VALUES (NULL, '$nick', '$hashPassword', '$mail', '0');"), 0 );
				$blad = '<div class="alert alert-success"> <span>Twoje konto zostało założone poprawnie! <br> Możesz już zalogowac się do panelu!</span></div>';
				echo '<meta http-equiv="refresh" content="4; URL=http://mcsklepik.pl/admin/dashboard.php">';
			
			} else {
				$blad = '<div class="alert alert-warning"> <span>'.$blad.'</span></div>';
			}
			
			
	 } else {
			$blad = '<div class="alert alert-warning"> <span>Halo! Nie wszystkie pola formularza zostały wypełnione! Prosimy uzupełnić wpisane dane.</span></div>';
	 }
	}
	
?>

<!DOCTYPE html>
<html>

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicon.png">

	<title>Rejestracja nowego sklepu McSklepik.pl</title>

	<link rel="stylesheet" href="styles/form-register.css?2">

    <!-- Animation library for notifications   -->
    <link href="assets/css/animate.min.css" rel="stylesheet"/>

    <!--  Paper Dashboard core CSS    -->
    <link href="assets/css/paper-dashboard.css?21" rel="stylesheet"/>
	
</head>
<body>

    <div class="main-content">

        <!-- You only need this form and the form-register.css -->

        <form class="form-register" method="post" action="#">

            <div class="form-register-with-email">

                <div class="form-white-background">

                    <div class="form-title-row">
                        <h1>Rejestracja nowego konta!</h1>
                    </div>
					
                    <div class="form-title-row">
                        <?php echo $blad; ?>
                    </div>
								
					
                    <div class="form-row">
                        <label>
                            <span>Nick</span>
                            <input value="<?php echo $nick;?>" maxlength="30" class="form-control border-input" type="text" name="nick" required>
                        </label>
                    </div>
					
                    <div class="form-row">
                        <label>
                            <span>Adres E-Mail</span>
                            <input value="<?php echo $mail;?>" maxlength="50" class="form-control border-input" type="email" name="mail" required>
                        </label>
                    </div>
					
                    <div class="form-row">
                        <label>
                            <span>Hasło Admina</span>
                            <input maxlength="20" class="form-control border-input" type="password" name="password" required>
                        </label>
                    </div>

                    <div class="form-row">
                        <label>
                            <span>Powtórz hasło</span>
                            <input maxlength="20" class="form-control border-input" type="password" name="password_repeat" required>
                        </label>
                    </div>

                    <div class="form-row">
                        <label class="form-checkbox">
                            <input type="checkbox" name="checkbox" required>
                            <span>Zapoznałem się i potwierdzam <a href="regulamin.php">REGULAMIN SERWISU</a></span>
                        </label>
                    </div>

                    <div class="form-row">
                        <button type="submit">ZAREJESTRUJ KONTO</button>
                    </div>

                </div>

                <a href="index.php" class="form-log-in-with-existing">Masz już konto? Zaloguj się tutaj &rarr;</a>

            </div>

        </form>

    </div>
	
	<script type="text/javascript">
		var input = document.getElementById('name'); 
		input.onkeyup = function() {
			document.getElementById('nameShop').innerHTML = 'www.<font color="blue">' + input.value + '</font>.mcsklepik.pl';    
		}
	</script>
</body>

</html>
