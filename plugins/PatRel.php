<?php
//lists out the details of the relationship between the patient and practitioner. 
//also lists out a verification for if the relationship verification value is set to 0 (ie boolean false)

if($_SESSION['practitioner']!="blank"){
if(isset($_POST['verRel'])){
$verRSQL="UPDATE UserAccess SET Verified=1 WHERE PatientID={$_SESSION['patient']} AND StaffID={$_SESSION['practitioner']}";
$verSQL=mysql_query($verRSQL);
}

$findDetails = "SELECT ua.PatientID, ua.StaffID, ua.Date, ua.Verified, ua.Relationship FROM UserAccess AS ua
				WHERE ua.PatientID={$_SESSION['patient']} AND ua.StaffID={$_SESSION['practitioner']}";

$relSQL = mysql_query($findDetails);
if($relSQL){
	while($row = mysql_fetch_array($relSQL)){
		echo "
		<table class=info>
		<tr><th colspan=2 class=infoheader>Relationship</td></tr>
		<tr><td class=infocol3>Association</td><td class=infocol4>{$row[4]}</td></tr>
		<tr><td class=infocol3>Date Established</td><td class=infocol4>{$row[2]}</td></tr>";
		if($row[3]==0){
			echo"<tr><td colspan=2 style='background:red;'>This relationship has yet to be verified. ";
			if($_SESSION['type']=="p"){
				echo "<form method=post name=estRel action='{$action}'>
						Do you wish to verify?</td></tr>
						<tr><td colspan=2 style='background:red;'><input type=submit value='Verify Relationship' name='verRel'></td></tr>";
			}
			else{
			echo"</td></tr>";}
		}
		echo "</table>";
	}
mysql_free_result($relSQL);}
else {
	echo "an error occurred".mysql_error();}
}
?><br>