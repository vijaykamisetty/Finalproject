
<?php
include_once('/var/www/html/project/proj-lib.php');
include_once('header.php');

connect($db);
icheck($s);
icheck($pid);
switch($s){
case 0;
default;
	echo " 
		<p>
			<img src=http://www.centerbrook.com/media/projects/university_of_colorado_at_boulder_center_for_community/large/1.jpg style=\"float:right\"/>
			 <table><td><span style= \"color : #00008B;text-align:left;font-size:20px;font-family:Arial, Helvetica, sans-serif\"></br>
	The University of Colorado Boulder (UCB, commonly referred to as CU, Boulder, CU-Boulder, or Colorado) is a public research university located in Boulder, Colorado, United States. It is the flagship university of the University of Colorado system and was founded five months before Colorado was admitted to the union in 1876.</br>	
		</p>";
echo "</br> Located at the foot of the Rocky Mountains, the University of Colorado Boulder has an awe-inspiring view from campus. Our world-renowned faculty maintain a reputation for excellence in research, creative work and teaching across 150 academic fields. From hands-on learning to close connections with dedicated faculty, CU-Boulder prepares students to become leaders within Colorado and throughout the world </span></td></br>";
	echo "</table>";
	
	echo"	<style>
	{ margin: 0; padding: 0; }

	html {
		background: url('0f455b5a-a500-4e2a-b5c7-66012c30eff5.jpg');
		 background-size: cover;
		}	
	body{   
    margin:0 auto; 
 
	}
	content{   
	        width:100%; 
        	background-color:#EBEBEB;
	        margin:0 auto; 
        	padding:20px;
	}



	</style>";
	break;
case 50;
        echo "<table><tr><td colspan=2><b><u><span style= \"color : #4B0082;font-size:20px;font-family:Arial, Helvetica, sans-serif\">Professors</span> </b></u></td></tr>\n";
        $query="select professors.pid,pname,url from professors,pictures where pictures.frpid=professors.pid";
        $result=mysqli_query($db,$query);
        while($row=mysqli_fetch_row($result)){
                echo "<tr><td><a href=index.php?pid=$row[0]&s=51><span style= \"color : #4B0082;font-size:15px;font-family:Arial, Helvetica, sans-serif\">$row[1]</span></a></td><td><p><img src=$row[2]></p></td></tr>\n";
        }
        echo "</table>";
	 echo"   <style>
        { margin: 0; padding: 0; }
	 html {
                background-color:#00CED1;
        }

	body{
	    margin:0 auto;
	}
	</style>";
        break;

case 51;
	
	echo "<table><tr><td> <b><u><span style= \"color : #4B0082;font-size:20px;font-family:Arial, Helvetica, sans-serif\">Courses</span></b></u></td></tr>\n";
	$pid=mysqli_real_escape_string($db,$pid);
	if($stmt=mysqli_prepare($db,"select cname,description from courses,professors where courses.frcid=professors.pid and pid=?")){
		mysqli_stmt_bind_param($stmt,"i",$pid);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt,$cname,$cdesc);
		while(mysqli_stmt_fetch($stmt)){
			$cname=htmlspecialchars($cname);
			$cdesc=htmlspecialchars($cdesc);
			echo "<tr><td><span style= \"color : #4B0082;font-size:15px;font-family:Arial, Helvetica, sans-serif\">$cname</span></td> <td><span style= \"color : #4B0082;font-size:15px;font-family:Arial, Helvetica, sans-serif\">$cdesc</span></td></tr>";
		}
		
		mysqli_stmt_close($stmt);
	}
//	echo "<div> <img src=https://s-media-cache-ak0.pinimg.com/custom_covers/216x146/366762032092964386_1435772073.jpg style=\"float:left\"/> </div>";
	echo "</table>";
	 echo "<style>
        { margin: 0; padding: 0; }

         html {
		 background: url('https://honorcode.colorado.edu/sites/default/files/Page1.jpg');
                 background-size: cover;
		
        }

        </style>";

	break;
}

include_once('footer.php');	

?>

