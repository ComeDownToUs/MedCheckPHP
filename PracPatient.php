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
}


if(isset($_POST['verify'])){
	$_SESSION['verified'] = "yes";
	header("Location: PracPatient.php");
}
if(isset($_POST['exit'])){
	$_SESSION['verified'] = "no";
	unset($_SESSION['patient']);
	header("Location: PracHome.php");
}

$action="PracPatient.php";
?>
<?php //verify patient first

if(!isset($_SESSION['verified'])||($_SESSION['verified']=="no")){
//this verification page appears first to ensure that the patient is the correct one.

include "plugins/PatientDetails.php";//Patient Info form
?>
<form method="post" action="PracPatient.php" name="verification">
<table class=log>
<tr><td colspan=2>Are these the correct details for the patient</td></tr>
<tr><td style="width:50%"><input type=submit name=verify value=Yes></td><td><input type=submit name=exit value=No></td></tr>
</table>
<?php
}
else if($_SESSION['verified']=="yes"){


/*
Uses an accordion to store a large number of options within a small area

There is some code in the header relating to keeping the correct accordion tab open when the page reloads itself 
as it opens the top one by default.
*/
?>
<h2 class="demoHeaders">Patient Page</h2>
<div id="accordion">
	<h3>Patient Details</h3>
	<div><?php //patient details
			include "plugins/PatientDetails.php"; 
		?>
	</div>
	<h3>Patient Records</h3>
	<div>View Past Results</br>
	<?php include "plugins/PatientResults.php"; ?></div>
	<h3>Record New Results</h3>
	<div>
	
			<?php		
		$patientMeds = mysql_query("SELECT p.Medication FROM PatientMedication AS p WHERE p.PatientID='{$_SESSION['patient']}'");
		
		if(mysql_num_rows($patientMeds)>0){
			$medL = "SELECT m.Medication 
			FROM Medications AS m 
			WHERE m.Medication !='Cloz'
			GROUP BY m.Medication";
			$medQ = mysql_query($medL) or die(mysql_error());}
		else{
			$medL = "SELECT m.Medication FROM Medications AS m
			GROUP BY m.Medication";
			$medQ = mysql_query($medL) or die(mysql_error());
		}
			if((mysql_num_rows($medQ))!=0){
		?>
		Add Medications to Patient Account</br>
		<form name="manageMedication" method="post" action="PracPatient.php">
		<table class="log">
		<tr class="logrow"><td class="logcol1">Add Medication to Records</td>
			<td>
				<select name="medType">
					<option value="blank">Select a type</option>
				<?php 
						if ($medQ) {
							while ($row = mysql_fetch_array($medQ)) {
								echo "<option value='{$row[0]}'>{$row[0]}</option>";}
							mysql_free_result($medQ);}
						else {
							echo "an error occurred".mysql_error();}
				?>
				</select></td><tr><td colspan=2><input type="submit" name="AddMed" value="Add to Patient"></td></tr>
		<?php 
			if(isset($_POST['AddMed'])){
			$med=$_POST['medType'];
				if($med!="blank"){
	//				$noMed = "SELECT Medication FROM PatientMedication WHERE Medication='{$med}' AND PatientID='{$_SESSION['patient']}'";
		//			$nomedSQL = mysql_query($noMed);
			//		if(mysql_num_rows($nomedSQL)==0){
						$addMed="INSERT INTO PatientMedication (PatientID, Medication) VALUES('".$_SESSION['patient']."', '".$med."')";
						$add = mysql_query($addMed);
					}
				//}
			}
			else{
			$med="";}
		?>
		</table>
		</form>
		<?php 	
		}
		else{	
		echo("This patient currently has all medications in the system listed among their records.");} ?>
	
	
		Record New Test Results</br>
		<?php //record new result
		if(isset($_POST['findMeds'])){
			$medication = $_POST['medType'];
			if($medication!="blank"){
				$_SESSION['medication'] = $medication;
					header("Location: Tests.php");
			}
		}
		else{
			$medication = "";
		}
		?>
		<form name="recMedication" method="post" action="<?php echo($action);?>">
		<table class="log">
		<tr class="logrow"><td class="logcol1">Medication Type</td><td><select name="medType">
				<option value="blank">Select a type</option>
				<?php 
					$medList = "SELECT Medication FROM PatientMedication WHERE PatientID='".$_SESSION['patient']."' GROUP BY Medication";
					
					$medQuery = mysql_query($medList);
						if ($medQuery) {
							while ($row = mysql_fetch_array($medQuery)) {
								echo "<option value='{$row[0]}'>{$row[0]}</option>/n";}
							mysql_free_result($medQuery);}
						else {
							echo "an error occurred".mysql_error();}
					
				?>
			</select></td></tr>
			<tr><td colspan=2><input type="submit" name="findMeds" value="List Tests"> 
		</td></tr>
		</table>
		</form>
		</br>
	</div>
</div>
<?php
} 
?>
<?php



if(!isset($_SESSION['patient'])){
	$_SESSION['errmsg'] = "You cannot access this area without accessing a patient's account";
	header("Location: Error.php");
}

include "footer.php";
?>