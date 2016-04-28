<?php
session_start();
include_once('/var/www/html/project/proj-lib.php');
include_once('header.php');



connect($db);
if(!isset($_SESSION['authenticated'])){
authenticate($db,$postUser,$postPass);
checkauth();
}
echo "</br><span style= \"color : #F08080;text-align:left;font-size:20px;font-family:Arial, Helvetica, sans-serif\"></br>Welcome to University of Colorado Boulder : Connect2College </span></br>";
echo "<div> <img src=http://bands.colorado.edu/colorguard/logo-colorado-university-boulder.gif style=\"float:right\"/> </div>";
icheck($s);
icheck($select_prof);


switch ($s){
default ;
	echo "<style>
	html {
		background-color:#D3D3D3;
	}
	</style>";
	break;
case 4;
	admin_check();
	echo "<form method=post action=add.php>";
	echo "<table><tr><td colspan=2><span style= \"color : #4B0082;font-size:20px;font-family:Arial, Helvetica, sans-serif\">Add professors to the database</span></td></tr>";
	echo "<tr><td><span style= \"color : #4B0082;font-size:15px;font-family:Arial, Helvetica, sans-serif\">Professor Name</span></td><td><input type=\"text\" name=\"profName\"></td></tr>";
	echo "<tr><td><span style= \"color : #4B0082;font-size:15px;font-family:Arial, Helvetica, sans-serif\">Identiti key</span></td><td><input type=\"text\" name=\"profIden\"></td></tr>";
	echo "<tr><td><span style= \"color : #4B0082;font-size:15px;font-family:Arial, Helvetica, sans-serif\">Department Name</span></td><td><input type=\"text\" name=\"deptName\"></td></tr>";
	echo "<tr><td colspan=2><input type=\"hidden\" name=s value=\"5\"><input type=\"submit\" name=\"junk\" value=\"submit\"</td></tr>";
	echo "</table>";
	echo "<style>
        html {
                background-color:#D3D3D3;
        }
        </style>";

	echo "</form>";
	break;
case 5;
	admin_check();
	$profName=mysqli_real_escape_string($db,$profName);
	$deptName=mysqli_real_escape_string($db,$deptName);
	$profIden=mysqli_real_escape_string($db,$profIden);
	if($stmt=mysqli_prepare($db,"INSERT INTO professors set pid='', pname=?, Department=?, identitikey=?"))
	{
		mysqli_stmt_bind_param($stmt,"sss",$profName,$deptName,$profIden);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
	echo "<form method=post action=add.php>";
	echo "<table><tr><td colspan=2><span style= \"color : #4B0082;font-size:20px;font-family:Arial, Helvetica, sans-serif\">Add picture to Professor $ProfName</span></td></tr>\n";
	echo "<tr><td><span style= \"color : #4B0082;font-size:15px;font-family:Arial, Helvetica, sans-serif\"> Professor Picture</span> URL </td><td><input type=\"text\" name=\"pictureURL\"></td></tr>";
	echo "<tr><td colspan=2><input type=\"hidden\" name=s value=\"6\"><input type=\"submit\" name=\"submit\" value=\"submit\"></td></tr>";
	echo "<tr><td><input type=\"hidden\" name=profName value=\"$profName\"</td></tr>";
	if($stmt=mysqli_prepare($db,"select pid from professors where pname=? and Department=? order by pid desc limit 1")){
		mysqli_stmt_bind_param($stmt,"ss",$profName,$deptName);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt,$picd);
		while(mysqli_stmt_Fetch($stmt)){
			$picd=htmlspecialchars($picd);
		}
	mysqli_stmt_close($stmt);
	}
	 echo "<tr><td><input type=\"hidden\" name=picd value=\"$picd\"></td></tr>";
	echo "</table>";
	 echo "<style>
        html {
                background-color:#D3D3D3;
        }
        </style>";

	echo "</form>";
	break;
case 6;
	admin_check();
	$pictureURL=mysqli_real_escape_string($db,$pictureURL);

	if($stmt=mysqli_prepare($db,"insert into pictures set pid='', url=?, frpid=$picd")){
		mysqli_stmt_bind_param($stmt,"s",$pictureURL);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
	echo "<form>";
	echo "<table>";
	echo "<tr><td><a href=add.php> Done Adding Professors</a></td></tr>\n";
	echo "</table>";
	echo "</form>";
	break;
case 7;
	admin_check();
	if($stmt=mysqli_prepare($db,"select pid,pname from professors")){
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt,$profid,$profname);
	}
	echo "<form method=post action=add.php>";
	echo "<table><tr><td colspan=2> <span style= \"color : #4B0082;font-size:20px;font-family:Arial, Helvetica, sans-serif\">Add new Courses to the database</span> </td></tr></br>";
	echo "<tr><td> <span style= \"color : #4B0082;font-size:15px;font-family:Arial, Helvetica, sans-serif\">Add Course Name</span></td><td><input type=\"text\" name=corname></td></tr>";
	echo "<tr><td> <span style= \"color : #4B0082;font-size:15px;font-family:Arial, Helvetica, sans-serif\">Add Course Description</td></span><td> <input type=\"text\" name=cordesc></td></tr>";
 
	echo "<tr><td><span style= \"color : #4B0082;font-size:15px;font-family:Arial, Helvetica, sans-serif\">Assign a Professor to the course </span></td>";
	echo "<td><select name=\"select_prof\" id=\"profchar\">";
	while (mysqli_stmt_fetch($stmt)){
		$profid=htmlspecialchars($profid);
		$profname=htmlspecialchars($profname);
	echo "<option value=\"$profid\"> $profname </option>";
	}
	echo "</select> </td></tr>";
	mysqli_stmt_close($stmt);
	echo "<tr><td><input type=\"submit\" name=\"ADD\" value=\"Add courses to professors\"></td></tr>";
	echo "<tr><td><input type=\"hidden\" name=\"s\" value=\"8\"></td></tr>";
	echo "</table>";
	echo "<style>
        html {
                background-color:#D3D3D3;
        }
        </style>";


	echo "</form>";
	break;
case 8;
	admin_check();
	$corname=mysqli_real_escape_string($db,$corname);
	$cordesc=mysqli_real_escape_string($db,$cordesc);
	
	if($stmt=mysqli_prepare($db,"INSERT INTO courses set cid='', cname=?, Description=?, frcid=?")){
		mysqli_stmt_bind_param($stmt,"sss",$corname,$cordesc,$select_prof);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
	echo "<form>";
	echo "<table>";
	echo "<tr><td><a href=add.php> Done Adding Courses</a><td></tr>\n";
	echo "</table>";
	echo "</form>";
	break;
case 9;
	admin_check();
	echo "<form method=post action=add.php>";
	echo "<table><tr><td colspan=2><span style= \"color : #4B0082;font-size:20px;font-family:Arial, Helvetica, sans-serif\">Add Students to the Database</span></td></tr>";
	echo "<tr><td><span style= \"color : #4B0082;font-size:15px;font-family:Arial, Helvetica, sans-serif\"> Student Name </span></td><td><input type=\"text\" name=\"stname\"></td></tr>";
	echo "<tr><td> <span style= \"color : #4B0082;font-size:15px;font-family:Arial, Helvetica, sans-serif\">Identity Key </span></td><td><input type=\"text\" name=\"stkey\"></td></tr>";
	echo "<tr><td colspan=2> <input type=\"submit\" name=submit></td><td><input type=\"hidden\" name=s value=\"10\"></td></tr>";
	echo "</table>";
	echo "<style>
        html {
                background-color:#D3D3D3;
        }
        </style>";

	echo "</form>";
	break;
case 10;
	admin_check();
	$stname=mysqli_real_escape_string($db,$stname);
	$stkey=mysqli_real_escape_string($db,$stkey);
	if($stmt=mysqli_prepare($db,"INSERT INTO student set sid='',sname=?,identitikey=?")){
		mysqli_stmt_bind_param($stmt,"ss",$stname,$stkey);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
	echo "<form>";
        echo "<table>";
        echo "<tr><td><a href=add.php><span style= \"color : #4B0082;font-size:15px;font-family:Arial, Helvetica, sans-serif\"> Done Adding Students</span></a><td></tr>\n";
        echo "</table>";
	echo "<style>
        html {
                background-color:#D3D3D3;
        }
        </style>";

        echo "</form>";
        break;
case 11;
	admin_check();
	if($stmt=mysqli_prepare($db,"select sid,sname from student")){
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt,$stid,$stname);
        }


	echo "<form method=post action=add.php>";
	echo "<table><tr><td colspan=2> <span style= \"color : #4B0082;font-size:20px;font-family:Arial, Helvetica, sans-serif\">Course Enrollment for students</span> </td>";
	echo "<td><select name=\"select_stud\" id=\"stchar\">";
	while (mysqli_stmt_fetch($stmt)){
		$stid=htmlspecialchars($stid);
		$stname=htmlspecialchars($stname);
	echo "<option value=\"$stid\"> $stname </option>";
	}
	echo "</select></td></tr>";
	mysqli_stmt_close($stmt);
	echo "<tr><td><input type=\"submit\" name=\"select student\" value=\"Enroll Students\"></td></tr>";
	echo "<tr><td><input type=\"hidden\" name=\"s\" value=\"12\"></td></tr>";
	echo "</table>";
	echo "<style>
	html {
                background-color:#D3D3D3;
        }
        </style>";

	echo "</form>";
	break;
case 12;
	admin_check();
	$select_stud=mysqli_real_escape_string($db,$select_stud);
	if ($stmt=mysqli_prepare($db,"select sname from student where sid=?")){
		mysqli_stmt_bind_param($stmt,"s",$select_stud);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt,$tmp_snam);
		}
	while (mysqli_stmt_fetch($stmt)){
		$tmp_snam=htmlspecialchars($tmp_snam);
	}
		
	 mysqli_stmt_close($stmt);	
if ($stmt=mysqli_prepare($db,"select distinct(courses.cid),courses.cname from courses,grades where courses.cid NOT IN (select frcid from grades where frsid=?)")){
	mysqli_stmt_bind_param($stmt,"s",$select_stud);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt,$cid1,$cname1);
	}
	
	echo "<form method=post action=add.php>";
	echo "<table><tr><td colspan=2> <span style= \"color : #4B0082;font-size:15px;font-family:Arial, Helvetica, sans-serif\">Assign courses to $tmp_snam </span> </td>";
	echo "<td><select name=\"select_stud1\" id=\"stchar\">";
	while (mysqli_stmt_fetch($stmt)){
                $cid1=htmlspecialchars($cid1);
                $cname1=htmlspecialchars($cname1);
        echo "<option value=\"$cid1\"> $cname1 </option>";
        }
        echo "</select></td></tr>";
        mysqli_stmt_close($stmt);
	echo "<tr><td><input type=\"submit\" name=\"select student\" value=\"Enroll\"></td></tr>";
        echo "<tr><td><input type=\"hidden\" name=\"s\" value=\"13\"></td></tr>";
	echo "<tr><td><input type=\"hidden\" name=\"select_stud\" value=\"$select_stud\"></td></tr>";
        echo "<tr><td><input type=\"hidden\" name=\"tmp_snam\" value=\"$tmp_snam\"></td></tr>";
	echo "<tr><td><a href=add.php> Done</a><td></tr>\n";
	echo "</table>";
	echo "<style>
        html {
                background-color:#D3D3D3;
        }
        </style>";

	echo "</form>";
	
        break;
case 13;
	admin_check();
	$select_stud=mysqli_real_escape_string($db,$select_stud);
	$select_stud1=mysqli_real_escape_string($db,$select_stud1);	
	if($stmt=mysqli_prepare($db,"INSERT INTO grades set gid='', frsid=?, frcid=?")){
		mysqli_stmt_bind_param($stmt,"ss",$select_stud,$select_stud1);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt,$select_stud,$select_stud1);
		mysqli_stmt_close($stmt);
	}
	if ($stmt=mysqli_prepare($db,"select distinct(courses.cid),courses.cname from courses,grades where courses.cid NOT IN (select frcid from grades where frsid=?)")){
        mysqli_stmt_bind_param($stmt,"s",$select_stud);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt,$cid1,$cname1);
        }

        echo "<form method=post action=add.php>";
        echo "<table><tr><td colspan=2> <span style= \"color : #4B0082;font-size:15px;font-family:Arial, Helvetica, sans-serif\">Assign courses to $tmp_snam </span></td>";
	echo "<td><select name=\"select_stud1\" id=\"stchar\">";
        while (mysqli_stmt_fetch($stmt)){
                $cid1=htmlspecialchars($cid1);
                $cname1=htmlspecialchars($cname1);
        echo "<option value=\"$cid1\"> $cname1 </option>";
        }
        echo "</select></td>";
        mysqli_stmt_close($stmt);
	 echo "<tr><td><input type=\"submit\" name=\"select student\" value=\"Enroll\"></td></tr>";
        echo "<tr><td><input type=\"hidden\" name=\"s\" value=\"12\"></td></tr>";
        echo "<tr><td><input type=\"hidden\" name=\"select_stud\" value=\"$select_stud\"></td></tr>";

        echo "<tr><td><a href=add.php> Done</a><td></tr>\n";
        echo "</table>";
	echo "<style>
        html {
                background-color:#D3D3D3;
        }
        </style>";

        echo "</form>";
	break;
case 90;
	admin_check();
        echo "<form method=post action=add.php>";
        echo "<table><tr><td colspan=2> <span style= \"color : #4B0082;font-size:15px;font-family:Arial, Helvetica, sans-serif\">Add users to Connect2College </span></td></tr>";
        echo "<tr><td><span style= \"color : #4B0082;font-size:15px;font-family:Arial, Helvetica, sans-serif\">Identitikey: </span></td><td><input type=text name=newUser value=\"\"> </td></tr>";
        echo "<tr><td><span style= \"color : #4B0082;font-size:15px;font-family:Arial, Helvetica, sans-serif\">Password: </span></td><td><input type=password name=newPass value=\"\"> </td></tr>";
        echo "<tr><td><span style= \"color : #4B0082;font-size:15px;font-family:Arial, Helvetica, sans-serif\">Email : </span></td><td><input type=text name=newEmail value=\"\"> </td></tr>";
echo "<tr><td> <span style= \"color : #4B0082;font-size:15px;font-family:Arial, Helvetica, sans-serif\">Type of Person</span></td><td><span style= \"color : #4B0082;font-size:15px;font-family:Arial, Helvetica, sans-serif\"><input type=\"radio\" name=\"type_person\" value=\"professor\">Professor<input type=\"radio\" name=\"type_person\" value=\"student\">Student </span></td></tr>";
        echo "<tr><td colspan=2><input type=hidden name=s value=\"91\"><input type=submit name=submit value=\"submit\"></td></tr>";
        echo "</table>";
	 echo "<style>
        html {
                background-color:#D3D3D3;
        }
        </style>";

        echo "</form>";
        break;
case 91;
	admin_check();
        $salt=hash('sha256',$newUser);
        $pass_hash=hash('sha256',$newPass.$salt);
        $newUser=mysqli_real_escape_string($db,$newUser);
        $newPass=mysqli_real_escape_string($db,$pass_hash);
        $newEmail=mysqli_real_escape_string($db,$newEmail);
	$type_person=mysqli_real_escape_string($db,$type_person);
	if($stmt=mysqli_prepare($db,"INSERT into users set userid='', ukey=?, password=?, salt=?, email=?, categ=?")){
		mysqli_stmt_bind_param($stmt,"sssss",$newUser,$newPass,$salt,$newEmail,$type_person);
		 mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
	echo "Added new user with Identiti Key $newUser <br>";
	break;
case 92;
	 admin_check();
        echo "<table><tr><td> <b><u><span style=\"color : #4B0082;font-size:20px;font-family:Arial, Helvetica, sans-serif\"> Users </span></b></u></td><td> <b><u><span style=\"color : #4B0082;font-size:20px;font-family:Arial, Helvetica, sans-serif\"> Type of Login </span></b></u></td></tr>\n";
        if($stmt=mysqli_prepare($db,"select ukey,categ from users")){
        //      mysqli_stmt_bind_param($stmt);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt,$unam,$categ_type);
                while(mysqli_stmt_fetch($stmt)){
                        $unam=htmlspecialchars($unam);
			$categ_type=htmlspecialchars($categ_type);
                        echo "<tr><td> <span style=\"color : #4B0082;font-size:18px;font-family:Arial, Helvetica, sans-serif\"> $unam </span></td> <td><span style=\"color : #4B0082;font-size:18px;font-family:Arial, Helvetica, sans-serif\"> $categ_type </span></td> </tr>\n";
                }
                 mysqli_stmt_close($stmt);
        }
        echo "</table>";
	        echo "<style>
        html {
                background-color:#D3D3D3;
        }
        </style>";


        break;

case 93;
	 admin_check();
echo "<table><tr><td colspan=2> <b><u> <span style=\"color : #4B0082;font-size:20px;font-family:Arial, Helvetica, sans-serif\">Failed Logs/Logins </span> </b></u><b><u><span style=\"color : #4B0082;font-size:20px;font-family:Arial, Helvetica, sans-serif\"> No.of attempts</span> </b></u></td></tr>\n";
        if($stmt=mysqli_prepare($db,"select ip,count(ip) from login where action='fail' GROUP BY ip")){
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt,$ipa,$cnt_ipa);
        while(mysqli_stmt_fetch($stmt)){
                $ipa=htmlspecialchars($ipa);
                $cnt_ipa=htmlspecialchars($cnt_ipa);
                echo "<tr><td> <span style=\"color : #4B0082;font-size:18px;font-family:Arial, Helvetica, sans-serif\">$ipa</span></td><td><span style=\"color : #4B0082;font-size:18px;font-family:Arial, Helvetica, sans-serif\">$cnt_ipa</span></td></tr>\n";
                }
                mysqli_stmt_close($stmt);
        }
        echo "</table>";
	echo "<style>
        html {
                background-color:#D3D3D3;
        }
        </style>";


	break;
	


case 99;
	logout();
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
