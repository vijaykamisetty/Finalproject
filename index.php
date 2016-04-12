<?php
include_once('/var/www/html/tmp_proj/proj-lib.php');
include_once('header.php');


connect($db);

switch($s){
case 0;
default;
	echo "This will be updatd soon \n";
	break;
case 50;
	echo "in 50 loop";
	echo "<table><tr><td colspan=2><b><u>Professors </b></u></td></tr>\n";
	$query="select pid,pname from professors";
	$result=mysqli_query($db,$query);
	while($row=mysqli_fetch_row($result)){
		echo "<tr><td><a href=index.php?pid=$row[0]&s=51>$row[1]</a></td>";
	}
echo "</table>";
	break;
case 51;
	echo "<table><tr><td> <b><u>Courses</b></u></td></tr>\n";
	$pid=mysqli_real_escape_string($db,$pid);
	if($stmt=mysqli_prepare($db,"select cname from courses,professors where courses.frcid=professors.pid and pid=?")){
		mysqli_stmt_bind_param($stmt,"i",$pid);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt,$cname);
		while(mysqli_stmt_fetch($stmt)){
			$cname=htmlspecialchars($cname);
			echo "<tr><td>$cname</tr></td>";
		}
		
		mysqli_stmt_close($stmt);
	}
	echo "</table>";
	break;
}
	

?>

