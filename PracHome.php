<?php
include "header.php";

if(!isset($_SESSION['login']) || !isset($_SESSION['type'])){
	$_SESSION['errmsg'] = "You cannot access this area without logging in";
	header("Location: Error.php");
}
if(isset($_SESSION['type'])){
	if($_SESSION['type'] != "d"){
		$_SESSION['errmsg'] = "You don't have the access rights for this page";
		header("Location: Error.php");}
		
/*
Uses an accordion to store a large number of options within a small area

There is some code in the header relating to keeping the correct accordion tab open when the page reloads itself 
as it opens the top one by default while it's important to have the correct one open on this page if login fails to verify.

At the moment, this page doesn't let unverified patient's log in through the current patient system, to function as a sort
of reminder that that's kinda important, but that's a really awkward way about it.

If a patient already exists, but isn't currently in a relationship with practitioner, the new patient form will create a new
entry in the user access table, taking the data from the new patient form.
If the patient doesn't exist, they're redirected to the create patient page.
*/
}
?>
<h2 class="demoHeaders">Home Page</h2>
<div id="accordion">
	<h3>Staff details:</h3>
	<div><?php include "plugins/PracDetails.php"; 
	//grabs the practitioner details, as they are also needed on the patients, I stored them in an external file?></div>
	<h3>Current Patient:</h3>
	<div>
		<?php //current patient php code
if(isset($_SESSION['patient'])){
 include "plugins/PatientDetails.php";
 ?>
<h4>
 If you wish to access another patient, please cease activity with the patient you currently have access to<h4>
<?php
}
	else{
	if (isset($_POST['CurPat'])){
		if(isset($_POST['NewPat'])){
			unset($_POST['NewPat']);
		}
		
		$cpid = $_POST['cpid'];
		$cpname = $_POST['cpname'];
		$cpidErrors = "";
		$cpnameErrors = "";

		$checkAccess1 = "SELECT * FROM UserAccess WHERE PatientID='".$cpid."' AND StaffID='".$_SESSION['login']."'";
		$sqlC1 = mysql_query($checkAccess1);
		$checkAccess2 = "SELECT * FROM UserAccess WHERE PatientID='".$cpid."' AND StaffID='".$_SESSION['login']."'";// AND Verified=1
		$sqlC2 = mysql_query($checkAccess2);
		
		
		$accessPatient1 = "SELECT * FROM Patient WHERE PatientID ='$cpid'";
		$sqlCA1 = mysql_query($accessPatient1);
		$accessPatient2 = "SELECT * FROM Patient WHERE PatientID ='$cpid' AND LName='$cpname' ";
		$sqlCA2 = mysql_query($accessPatient2);
		
		if ($cpid==""||$cpname==""){
				if($cpid==""){
					$cpidErrors = "Please fill in Patient ID";}
				if($cpname==""){
					$cpnameErrors = "Please fill in Surname";}
		}
		else if (strlen($cpid)>20||strlen($cpid)<6||strlen($cpname)>30 || strlen($cpname)<2){
			if(strlen($cpid)>20||strlen($cpid)<6){
				$cpidErrors = "Patient ID must be between 6 and 20 character";}
			if(strlen($cpname)>30 || strlen($cpname)<2){
				$cpnameErrors = "Patient Surname must be between 2 and 30 character";}
		}
		else if (mysql_num_rows($sqlC1)==0||mysql_num_rows($sqlC2)==0){
			$cpidErrors = "There is currently no patient's account with the ID you have entered which you have access rights to. Please check if you have the correct Patient ID entered; if yes, please register the relationship between you and this patient first in the New Patient option. Until the patient verifies this relationship, you will have to continue using the new patient form.";
		}
		else if (mysql_num_rows($sqlC1)==1&&mysql_num_rows($sqlCA2)==0){
			$cpnameErrors = "You have access to the entered ID. However, the name you have entered does not correspond with the ID you have entered";
		}
		else if ((mysql_num_rows($sqlCA1)==1&&mysql_num_rows($sqlCA2)==0)){
			$cpnameErrors = "The name you have entered does not match the ID you have entered.";
		}
		else{
			$_SESSION['patient'] = $cpid;
			header("Location: PracPatient.php");
		}
	}
	else{
		$cpid = "";
		$cpname = "";
		$rltnshp = "";
	}
	?>
			Access Patient
			<form action="PracHome.php" method="post" name="currentP">
			<table class="log">
			<tr class="logrow"><td class="logcol1">Patient ID: </td><td><input type="text" name="cpid" value='<?php echo $cpid ?>'></td></tr>
<?php
if(isset($_POST['CurPat'])){
	if($cpid==""||strlen($cpid)>20||strlen($cpid)<6||mysql_num_rows($sqlC1)==0||mysql_num_rows($sqlC2)==0){
		echo("<tr><td colspan='3' class='alertrow'>{$cpidErrors}</td></tr>");
	}
}
?>			
			<tr class="logrow"><td class="logcol1">Patient's Surname: </td><td><input type="text" name="cpname" value='<?php echo $cpname ?>'></td></tr>
<?php
if(isset($_POST['CurPat'])){
	if($cpname==""||strlen($cpname)>30 || strlen($cpname)<2||(mysql_num_rows($sqlC1)==1&&mysql_num_rows($sqlCA2)==0)||(mysql_num_rows($sqlCA1)==1&&mysql_num_rows($sqlCA2)==0)){
		echo("<tr><td colspan='3' class='alertrow'>{$cpnameErrors}</td></tr>");
	}
}			
?>
			<tr><td colspan=2><input name="CurPat" type="submit" value="Access Patient"></td></tr></table></form>
<?php }?>
	</div>
	<h3>New Patient</h3>
	<div>
<?php //newPatient
if(isset($_SESSION['patient'])){
	include "plugins/PatientDetails.php";
 ?><h4>
 If you wish to access another patient, please cease activity with the patient you currently have access to<h4>
<?php
}
else{		

	if (isset($_POST['NewPat'])){
		if(isset($_POST['CurPat'])){
			unset($_POST['CurPat']);
		}
		$npid = $_POST['npid'];
		$npname = $_POST['npname'];
		$rltnshp = $_POST['relationship'];
		$npidErrors = "";
		$npnameErrors = "";
		
		$patientExist = "SELECT PatientID FROM Patient WHERE PatientID='".$npid."'";
		$patientRel = "SELECT PatientID FROM UserAccess WHERE PatientID='".$npid."' AND StaffID='".$_SESSION['login']."' AND Verified=1";
		$proceed = "SELECT * FROM Patient WHERE PatientID ='".$npid."' AND LName='".$npname."' ";
		
		$sqlN1 = mysql_query($patientExist);
		$sqlN2 = mysql_query($patientRel);
		$sqlN3 = mysql_query($proceed);
		if($npid==""||$npname==""||$rltnshp=="blank"||strlen($npid)>20||strlen($npid)<6|| strlen($npname)>30 || strlen($npname)<2||
		(!isset($_POST['check']))||(mysql_num_rows($sqlN2)==1)){
			if ($npid==""||$npname==""||$rltnshp=="blank"){
					if($npid==""){
						$npidErrors = "Please fill in Patient ID";}
					if($npname==""){
						$npnameErrors = "Please fill in Patient Name";}
					if($rltnshp=="blank"){
						$nprelErrors = "Please select the option which best explains your relationship to the patient.";}	
			}
			if (strlen($npid)>20||strlen($npid)<6){
				$npidErrors = "Patient's ID must be between 6 and 20 characters";}
			if(strlen($npname)>30 || strlen($npname)<2){
					$npnameErrors = "The name entered must be between 2 and 30 characters";
			}
			if (!isset($_POST['check'])){
				$npvrfyErrors = "Please get the patient to check this box so you have permission to view and update their records in this session.";
			}
			if (mysql_num_rows($sqlN2)==1){
				$npidErrors = "This patient already has a verified relationship with you, please use the current patient login.";
			}
		}
		else if(mysql_num_rows($sqlN3)==0 && mysql_num_rows($sqlN1)!=0){
			$npnameErrors = "The name you have entered does not match the ID you have entered";
			}
		else if (mysql_num_rows($sqlN1)==0){
				header("Location: NewPatient.php"); //create new patient form is loaded
		}
		else{
			$_SESSION['patient'] = $npid;
			
			$_newRel = "INSERT INTO UserAccess(PatientID, DoctorID, Date, Verified, Relationship) VALUES ('".$npid."', '".$_SESSION['login']."', now(), 0, '".$rltnshp."')";
			$newRel=mysql_query($_newRel);
			
			header("Location: PracPatient.php");
		}
	}
	else{
		$npid = "";
		$npname = "";
		$rltnshp = "";
	}
?>
		<form action="PracHome.php" method="post" name="newP">
		<table class="log">
		<tr class="logrow"><td class="logcol1">Patient ID: </td><td><input type="text" name="npid" value='<?php echo $npid ?>'></td></tr>
<?php
	if(isset($_POST['NewPat'])){
		if($npid==""||strlen($npid)>20||strlen($npid)<6||(mysql_num_rows($sqlN2)==1)){
			echo("<tr><td colspan='3' class='alertrow'>{$npidErrors}</td></tr>");
		}
	}
?>				
		<tr class="logrow"><td class="logcol1">Patient's Surname: </td><td><input type="text" name="npname" value='<?php echo $npname ?>'></td></tr>	
<?php
	if(isset($_POST['NewPat'])){
		if($npname==""||strlen($npname)>30 || strlen($npname)<2||(mysql_num_rows($sqlN3)==0 && mysql_num_rows($sqlN1)!=0)){
			echo("<tr><td colspan='3' class='alertrow'>{$npnameErrors}</td></tr>");
		}
	}			
?>	
		<tr class="logrow"><td class="logcol1">Relationship: </td><td>
				<select name="relationship">
					<option value="blank">Select a relationship</option>
					<option <?php if($rltnshp=="GP"){echo("selected='selected'");}?> value="GP">GP</option>
					<option <?php if($rltnshp=="Consultant"){echo("selected='selected'");}?> value="Consultant">Consultant</option>
					<option <?php if($rltnshp=="Care Co-Ordinator"){echo("selected='selected'");}?> value="Care Co-Ordinator">Care Co-ordinator</option>
					<option <?php if($rltnshp=="Other"){echo("selected='selected'");}?> value="Other">Other</option>
				</select></td></tr>
<?php
	if(isset($_POST['NewPat'])){
		if($rltnshp=="blank"){
			echo("<tr><td colspan='3' class='alertrow'>{$nprelErrors}</td></tr>");
		}
	}			
?>	
		<tr class="logrow"><td colspan=2 style="font-size:12px; text-align:right;">I agree to let <?php echo $docname?> temporarily access my records and record new entries <input type=checkbox name="check" value=1></td></tr>
<?php
	if(isset($_POST['NewPat'])){
		if(!isset($_POST['check'])){
			echo("<tr><td colspan='3' class='alertrow'>{$npvrfyErrors}</td></tr>");
		}
	}			
?>	
		<tr><td colspan=2><input name="NewPat" type="submit" value="Create Relationship"></td></tr>
		</table>
		</form>
<?php 
} 
?>
	</div>
</div>
<?php
include "footer.php";
?>