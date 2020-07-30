	

	<?php 
		include 'config.php';
		$day   = date('Y/m/d'); // data
		function roznica_data($data_poczatek, $date_koniec, $jednostka_czasu="sekund") 
		{ 
		 $tablica = array(minut=>60, godzin=>3600, dni=>86400, sekund=>1);
		 return "".round(((strtotime($date_koniec) - strtotime($data_poczatek)) / $tablica[$jednostka_czasu]))." ".$jednostka_czasu; 
		}
	
	
	
	
		$query="SELECT * FROM `shops`";
		$result = mysql_query($query)
		or die(mysqli_error($result));
		$check = false;
		while ($row = mysql_fetch_array($result)){
			$licenseID = $row['licenseID'];
			# KIEDY LICENCJA PAD£Aw
			$expireDate = mysql_result( mysql_query("SELECT expire FROM `shops` WHERE `licenseID` = '$licenseID'"), 0 );
			$licenseDays = intval(roznica_data($expireDate, $day, "dni"));
			$nazwa = $row['name'];
			$nick = $row['nick'];
			$email = mysql_result( mysql_query("SELECT email FROM `admins` WHERE `nick` = '$nick'"), 0 );
			$dni = $licenseDays;
			$dni = str_replace("-","",$dni);
			if ($licenseDays == "-7" || $licenseDays == "-3"){
				przypomnienie($nazwa, $dni, $nick, $email);
			}elseif ($licenseDays == "0"){
				wygasla($nazwa, $nick, $email);
			}
		}
		
		function przypomnienie($nazwa, $dni, $nick, $email){
			$to = $email;
			$subject = 'Twoja licencja sklepu '.$nazwa.' wygasa za kilka dni!';
			
			$message = file_get_contents('http://www.mcsklepik.pl/admin/inc/expire.html');
			$message = str_replace('USERNICK', $nick, $message); 
			$message = str_replace('USERSHOP', $nazwa, $message);
			$message = str_replace('USERDAYS', $dni, $message);
			
			$headers = 'From: automat@mcsklepik.pl' . "\r\n" .
			   'Reply-To: skrinszot@wp.pl' . "\r\n" .
			   'Content-type: text/html; utf-8' . "\r\n";
			
			mail($to, $subject, $message, $headers);
			return;
		}
		
		function wygasla($nazwa, $nick, $email){
			$to = $email;
			$subject = 'Twoja licencja sklepu '.$nazwa.' wygasla!';
			
			$message = file_get_contents('http://www.mcsklepik.pl/admin/inc/licensedown.html');
			$message = str_replace('USERNICK', $nick, $message); 
			$message = str_replace('USERSHOP', $nazwa, $message);
			
			$headers = 'From: automat@mcsklepik.pl' . "\r\n" .
			   'Reply-To: skrinszot@wp.pl' . "\r\n" .
			   'Content-type: text/html; utf-8' . "\r\n";
			
			mail($to, $subject, $message, $headers);
			return;
		}
	?>