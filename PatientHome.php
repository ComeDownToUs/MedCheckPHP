<?php
include "header.php";

$action = "PatientHome.php";
/**
this page lists out 
**/

if(!isset($_SESSION['login']) || !isset($_SESSION['type'])){
	$_SESSION['errmsg'] = "You cannot access this area without logging in";
	header("Location: Error.php");
}
if(isset($_SESSION['type'])){
	if($_SESSION['type'] != "p"){
		$_SESSION['errmsg'] = "You don't have the access rights for this page";
		header("Location: Error.php");}
}
if(isset($_SESSION['practitioner'])){
	unset($_SESSION['practitioner']);
}
$findUnV = "SELECT ua.Relationship, s.FName, s.LName, s.Location FROM UserAccess AS ua 
LEFT JOIN Staff AS s ON ua.StaffID=s.StaffID WHERE ua.Verified=0 AND ua.PatientID={$_SESSION['patient']}";
$unvSQL = mysql_query($findUnV);
?>

	<h3>Patient Home</h3>
<div id="accordion">
<?php
if(mysql_num_rows($unvSQL)!=0){
//the notifications tab only appears if there are any, as it is the top of the page, it stays open as default
//this could contain numerous other relevant details, but at the moment only specifies unverified relationships
//the reason for the verification on the patient's side is that there is a trust placed in doctors to ensure they dont
//place information into patients they are not dealing with
//the security tables in the database were also going to give notifications as to if people were attempting to hack an account or whatever
?>	<h3>Notifications</h3>
	<div>
<?php

if($unvSQL){
	echo("The following relationships await verification:<ul>");
	while($row = mysql_fetch_array($unvSQL)){
		echo"<li>{$row[1]} {$row[2]} - {$row[3]} - {$row[0]}</li>";
	}
	echo("</ul> Relationships can be verified within the Practitioner's details page.");
	mysql_free_result($unvSQL);
	}
else{
	echo "an error occurred ".mysql_error();}

?>


	</div>
<?php } ?>
	<h3>Patient Details:</h3>
	<div>
	<?php //used again in the PracPatient page
		include "plugins/PatientDetails.php"; 
	?>
	</div>
	<h3>Prescriber Details:</h3>
	<div>
<?php	//used again in the PatientPrac page
	include "plugins/SelectPrac.php";?>
	</div>
	<h3>View Records:</h3>
	<div>
	<?php //find results form, also used on the doctor's side so a plug-in
		include "plugins/PatientResults.php";
	?>
	</div>
</div>

<?php //footer
	include "footer.php";
?>
