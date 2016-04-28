<?php
session_start();
include_once('/var/www/html/project/proj-lib.php');
include_once('header.php');
connect($db);

if(!isset($_SESSION['authenticated'])){
authenticate($db,$postUser,$postPass);
checkauth();
}

switch ($s){
default ;
         echo "</br>Welcome to University of Colorado Boulder : Connect2College </br>";
        break;
case 100;
	student_check();
	$res= $_SESSION['ukey'];
	if($stmt=mysqli_prepare($db,"select sid,sname from student where identitikey=?")){
		mysqli_stmt_bind_param($stmt,"s",$res);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt,$sid,$sname);
	
	while(mysqli_stmt_fetch($stmt)){
		$sid=htmlspecialchars($sid);
		$sname=htmlspecialchars($sname);
	}
	mysqli_stmt_close($stmt);
	}
	if($stmt=mysqli_prepare($db,"select cname,description,gradepoint from courses,grades,student where student.sid=grades.frsid and courses.cid =grades.frcid and student.sid=?")){
		mysqli_stmt_bind_param($stmt,"s",$sid);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt,$cname,$description,$gradepoint);
	echo "<table><tr><td><b><span style=\"color : #008080;font-size:30px;font-family:Arial, Helvetica, sans-serif\">$sname</br>Good Job..!!</span></b></td></tr>\n ";
	while($row=mysqli_stmt_fetch($stmt)){
		echo "<tr><td><span style=\"color : #008080;font-size:20px;font-family:Arial, Helvetica, sans-serif\">$cname</span></td> <td><span style=\"color : #008080;font-size:20px;font-family:Arial, Helvetica, sans-serif\">$description</span></td> <td><b><span style=\"color : #800000;font-size:20px;font-family:Arial, Helvetica, sans-serif\">$gradepoint</b></span></td></tr>";
		
	}
	
	mysqli_stmt_close($stmt);
	}
	echo "<body style=\"background-color:#B0C4DE\">";

	break;
case 101;
	professor_check();
	$res=$_SESSION['ukey'];
	if($stmt=mysqli_prepare($db,"select cid,cname from courses,professors where courses.frcid=professors.pid and courses.frcid=(select pid from professors where identitikey=?)")){
		mysqli_stmt_bind_param($stmt,"s",$res);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt,$cidlast,$cnamelast);
		}
		echo "<form method=post action=view.php>";
		echo "<table><tr><td colspan=1><span style=\"color : #008080;font-size:20px;font-family:Arial, Helvetica, sans-serif\"> See the grades </span></td></tr></br>";
		echo "<tr><td colspan=2> <span style=\"color : #008080;font-size:18px;font-family:Arial, Helvetica, sans-serif\">Select a course</span> </td>";
		echo "<td><select name=\"select_cor_grad\" id=\"s_c_g\">";
		while (mysqli_stmt_fetch($stmt)){
			$cidlast=htmlspecialchars($cidlast);
			$cnamelast=htmlspecialchars($cnamelast);
			echo "<option value=\"$cidlast\"> $cnamelast </option>";	
			}
		echo "</span></select></td>";
		echo "</tr>";
		mysqli_stmt_close($stmt);
		echo "<tr><td><input type=\"submit\" name=\"submit\" value=\"show grades\"></tr></td>";
		echo "<input type=\"hidden\" name=\"s\" value=\"102\">";
		echo "</table>";
		echo "<body style=\"background-color:#B0C4DE\">";
		echo "</form>";
		break;
case 102;
		professor_check();
		$select_cor_grad=mysqli_real_escape_string($db,$select_cor_grad);
		if($stmt=mysqli_prepare($db,"select sname,gradepoint from student,grades where student.sid=grades.frsid and grades.frcid=?")){
			mysqli_stmt_bind_param($stmt,"s",$select_cor_grad);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt,$sname,$gradepoint);
		echo "<table><tr><td><b><u> <span style=\"color : #008080;font-size:18px;font-family:Arial, Helvetica, sans-serif\"><a href=view.php?s=101> List of grades</span> </b></u></td></tr>\n";
		while($row=mysqli_stmt_fetch($stmt)){
                echo "<tr><td><span style=\"color : #008080;font-size:18px;font-family:Arial, Helvetica, sans-serif\">$sname</span></td> <td><span style=\"color : #008080;font-size:18px;font-family:Arial, Helvetica, sans-serif\">$gradepoint</span></td>";

    		    }

	        mysqli_stmt_close($stmt);
        	}
		echo "<body style=\"background-color:#B0C4DE\">";
        break;

}

echo "<table><tr><td><br><br><a href=add.php?s=99> Logout </a><br></td></tr></table>";
include_once('footer.php');
?>
