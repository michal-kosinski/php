<?
error_reporting(E_ALL);
ini_set('display_errors', True);
date_default_timezone_set('Europe/Warsaw');

if (!isset($_COOKIE['CoDzisJemy'])) {
	$value = mt_rand(1,43); 
	$expiry = time()+3600*24;
	$my_date = date("d.m");
	$my_time = date("H:i");
	$data = (object) array( "random" => $value, "data" => $my_date, "czas" => $my_time );
	$cookieData = (object) array( "data" => $data, "expiry" => $expiry );
	setcookie( "CoDzisJemy", base64_encode(json_encode($cookieData)), $expiry );
	header("Refresh:0");
 } 

else {

$cookie = json_decode(base64_decode($_COOKIE['CoDzisJemy']));
$random = $cookie->data->random;
$data = $cookie->data->data;
$czas = $cookie->data->czas;

echo "
<!DOCTYPE html>
<html>
<head>
	<meta charset=\"UTF-8\">
	<style type=\"text/css\">
  	 p { font-family: Verdana; font-size: 10pt; text-align: center; line-height: 2.0em;}
  	 p.b { font-size: 12pt; font-weight:bold; }
 	</style>
 	<title>Co dziś jemy?</title>
</head>
<body>
<p><img src=\"simon.jpg\" alt=\"\"></p>
";
	
echo "<p class=\"b\">Dnia ".$data." o ".$czas." wylosowałeś numer ".$random.":</p><p><img src=\"".$random.".png\" alt=\"\"><br><br>Następne losowanie możliwe za 24 godz.<br/>(ewentualnie użyj metody Emacsem przez Sendmail&trade;)</p>";
echo "<p>Pula:</p>";

for ($i = 1; $i <= 43; $i++) {
    echo "<p class=\"b\">".$i."<br/><br/><img src=\"".$i.".png\" alt=\"\"></p>";        
    // echo "&nbsp;&nbsp;<img src=\"".$i.".png\" alt=\"\">&nbsp;&nbsp;";        
    }

echo "</body></html>";

}

?>
