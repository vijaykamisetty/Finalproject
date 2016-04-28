<?php

include_once('/var/www/html/project/proj-lib.php');

include_once('header.php');

echo "<form method=post action=add.php>
	<style>
	html {
                background: url('http://i.imgur.com/v8DVO8o.jpg');
		background-size: cover;
                }
	</style>

	<div style=\"font:30px/30px Arial,tahoma,sans-serif;color:#000080;text-shadow :green\">Connect2College: University of Colorado Boulder</div>
	</br></br><fieldset style= \" width: 280px;background-color:#8FBC8F\"> <font size=5> <font color=#000080>
	<legend style = \"color: #00008B\"> Please tell us who you are !! </legend>
	<table>
        <tr>
	<td width=116><div align=right> <label for=Username>Username: </label></td>
	<td width=177><input type=text name=postUser placeholder=\"Enter your Identity key\" id=Username> </td>
	</tr>
        <tr>
	<td><div align=right>Password: </td> 
	<td><input type=password placeholder=\"Enter your password only\" name=postPass> </td> 
	</tr>
        <tr> 
	<td div align=right><input type=submit name=submit value=Login> </td>
	</tr>
        </table>
	</fieldset>
        </form>";
?>
