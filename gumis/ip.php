<?

$index = 'index.html';
$log = 'log.txt';

$ip = $_SERVER['REMOTE_ADDR'];
$ip .= "\n";

file_put_contents($index, $ip);
file_put_contents($log, $ip, FILE_APPEND |LOCK_EX);

?>
