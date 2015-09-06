<?php 
/**
 Plugin to display patient details, possibly split list medications?
**/

$patientDetails = "SELECT PatientID, FName, LName, DOB, Address1, Address2, County, Phone, EMail FROM Patient WHERE PatientID='".$_SESSION['patient']."'";

$results = mysql_query($patientDetails);
if($results){
		while ($result = mysql_fetch_array($results)) {
?>
<table class="info">	
		<tr><th colspan="2" class="infoheader">Personal Information</th></tr>
		<tr><td class="infocol1">ID</td><td class="infocol2"><?php echo("{$result[0]}");?></td></tr>
		<tr><td class="infocol1">First Name</td><td class="infocol2"><?php echo("{$result[1]}");?></td></tr>
		<tr><td class="infocol1">Last Name</td><td class="infocol2"><?php echo("{$result[2]}");?></td></tr>
		<tr><td class="infocol1">Date of Birth</td><td class="infocol2"><?php echo("{$result[3]}");?></td></tr>
		<tr><td class="infocol1" rowspan=3>Address</td><td class="infocol2"><?php echo("{$result[4]}");?></td></tr>
		<tr><td class="infocol2"><?php echo("{$result[5]}");?> </td></tr>
		<tr><td class="infocol2"><?php echo("{$result[6]}");?></td></tr>
		<tr><td class="infocol1">Phone</td><td class="infocol2"><?php echo("{$result[7]}");?></td></tr>
		<tr><td class="infocol1">E-Mail</td><td class="infocol2"><?php echo("{$result[8]}");?></td></tr>
		<?php 
		$medications = "SELECT Medication FROM PatientMedication WHERE PatientID='".$_SESSION['patient']."' GROUP BY Medication";
		$listmeds = mysql_query($medications);
		if($listmeds){ $i=1;?>
		<?php	while($list = mysql_fetch_array($listmeds)){ ?>
		<tr><td class="infocol1"><?php if($i=1){echo("Medication"); $i=0; }?></td><td class="infocol2"><?php echo("{$list[0]}");?></td></tr>
		<?php	}
			mysql_free_result($listmeds);}
		else{
			echo "An error occurred".mysql_error();}
		?>
</table>
<?php }
mysql_free_result($results);
}
else{
	echo "an error occurred".mysql_error();
}
?>