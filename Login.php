<?php
/**
this page redirects you to your relevant home page if you are logged in

tabs are used on this page, the jQuiry UI css was modified slightly so the tabs would fill the span of the table, 
the colour of the select tab was altered so it would match the colour of the form

IPAddresses are recorded along with every login attempt
**/

include "header.php";

$ipaddress = $_SERVER["REMOTE_ADDR"]; //sourcing IP address from client

if(isset($_SESSION['login'])&&isset($_SESSION['type'])){
	if($_SESSION['type']=="p"){
		header("Location: PatientHome.php");
		}
	else if($_SESSION['type']=="d"){
		header("Location: PracHome.php");
		}
	else{
	unset($_SESSION['type']);
	unset($_SESSION['login']);
	}
}
?>
<?php 
if (isset($_POST['SubPat'])){
	$userp=$_POST['patientID'];
	$passp=$_POST['ppassword'];
	$hashp=md5($passp);//this hashes the entry for the password to see if it matches up with the hashed password which is registered.
	$sqlP = "SELECT * FROM Patient WHERE PatientID='$userp' AND Password='$hashp'";
	
	$query1 = mysql_query($sqlP) or die(mysql_error());
	
	$time = date('Y/m/d G:i:s', time());
	
	if ($userp == ""|| $passp == "" || strlen($userp)>20 || strlen($userp)<6 || strlen($passp)>20 || strlen($passp)<6 || mysql_num_rows($query1)==0){
		$failedLog = "INSERT INTO LoginAttempts (IDEntered, PasswordAttempt, IPAddress, Time, Type, Success) 
		VALUES ('{$userp}', '{$passp}', '{$ipaddress}', '{$time}', 'P', 0)";
		$logAttempt = mysql_query($failedLog);
	}
	else{
		$successLog = "INSERT INTO LoginAttempts (IDEntered, PasswordAttempt, IPAddress, Time, Type, Success) 
		VALUES ('{$userp}', '{$passp}', '{$ipaddress}', '{$time}', 'P', 1)";
		$logAttempt = mysql_query($successLog);
		
		$_SESSION['login'] = $userp;
		$_SESSION['type'] = "p";
		$_SESSION['patient'] = $userp;
		header("Location: PatientHome.php");
	}
}
else{
	$userp="";
	$passp="";
}
?>
<h2 class="demoHeaders">Login Form</h2>
<div id="tabs">
	<ul>
		<li><a href="#tabs-1">Patient</a></li>
		<li><a href="#tabs-2">Prescriber</a></li>
	</ul>
	<div id="tabs-1">
		<form action=Login.php method=post name=pat>
		<table class="log">
		<tr class="logrow"><td class="logcol1">Patient ID: </td><td><input type="text" name=patientID value=<?php echo $userp ?>></td></tr>
<?php		
if (isset($_POST['SubPat'])){
	if ($userp == ""|| strlen($userp)>20 || strlen($userp)<6){
		if ($userp == ""){
			echo ("<tr><td colspan=2 class='alertrow'>Please enter a patient ID</td></tr>");
		}
		else if (strlen($userp)>20 || strlen($userp)<6){
			echo ("<tr><td colspan=2 class='alertrow'>Username must be between 6 and 20 characters</td></tr>");
		}
	}
} 
?>
		<tr class="logrow"><td class="logcol1">Password: </td><td><input type=password name=ppassword value=<?php echo $passp ?>></td></tr>
<?php		
if (isset($_POST['SubPat'])){
	if ($passp == "" || strlen($passp)>20 || strlen($passp)<6){
		if ($passp == ""){
			echo ("<tr><td colspan=2 class='alertrow'>Please enter a password</td></tr>");
		}
		else if (strlen($passp)>20 || strlen($passp)<6){
			echo ("<tr><td colspan=2 class='alertrow'>Password must be between 6 and 20 characters</td></tr>");
		}
	}
} 
?>
		<tr><td colspan=2><input type=submit name="SubPat" value="Login as Patient"></td></tr>
<?php		
if (isset($_POST['SubPat'])){
		if (mysql_num_rows($query1)==0){
			echo ("<tr><td colspan=2 class='alertrow'>Please enter a valid username and password</td></tr>");
		}
} 
?>
		</table>
		</form>
	</div>
<?php 
if (isset($_POST['SubPrac'])){
	$userd=$_POST['practitionerID'];
	$passd=$_POST['dpassword'];
	$hashd=md5($passd);
	
	$sqlD = "SELECT * FROM Staff WHERE StaffID='$userd'";
	$query1 = mysql_query($sqlD) or die(mysql_error());
	
	$sqlD2 = "SELECT * FROM Staff WHERE StaffID='$userd' AND Password='$hashd'";
	$query2 = mysql_query($sqlD2) or die(mysql_error());
	
	$time = date('Y/m/d G:i:s', time());
	
	if ($userd == ""|| $passd == "" || strlen($userd)>20 || strlen($userd)<6 || strlen($passd)>20 || strlen($passd)<6 || mysql_num_rows($query1)==0 || mysql_num_rows($query2)==0){
	
		$failedLog = "INSERT INTO LoginAttempts (IDEntered, PasswordAttempt, IPAddress, Time, Type, Success) 
		VALUES ('{$userd}', '{$passd}', '{$ipaddress}', '{$time}', 'D', 0)";
		$logAttempt = mysql_query($failedLog);
		
	}
	else{
		$successLog = "INSERT INTO LoginAttempts (IDEntered, PasswordAttempt, IPAddress, Time, Type, Success) 
		VALUES ('{$userd}', '{$passd}', '{$ipaddress}', '{$time}', 'D', 1)";
		$logAttempt = mysql_query($failedLog);
	
		$_SESSION['practitioner'] = $userd;
		$_SESSION['type'] = "d";
		$_SESSION['login'] = $userd;
		header("Location: PracHome.php");
	}
}
else{
	$userd="";
	$passd="";
	$hashd="";
	
}
?>
	<div id="tabs-2">
		<form action=Login.php method=post name=prac>
		<table class="log">
		<tr class="logrow"><td class="logcol1">Prescriber ID: </td><td><input type="text" name=practitionerID value=<?php echo $userd ?>></td></tr>
<?php
if (isset($_POST['SubPrac'])){
	if ($userd == ""|| strlen($userd)>20 || strlen($userd)<6 || mysql_num_rows($query1)==0){
		if ($userd == ""){
			echo ("<tr><td colspan=2 class='alertrow'>Please enter a username</td></tr>");
		}
		else if (strlen($userd)>20 || strlen($userd)<6){
			echo ("<tr><td colspan=2 class='alertrow'>Username must be between 6 and 20 characters</td></tr>");
		}
		else if (mysql_num_rows($query1)==0){
			echo ("<tr><td colspan=2 class='alertrow'>Please enter a valid username</td></tr>");
		}
	}
}
?>
		<tr class="logrow"><td class="logcol1">Password: </td><td><input type=password name=dpassword value=<?php echo $passd ?>></td></tr>
<?php
if (isset($_POST['SubPrac'])){
	if ($passd == "" || strlen($passd)>20 || strlen($passd)<6 || mysql_num_rows($query2)==0){
		if ($passd == ""){
			echo ("<tr><td colspan=2 class='alertrow'>Please enter a password</td></tr>");
		}
		else if (strlen($passd)>20 || strlen($passd)<6){
			echo ("<tr><td colspan=2 class='alertrow'>Password must be between 6 and 20 characters</td></tr>");
		}
		else if (mysql_num_rows($query2)==0){
			echo ("<tr><td colspan=2 class='alertrow'>Please enter a valid password</td></tr>");
		}
	}
}
?>
		<tr><td colspan=2><input type=submit name="SubPrac" value="Login as Prescriber"></td></tr>
		</table>
		</form>
	</div>
</div>
<?php 

include "footer.php";

?>