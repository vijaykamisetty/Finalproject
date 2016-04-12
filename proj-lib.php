
<?php
isset ($_REQUEST['s']) ? $s = strip_tags($_REQUEST['s']) : $s="";
isset ($_REQUEST['pid']) ? $pid = strip_tags($_REQUEST['pid']) : $pid="";
function connect(&$db){
$mycnf="/etc/proj-mysql.conf";
if (!file_exists($mycnf)){
echo "Error file not found : $mycnf";
exit;
}
$mysql_ini_array=parse_ini_file($mycnf);
$db_host=$mysql_ini_array["host"];
$db_user=$mysql_ini_array["user"];
$db_pass=$mysql_ini_array["pass"];
$db_port=$mysql_ini_array["port"];
$db_name=$mysql_ini_array["dbname"];
$db=mysqli_connect($db_host,$db_user,$db_pass,$db_name,$db_port);
if(!$db){
print "Error connecting:".mysqli_connect_error();
exit;
}
}
?>

