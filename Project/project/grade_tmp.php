<?php
session_start();
include_once('/var/www/html/project/proj-lib.php');
include_once('header.php');
connect($db); 

if(!isset($_SESSION['authenticated'])){
authenticate($db,$postUser,$postPass);
checkauth();
}
icheck($s);
icheck($select_cor);
icheck($select_sti);

switch ($s){
default ;
	 echo "</br>Welcome to University of Colorado Boulder : Connect2College </br>";
	break;
case 15;
	admin_check();
	echo "<form method=post action=grade.php>";
	echo "<table><tr><td> <span style= \"color : #4B0082;font-size:15px;font-family:Arial, Helvetica, sans-serif\"> Select the Course to which the student belongs </span>";
	if($stmt=mysqli_prepare($db,"select cid,cname from courses")){
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt,$corid,$conam);
	}
	echo "</br><span style=\"text-align:center\"><select name=\"select_cor\" id=\"stucor\">";
	while (mysqli_stmt_fetch($stmt)){
		$conam=htmlspecialchars($conam);
		echo "<option value=\"$corid\"> $conam </option>";
		}
	echo "</span></select>";
	echo "</tr>";
	mysqli_stmt_close($stmt);
	echo "<tr><td><input type=\"submit\" name=\"submit\" value=\"Select Course\"></td></tr>";
	echo "<tr><td><input type=\"hidden\" name=\"s\" value=\"16\"></td></tr>";
	echo "</table>";
	 echo "<style>
        html {
                background-color:#D3D3D3;
        }
        </style>";
	echo "</form>";
	break;
case 16;
	admin_check();
	$select_cor=mysqli_real_escape_string($db,$select_cor);
	echo "<form method=post action=grade.php>";
	echo "<table><tr><td colspan=2> <td><span style= \"color : #4B0082;font-size:15px;font-family:Arial, Helvetica, sans-serif\">Select the student</span></td> </br>";
	if ($stmt=mysqli_prepare($db,"select sid,sname from student where sid IN (select frsid from grades where gradepoint is NULL and frcid=?)")){
		mysqli_stmt_bind_param($stmt,"s",$select_cor);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt,$stid1,$stname1);
	}
	echo "<td><select name=\"select_sti\" id=\"corst\">";
	while (mysqli_stmt_fetch($stmt)){
		$stid1=htmlspecialchars($stid1);
		$stname1=htmlspecialchars($stname1);
		echo "<option value=\"$stid1\"> $stname1 </option>";
	}
	echo "</select>";
	echo "</td></tr>";
	mysqli_stmt_close($stmt);
	echo "<tr><td colspan=2><td> <span style= \"color : #4B0082;font-size:15px;font-family:Arial, Helvetica, sans-serif\">Give Grade between 0.0-4.0</span></td><td><input type=\"number\" step=\"any\" min=\"0\" max=\"4\" name=\"grad_val\"></td></tr>";
	echo "<tr><td colspan=2><td><input type=\"submit\" name=submit value=\"submit\"></td><td><input type=\"hidden\" name=s value=\"17\"></td>";
	echo "<tr><td colspan=2><td><input type=\"hidden\" name=select_cor value=\"$select_cor\"></td>";
	 echo "<style>
        html {
                background-color:#D3D3D3;
        }
        </style>";

	
	echo "</table>";
	echo "</form>";
	break;
case 17;
	admin_check();
	$select_cor=mysqli_real_escape_string($db,$select_cor);
	$select_sti=mysqli_real_escape_string($db,$select_sti);
	/*echo "The value of select_sti is $select_sti";
	$grad_val=mysqli_real_escape_string($db,$grad_val);
	$result=mysql_query("select gid from grades where frcid=?, frsid=?");
	$row=mysqli_fetch_row($result);
	echo "the value is $row[0]";*/
	if ($stmt=mysqli_prepare($db,"select gid from grades where frcid=? and frsid=?")){
		mysqli_stmt_bind_param($stmt,"ss",$select_cor,$select_sti);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt,$gid1);
	}
	while (mysqli_stmt_fetch($stmt)){
		$gid1=htmlspecialchars($gid1);
		}
	mysqli_stmt_close($stmt);
	
	/*if ($stmt=mysqli_prepare($db, "delete from grades where gid=?")){
		mysqli_stmt_bind_param($stmt,"s",$gid1);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}*/
	
	if($stmt=mysqli_prepare($db,"replace into grades (gid,frsid,frcid,gradepoint) values(?,?,?,?)")){
		mysqli_stmt_bind_param($stmt,"ssss",$gid1,$select_sti,$select_cor,$grad_val);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
	 echo "<form method=post action=grade.php>";
        echo "<table><tr><td colspan=2<td> <span style= \"color : #4B0082;font-size:15px;font-family:Arial, Helvetica, sans-serif\">Select the student</span> </td></br>";
        if ($stmt=mysqli_prepare($db,"select sid,sname from student where sid IN (select frsid from grades where gradepoint is NULL and frcid=?)")){
                mysqli_stmt_bind_param($stmt,"s",$select_cor);

                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt,$stid1,$stname1);
        }
        echo "<td><select name=\"select_sti\" id=\"corst\">";
        while (mysqli_stmt_fetch($stmt)){
                $stid1=htmlspecialchars($stid1);
                $stname1=htmlspecialchars($stname1);
                echo "<option value=\"$stid1\"> $stname1 </option>";
        }
        echo "</select>";
	echo "</td></tr>";
	mysqli_stmt_close($stmt);
	echo "<tr><td colspan=2><td><span style= \"color : #4B0082;font-size:15px;font-family:Arial, Helvetica, sans-serif\">Give Grade between 0.0-4.0</span></td><td><input type=\"number\" step=\"any\" min=\"0\" max=\"4\" name=\"grad_val\"></td></tr>";
	
	echo "<tr><td colspan=2><td><input type=\"submit\" name=submit value=\"submit\"></td><td><input type=\"hidden\" name=s value=\"16\"></td>";
	echo "<tr><td colspan=2><td><input type=\"hidden\" name=select_cor value=\"$select_cor\"></td>";
	echo "</table>";
	echo "</form>";
	echo "<style> 
	 html { 
		background-color:#D3D3D3;
	}
	</style>";
	
	break;
}

if ($_SESSION['userid']==1){
        echo "<div style=\"text-align:left;font-size:25px\"><span style=\"font-family:Arial, Helvetica, sans-serif\"><a href=add.php?s=4> Add New Professors </a></span></div> <br>";
}
if ($_SESSION['userid']==1){
        echo "<div style=\"text-align:left;font-size:25px\"><span style=\"font-family:Arial, Helvetica, sans-serif\"><a href=add.php?s=7> Add New courses </a> </span></div><br>";
}
if ($_SESSION['userid']==1){
        echo "<div style=\"text-align:left;font-size:25px\"><span style=\"font-family:Arial, Helvetica, sans-serif\"><a href=add.php?s=9> Add students </a></span></div> <br>";
}
if ($_SESSION['userid']==1){
        echo "<div style=\"text-align:left;font-size:25px\"><span style=\"font-family:Arial, Helvetica, sans-serif\"><a href=add.php?s=11> Course Enrollment for students </a></span></div><br>";
}
if ($_SESSION['userid']==1){
        echo "<div style=\"text-align:left;font-size:25px\"><span style=\"font-family:Arial, Helvetica, sans-serif\"><a href=grade.php?s=15> Give Grades </a></span></div><br>";
}
if ($_SESSION['userid']==1){
        echo "<div style=\"text-align:left;font-size:25px\"><span style=\"font-family:Arial, Helvetica, sans-serif\"><a href=add.php?s=90> Add New Users </a></span></div><br>";
}
if ($_SESSION['categ'] == "student"){
        echo "<div style=\"text-align:left;font-size:25px\"><span style=\"font-family:Arial, Helvetica, sans-serif\"><a href=view.php?s=100> View Grades </a></span></div><br>";
}
if ($_SESSION['categ'] == "professor"){
        echo "<div style=\"text-align:left;font-size:25px\"><span style=\"font-family:Arial, Helvetica, sans-serif\"><a href=view.php?s=101> View Grades for students </a></span></div></br>";
}
if ($_SESSION['userid']==1){
        echo "<div style=\"text-align:left;font-size:25px\"><span style=\"font-family:Arial, Helvetica, sans-serif\"><a href=add.php?s=93> Failed Logs </a> </span></div><br>";
}
if ($_SESSION['userid']==1){
        echo "<div style=\"text-align:left;font-size:25px\"><span style=\"font-family:Arial, Helvetica, sans-serif\"><a href=add.php?s=92> Display Users </a> </span></div><br>";
}
echo "<div style=\"text-align:left;font-size:25px\"><span style=\"font-family:Arial, Helvetica, sans-serif\"><a href=add.php?s=99> Logout </a></span></div><br>";

include_once('footer.php');

?>
	
	
		
	
		
	
	
