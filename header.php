<?php
session_start(); //session start located at the top of the header so that each page also contains it there.

ob_start(); //output buffers required on each page to prevent errors being caused by pages containing multiple php head redirectors 
//(ie. header("Location: webpage.php");) //ob_flush() located in footer.


//database accessed on header so it is accessed on every page
mysql_connect("danu2.it.nuigalway.ie", "mydb1057", "mydb105730") or die("connection failure");
mysql_select_db("mydb1057");

if(isset($_POST['logout'])){
	header("Location: Logout.php");}
if(isset($_POST['viewP'])){
	header("Location: PracPatient.php");}
if(isset($_POST['exit'])){
	unset($_SESSION['patient']);
	unset($_SESSION['verified']);
	header("Location: PracHome.php");}
?>
<html>
<head>

	<title>The Checkup Passport</title>
<!-- Would be very easy to include the conditions to change the CSS values here for a differently designed layout
alternatively, you could just have two different sets of headers and footers with completely different designs depending on
the type of screen viewing it 

The two css files here should really just be compiled together. It's almost all within the original file.-->
	<link rel="stylesheet" href="website2.css"/>
	<link rel="stylesheet" type="text/css" href="GeneralLayout.css">
	<link href="css/ui-lightness/jquery-ui-1.10.0.custom.css" rel="stylesheet">
	<script src="js/jquery-1.9.0.js"></script>
	<script src="js/jquery-ui-1.10.0.custom.js"></script>
	<script>
	$(function() {
		$( "#tabs" ).tabs(
			<?php
			if (isset($_POST['SubPrac'])){
				echo("{active:1}");
			}
			?>);
		
		$( "#accordion" ).accordion(
			<?php 	
			/*
			This code refers to the practitioner page's patient access forms, as the
			Accordion opens the top div by default, this code adds in the relevant 
			javascript code to open the set form
			*/
			if(isset($_POST['CurPat'])){echo("{active:1}");}
					if(isset($_POST['NewPat'])){echo("{active:2}");} ?>);
		
		});
		
	<?php
		if(isset($_SESSION['medication'])){
			if($_SESSION['medication']=="Clozapine"){
		
		/*
		This code loads the dialogs for the clozapine test and results pages
		*/
		?>

		$(function() {
						
			$( "#bs" ).dialog({
				autoOpen: false,
				width: 400,
				buttons: [
					{
						text: "Ok",
						click: function() {
							$( this ).dialog( "close" );
						}
					},
					{
						text: "Cancel",
						click: function() {
							$( this ).dialog( "close" );
						}
					}
				]
			});
			$( "#bs-link" ).click(function( event ) {
				$( "#bs" ).dialog( "open" );
				event.preventDefault();
			});		
			
			$( "#bl" ).dialog({
				autoOpen: false,
				width: 400,
				buttons: [
					{
						text: "Ok",
						click: function() {
							$( this ).dialog( "close" );
						}
					},
					{
						text: "Cancel",
						click: function() {
							$( this ).dialog( "close" );
						}
					}
				]
			});
			// Link to open the dialog
			$( "#bl-link" ).click(function( event ) {
				$( "#bl" ).dialog( "open" );
				event.preventDefault();
			});			
			
			$( "#fbc" ).dialog({
				autoOpen: false,
				width: 400,
				buttons: [
					{
						text: "Ok",
						click: function() {
							$( this ).dialog( "close" );
						}
					},
					{
						text: "Cancel",
						click: function() {
							$( this ).dialog( "close" );
						}
					}
				]
			});
			// Link to open the dialog
			$( "#fbc-link" ).click(function( event ) {
				$( "#fbc" ).dialog( "open" );
				event.preventDefault();
			});			
			
			$( "#lft" ).dialog({
				autoOpen: false,
				width: 400,
				buttons: [
					{
						text: "Ok",
						click: function() {
							$( this ).dialog( "close" );
						}
					},
					{
						text: "Cancel",
						click: function() {
							$( this ).dialog( "close" );
						}
					}
				]
			});
			// Link to open the dialog
			$( "#lft-link" ).click(function( event ) {
				$( "#lft" ).dialog( "open" );
				event.preventDefault();
			});		
			
			$( "#urea" ).dialog({
				autoOpen: false,
				width: 400,
				buttons: [
					{
						text: "Ok",
						click: function() {
							$( this ).dialog( "close" );
						}
					},
					{
						text: "Cancel",
						click: function() {
							$( this ).dialog( "close" );
						}
					}
				]
			});
			// Link to open the dialog
			$( "#urea-link" ).click(function( event ) {
				$( "#urea" ).dialog( "open" );
				event.preventDefault();
			});			
			
			$( "#ecg" ).dialog({
				autoOpen: false,
				width: 400,
				buttons: [
					{
						text: "Ok",
						click: function() {
							$( this ).dialog( "close" );
						}
					},
					{
						text: "Cancel",
						click: function() {
							$( this ).dialog( "close" );
						}
					}
				]
			});
			// Link to open the dialog
			$( "#ecg-link" ).click(function( event ) {
				$( "#ecg" ).dialog( "open" );
				event.preventDefault();
			});			
			
			$( "#bp" ).dialog({
				autoOpen: false,
				width: 400,
				buttons: [
					{
						text: "Ok",
						click: function() {
							$( this ).dialog( "close" );
						}
					},
					{
						text: "Cancel",
						click: function() {
							$( this ).dialog( "close" );
						}
					}
				]
			});
			// Link to open the dialog
			$( "#bp-link" ).click(function( event ) {
				$( "#bp" ).dialog( "open" );
				event.preventDefault();
			});	
			
			$( "#pulse" ).dialog({
				autoOpen: false,
				width: 400,
				buttons: [
					{
						text: "Ok",
						click: function() {
							$( this ).dialog( "close" );
						}
					},
					{
						text: "Cancel",
						click: function() {
							$( this ).dialog( "close" );
						}
					}
				]
			});
			// Link to open the dialog
			$( "#pulse-link" ).click(function( event ) {
				$( "#pulse" ).dialog( "open" );
				event.preventDefault();
			});	
			
			$( "#bmi" ).dialog({
				autoOpen: false,
				width: 400,
				buttons: [
					{
						text: "Ok",
						click: function() {
							$( this ).dialog( "close" );
						}
					},
					{
						text: "Cancel",
						click: function() {
							$( this ).dialog( "close" );
						}
					}
				]
			});
			// Link to open the dialog
			$( "#bmi-link" ).click(function( event ) {
				$( "#bmi" ).dialog( "open" );
				event.preventDefault();
			});
		});
		<?php
			}
		}
		?>
					
	function loopRefresh()
    {//code to refresh the time in the corner every 1000 milleseconds
        setTimeout("showTime();",1000);
		setTimeout("loopRefresh();",1000);
    }
    function showTime()
    {//really awkward clock code here for displaying the time in the corner
	var myDate = new Date();
	var myHours = myDate.getHours();
	var myMinutes = myDate.getMinutes();
	var mySeconds = myDate.getSeconds();
	
	
	if (myHours<10 && myMinutes<10 && mySeconds<10) {
		document.getElementById('clock').innerHTML = "0"+ myHours + ":0" + myMinutes + ":0" + mySeconds;}
	else if (myHours<10 && myMinutes<10) {
		document.getElementById('clock').innerHTML = "0"+ myHours + ":0" + myMinutes + ":" + mySeconds;}
	else if (myHours<10 && mySeconds<10) {
		document.getElementById('clock').innerHTML = "0"+ myHours + ":" + myMinutes + ":0" + mySeconds;}
	else if (myMinutes<10 && mySeconds<10) {
		document.getElementById('clock').innerHTML = myHours + ":0" + myMinutes + ":0" + mySeconds;}
	else if (myMinutes<10) {
		document.getElementById('clock').innerHTML = myHours + ":0" + myMinutes + ":" + mySeconds;}
	else if (mySeconds<10) {
		document.getElementById('clock').innerHTML = myHours + ":" + myMinutes + ":0" + mySeconds;}
	else if (myHours<10) {
		document.getElementById('clock').innerHTML = "0"+ myHours + ":" + myMinutes + ":" + mySeconds;}
	else {
		document.getElementById('clock').innerHTML = myHours + ":" + myMinutes + ":" + mySeconds;}
	}
	</script>
</head>

<body  onload="loopRefresh();"><!--remember to initiate any onloads here-->
	<div id="containerDiv">
	
		<div id="header">
			
			<!-- original circle location -->
			<div id="headerLine">The Checkup Passport<sup>&copy;</sup></div>
			<div id="clock" style="position:absolute; font-size:12px; margin-top:5px; margin-left: 930px;"></div>
		</div>
		
		<div id="tabHeader">
			<ul id="nav">
<?php
if(isset($_SESSION['login'])&&isset($_SESSION['type'])){
	if($_SESSION['type']=="p"){ 
	//tabHeader and nav collectively within the CSS code contain the code to make these menus drop down
	//they're currently quite empty but are mostly included due to a full site likely requiring them ?>
				<li>
					<a href= "PatientHome.php">Your Account</a>
					<ul>
						<li><a href="PatientHome.php">Home</a></li>
						<li><a href="Logout.php">Logout</a></li>
					</ul>
				</li>
	<?php
			if(isset($pastresults)){echo("<li><a href='#'>Test Results</a></li>");}
	}
	if($_SESSION['type']=="d"){ ?>
				<li>
					<a href="PracHome.php">Your Account</a>
					<ul>
						<li><a href="PracHome.php">Home</a></li>
						<li><a href="Logout.php">Logout</a></li>
					</ul>
				</li>
		<?php if(isset($_SESSION['patient'])){ ?>
				<li>
					<a href= "PracPatient.php">Patient No. <?php echo("{$_SESSION['patient']}");?></a>
					<ul>
						<li><a href= "PracPatient.php"> View Patient No. <?php echo("{$_SESSION['patient']}");?></a></li>
						<li><a href="DropPatient.php">Exit Patient</a></li>
					</ul>
				</li>
		<?php	if(isset($pastresults)){echo("<li><a href='#'>Test Results</a></li>");}
		}
	}
}
else{echo"<li><a href='Login.php'>Login</a></li>";}?>
			</ul>			</div>
		<div class="circle">
				<div class="circle1">
					<div class="circle2"></div>
				</div>
			</div>		
<div id="maincontent">
		<div id="sidecontent">
<?php 
if(isset($_SESSION['type'])){
//sidebars loaded on the basis of which session variables are set, they just outline basic info about who's logged in and whatnot
	if($_SESSION['type'] == "p"){
?>
			<div class="rectangle" style='background:#00CDCD;'>
<?php
$side = "SELECT PatientID, FName, LName FROM Patient WHERE PatientID='".$_SESSION['login']."'";
	
$sideSQL = mysql_query($side); 
if($sideSQL){ 
while($result3 = mysql_fetch_array($sideSQL)){
echo("<div style='margin-top: 10px; color:white;'>Patient</div><hr>
	<form action='header.php' method='post' name='logout'>
	<table name='sidedetails' style='font-size:14px; margin:0 auto 0 auto; width: 100%'>
	<tr><td>Name</td><td style='background:white;'>{$result3[1]}</td></tr>
	<tr><td></td><td style='background:white;'>{$result3[2]}</td></tr>
	<tr><td>ID </td><td style='background:white;'>{$result3[0]}</td></tr>
	<tr><td colspan=2><input type=submit name='logout' value='Logout' style='width:100%; font-size:16px;'></td></tr>
	</table>
	</form>
	</div></br>");}}
else{
	echo "an error occurred".mysql_error();}
	
?>	
			
<?php
	}
	if($_SESSION['type'] == "d"){
?>
			<div class="rectangle" style='background:#00CDCD;'>
<?php

$side = "Select StaffID, FName, LName FROM Staff WHERE StaffID='".$_SESSION['login']."'";
	
$sideSQL = mysql_query($side); 
if($sideSQL){ 
while($result2 = mysql_fetch_array($sideSQL)){
echo("<div style='margin-top: 10px; color:white;'>Prescriber</div><hr>
	<form action='header.php' method='post' name='logout'>
	<table name='sidedetails' style='font-size:14px; margin:0 auto 0 auto; width: 100%'>
	<tr><td colspan=2 style='background:white;'>{$result2[1]}</td></tr>
	<tr><td colspan=2 style='background:white;'>{$result2[2]}</td></tr>
	<tr><td style='color:white;'>ID </td><td style='background:white;'>{$result2[0]}</td></tr>
	<tr><td colspan=2><input type=submit name='logout' value='Logout' style='width:100%; font-size:16px;'></td></tr>
	</table>
	</form>
	</div>");}
	}
else{
	echo "an error occurred".mysql_error();}
?>
<?php
if(isset($_SESSION['patient'])){
	$sideP = "Select PatientID, FName, LName FROM Patient WHERE PatientID='{$_SESSION['patient']}'";
		
	$sidePSQL = mysql_query($side); 
	if($sidePSQL){ 
	while($result1 = mysql_fetch_array($sidePSQL)){
	echo("
		<div class='rectangle' style='background:#FFA500;'>
		<div style='margin-top: 10px; color:white;'>Patient Accessed</div><hr>
		<form action='header.php' method='post' name='logout'>
		<table name='sidedetails' style='font-size:14px; margin:0 auto 0 auto; width: 100%'>
		<tr><td colspan=2></td></tr>
		<tr><td  colspan=2 style='background:white;'>{$result1[1]}</td></tr>
		<tr><td colspan=2  style='background:white;'>{$result1[2]}</td></tr>
		<tr><td style='color:white;'>ID </td><td style='background:white;'>{$result1[0]}</td></tr>
		<tr><td colspan=2><input type=submit name='viewP' value='View' style='width:100%; font-size:16px;'></td></tr>
		<tr><td colspan=2><input type=submit name='exit' value='Exit' style='width:100%; font-size:16px;'></td></tr>
		</table>
		</form>
		</div>");}
		}
	else{
		echo "an error occurred".mysql_error();}

		}
	}
}
?>
		</div>
			<div class="rectangle1">