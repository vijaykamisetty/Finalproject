
<?php
isset ($_REQUEST['s']) ? $s = strip_tags($_REQUEST['s']) : $s="";
isset ($_REQUEST['profName']) ? $profName = strip_tags($_REQUEST['profName']) : $profName="";

isset ($_REQUEST['deptName']) ? $deptName = strip_tags($_REQUEST['deptName']) : $deptName="";
isset ($_REQUEST['picd']) ? $picd = strip_tags($_REQUEST['picd']) : $picd="";

isset ($_REQUEST['pictureURL']) ? $pictureURL = strip_tags($_REQUEST['pictureURL']) : $pictureURL="";
isset ($_REQUEST['select_prof']) ? $select_prof = strip_tags($_REQUEST['select_prof']) : $select_prof="";
isset ($_REQUEST['corname']) ? $corname = strip_tags($_REQUEST['corname']) : $corname="";
isset ($_REQUEST['cordesc']) ? $cordesc = strip_tags($_REQUEST['cordesc']) : $cordesc="";
isset ($_REQUEST['pid']) ? $pid = strip_tags($_REQUEST['pid']) : $pid="";
isset ($_REQUEST['stname']) ? $stname = strip_tags($_REQUEST['stname']) : $stname="";

isset ($_REQUEST['stkey']) ? $stkey = strip_tags($_REQUEST['stkey']) : $stkey="";
isset ($_REQUEST['select_stud']) ? $select_stud = strip_tags($_REQUEST['select_stud']) : $select_stud="";
isset ($_REQUEST['select_stud1']) ? $select_stud1 = strip_tags($_REQUEST['select_stud1']) : $select_stud1="";

isset ($_REQUEST['tmp_snam']) ? $tmp_snam = strip_tags($_REQUEST['tmp_snam']) : $tmp_snam="";
isset ($_REQUEST['corid']) ? $corid = strip_tags($_REQUEST['corid']) : $corid="";
isset ($_REQUEST['grad_val']) ? $grad_val = strip_tags($_REQUEST['grad_val']) : $grad_val="";
isset ($_REQUEST['select_sti']) ? $select_sti = strip_tags($_REQUEST['select_sti']) : $select_sti="";
isset ($_REQUEST['select_cor']) ? $select_cor = strip_tags($_REQUEST['select_cor']) : $select_cor="";
isset ($_REQUEST['postPass']) ? $postPass = strip_tags($_REQUEST['postPass']) : $postPass="";
isset ($_REQUEST['postUser']) ? $postUser = strip_tags($_REQUEST['postUser']) : $postUser="";
isset ($_REQUEST['newUser']) ? $newUser = strip_tags($_REQUEST['newUser']) : $newUser="";
isset ($_REQUEST['newPass']) ? $newPass = strip_tags($_REQUEST['newPass']) : $newPass="";
isset ($_REQUEST['newEmail']) ? $newEmail = strip_tags($_REQUEST['newEmail']) : $newEmail="";
isset ($_REQUEST['type_person']) ? $type_person = strip_tags($_REQUEST['type_person']) : $type_person="";
isset ($_REQUEST['profIden']) ? $profIden = strip_tags($_REQUEST['profIden']) : $profIden="";
isset ($_REQUEST['select_cor_grad']) ? $select_cor_grad = strip_tags($_REQUEST['select_cor_grad']) : $select_cor_grad="";


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

function checkauth(){
        if (isset($_SESSION['HTTP_USER_AGENT'])){
                if($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['SERVER_ADDR'].$_SERVER['HTTP_USER_AGENT'])){
                        logout();
        }
        }else {
                logout();
        }
        if(isset($_SESSION['ip'])){
                if($_SESSION['ip'] != $_SERVER['REMOTE_ADDR']){
                        logout();
                }
        }else{
                logout();
        }
        if("POST" == $_SERVER['REQUEST_METHOD']) {
                if(isset($_SERVER['HTTP_ORIGIN'])){
                        if($_SERVER['HTTP_ORIGIN'] != "https://100.66.1.14"){
                                logout();
                        }
                        }else{
                                logout();
                        }
}

        if(isset($_SESSION['created'])){
                if( time()-$_SESSION['created'] > 1800){
                        logout();
                }
        }else {
                logout();
        }

}




function authenticate($db,$postUser,$postPass){
	$_SESSION['ip']=$_SERVER['REMOTE_ADDR'];
        $_SESSION['HTTP_USER_AGENT']=md5($_SERVER['SERVER_ADDR'].$_SERVER['HTTP_USER_AGENT']);
        $_SESSION['created']=time();
        $addr_ser=$_SERVER['SERVER_ADDR'];

	$test_ip=$_SERVER['REMOTE_ADDR'];
	$whitelist=array("198.18.1.198");

	$query1="select count(ip) from login where date between DATE_SUB(NOW(), INTERVAL  1 HOUR) and NOW() and action='fail' and ip=?";
        if($stmt=mysqli_prepare($db,$query1)){
                mysqli_stmt_bind_param($stmt,"s",$test_ip);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt,$cnt_ip);
                while(mysqli_stmt_fetch($stmt)){
                        $cnt_ip=$cnt_ip;
                }
                mysqli_stmt_close($stmt);
        }
        if(!in_array($test_ip,$whitelist))
        {
        if($cnt_ip>=5){
        exit;
        }
        }

	$query="select userid,email,password,salt,categ from users where ukey=?";
	if($stmt=mysqli_prepare($db,$query)){
		mysqli_stmt_bind_param($stmt,"s",$postUser);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt,$userid,$email,$password,$salt,$categ);
		while(mysqli_stmt_fetch($stmt)){
			$userid=$userid;
			$password=$password;
			$salt=$salt;
			$email=$email;
			$categ=$categ;
		}	
	mysqli_stmt_close($stmt);
	$epass=hash('sha256',$postPass.$salt);
	if($epass==$password){

		$_SESSION['ukey']=$postUser;
		$_SESSION['userid']=$userid;
		$_SESSION['email']=$email;
		$_SESSION['authenticated']="yes";
		$_SESSION['categ']=$categ;
		if($stmt=mysqli_prepare($db,"INSERT into login set loginid='', ip=?, user=?, date=now(), action='pass'")){
                mysqli_stmt_bind_param($stmt,"ss",$test_ip,$postUser);
                mysqli_stmt_execute($stmt);
                }
                mysqli_stmt_close($stmt);

		}else{
			if($stmt=mysqli_prepare($db,"INSERT into login set loginid='', ip=?, user=?, date=now(), action='fail'")){
        		        mysqli_stmt_bind_param($stmt,"ss",$test_ip,$postUser);
	                	mysqli_stmt_execute($stmt);
                	}	
        	        mysqli_stmt_close($stmt);
	
			header("Location: /project/login.php");
			exit;
		}
	}
}
function logout(){
	session_destroy();
	header("Location: /project/login.php");

}
function admin_check(){
        if($_SESSION['userid'] != 1){
                echo "Error : Functinoality not enabled";
                exit;
        }
}
function student_check(){
        if($_SESSION['categ'] != "student"){
                echo "Error : Functinoality not enabled";
                exit;
        }
}
function professor_check(){
	if($_SESSION['categ']!="professor"){
		echo "Error:Functionality not enabled";
		exit;
	}
}
function icheck($i){
        if($i != null) {
                if (!is_numeric($i)){
                        print "<b> ERROR: </b> Invalid Syntax.";
                exit;
                }
        }
}




?>

