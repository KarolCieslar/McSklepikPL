<?php 
	session_start(); if (isset($_SESSION['logged'])) { header('Location: dashboard.php'); exit(); }
	include 'config.php'; 


$nick = mysql_real_escape_string($_POST['nick']);
$password = mysql_real_escape_string($_POST['password']);
	
	if (!empty($_POST)){
		if (!empty($_POST['nick']) && !empty($_POST['password'])) {
			$stored_secret = mysql_result(mysql_query("SELECT pass FROM `admins` WHERE `nick` LIKE '$nick'"), 0 );
		
			if (password_verify($password, $stored_secret)) {
				#$licenseID = mysql_result(mysql_query("SELECT licenseID FROM `shops` WHERE `nick` LIKE '$nick'"), 0 );
				$_SESSION['logged'] = true;
				$_SESSION['user'] = $nick;
				header('Location: dashboard.php');
			} else {
				$blad = '<div class="alert alert-danger"> <span>Podane hasło lub login nie zgadzają się!</span></div>';
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

	<title>Logowanie do sklepu McSklepik.pl</title>

	<link rel="stylesheet" href="styles/form-register.css?2">

    <!-- Animation library for notifications   -->
    <link href="assets/css/animate.min.css" rel="stylesheet"/>

    <!--  Paper Dashboard core CSS    -->
    <link href="assets/css/paper-dashboard.css?21" rel="stylesheet"/>
	

	<script type="text/javascript">
		function showfield(name){
			
		  if(name=='single')
			  document.getElementById('price').innerHTML='<label><span>Czas licencji</span><select name="time" class="form-control border-input" required><option value="">-- nie wybrano --</option><option value="1">1 miesiąc (10 PLN)</option><option value="2">2 miesiące (19 PLN)</option><option value="3">5 miesiący (45 PLN)</option><option value="4">12 miesiący (100 PLN)</option></select></label>';
		  else if(name=='multi') 
			  document.getElementById('price').innerHTML='<label><span>Czas licencji</span><select name="time" class="form-control border-input" required><option value="">-- nie wybrano --</option><option value="1">1 miesiąc (24 PLN)</option><option value="2">2 miesiące (40 PLN)</option><option value="3">5 miesiący (99 PLN)</option><option value="4">12 miesiący (260 PLN)</option></select></label>';
		 else if(name=='none') 
			  document.getElementById('price').innerHTML='';
		}
	</script>
	
</head>


    <div class="main-content">

        <!-- You only need this form and the form-register.css -->

        <form class="form-register" method="post" action="#">

            <div class="form-register-with-email">

                <div class="form-white-background">

                    <div class="form-title-row">
                        <h1>Zaloguj się do panelu Admina!</h1>
                    </div>
					
                    <div class="form-title-row">
                        <?php echo $blad; ?>
                    </div>
                    
                    <div class="form-row">
                        <label>
                            <span>Nick</span>
                            <input maxlength="30" class="form-control border-input" type="text" name="nick" required>
                        </label>
                    </div>

                    <div class="form-row">
                        <label>
                            <span>Hasło</span>
                            <input maxlength="20" class="form-control border-input" type="password" name="password" required>
                        </label>
                    </div>

                    <div class="form-row">
                        <button type="submit">ZALOGUJ SIĘ</button>
                    </div>

                </div>

                <a href="register.php" class="form-log-in-with-existing">Nie posiadasz konta? Załóż go tutaj &rarr;</a>

            </div>

        </form>

    </div>
	
</body>

</html>
