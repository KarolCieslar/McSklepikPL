
<?php 
# POŁĄCZENIE Z BAZĄ DANYCH
error_reporting( E_ALL & ~E_DEPRECATED & ~E_NOTICE );
if(!mysql_connect("localhost","shop","uQHrweuVz9bYSyrN"))
{
	die('oops connection problem ! --> '.mysql_error());
}
if(!mysql_select_db("shop"))
{
	die('oops database selection problem ! --> '.mysql_error());

}
?>
