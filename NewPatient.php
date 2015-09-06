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

if (isset($_POST['create'])){
	$pid = $_POST['patientID'];
	$pname1 = $_POST['pnames1'];
	$pname2 = $_POST['pnames2'];
	$date = $_POST['pdate'];
	$month = $_POST['pmonth'];
	$year = $_POST['pyear'];
	$paddress1 = $_POST['paddress1'];
	$paddress2 = $_POST['paddress2'];
	$pcounty = $_POST['pcounty'];
	$pphone = $_POST['pphoneNo'];
	$pemail = $_POST['pemail'];
	$ppassword = $_POST['ppassword'];
	$p2 = $_POST['ppassword2'];
	$dob = "{$year}-{$month}-{$date}";
	$dob1 = date("Y-m-d", strtotime($year."-".$month."-".$date));//doesn't work but runs, at least
	$rltnshp = $_POST['relationship'];
	if($date<10){$datee = "0".$date;}
	else{$datee = $date;}
	if($month<10){$monthh = "0".$month;}
	else{$monthh=$month;}

		
	$errID = "";
	$errFName = "";
	$errSName = "";
	$errAddr = "";
	$errAddr2 = "";
	$errCo = "";
	$errPh = "";
	$errPass = "";
	$errPass2 = "";
	$errRel = "";
	$errEmail = "";
	
mysql_connect("danu2.it.nuigalway.ie", "mydb1057", "mydb105730") or die("connection failure");
mysql_select_db("mydb1057");
	
	$checkID = "SELECT * FROM Patient WHERE PatientID='".$pid."'";
	$checkQuery = mysql_query($checkID);
	
	//list of isset checks to record variables of medications
	if(isset($_POST['clozapine'])){
		$medication = "clozapine";
		$sqll = "query to insert new relationship";}
	
	
	if ($pid==""||$pname1==""||$pname2==""||$paddress1==""||$pcounty=="blank"||$pphone==""||$pemail==""||$ppassword==""||$rltnshp=="blank"||$year=="YYYY"||$month=="MM"||$date=="DD"){
		if ($pid==""){$errID="Please enter a Patient ID";}
		if ($pname1==""){$errFName = "Please enter a first name";}
		if ($pname2==""){$errSName = "Please enter a surname";}
		if (($year=="YYYY"||$month=="MM"||$date=="DD")){$errDOB = "Please enter a date of birth";}
		if ($paddress1==""){$errAddr="Please enter an address";}
		if ($pcounty=="blank"){$errCo="Please select a county";}
		if ($pphone==""){$errPh="Please enter a phone number";}
		if ($pemail==""){$errEmail="Please enter an email address";}
		if ($ppassword==""){$errPass="Please enter a password";}
		if ($rltnshp=="blank"){$errRel="Please select the relationship option which best explains your relationship with the patient";}
		//if the verifications dont all pass, these variables are set to be plugged in below the entry with an error.
	}	
	else if((strlen($pid)>20) || (strlen($pid)<6) || 
	strlen($pname1)>30 || strlen($pname1)<2 ||
	strlen($pname2)>30 || strlen($pname2)<2 ||
	strlen($paddress1)>50 || strlen($paddress1)<6 || strlen($paddress2)>50 ||
	strlen($pphone)>20 || strlen($pphone)<8 ||
	strlen($ppassword)>20 || strlen($ppassword)<6 ||
	(preg_match("/^[a-zA-Z]\w+(\.\w+)*\@\w+(\.[0-9a-zA-Z]+)*\.[a-zA-Z]{2,4}$/", $pemail) === 0)||//this checks if it is a valid email, but it doesn't accept emails which contain a dot before the @ so it's flawed
	((checkdate((int)$month, (int)$date, (int)$year))==false) ||//this function checks if a date exists
	((is_numeric($pphone))==false)){//checks if the phone number only contains numeric values
		if(strlen($pid)>20 || strlen($pid)<6 ){$errID="Patient ID must at least 6 and no more than 20 characters";}
		if(($pname1)>30 || strlen($pname1)<2){$errFName ="First name must contain at least 2 and no more than 30 characters";}
		if(strlen($pname2)>30 || strlen($pname2)<2){$errSName ="Surname must contain at least 2 and no more than 30 characters";}
		if((!checkdate((int)$month, (int)$date, (int)$year))){$errDOB="The date of birth selected does not exist";}
		if((strlen($paddress1)>50 || strlen($paddress1)<6)){$errAddr="Address Line 1 must be at least 6 and no more than 50 characters";}
		if(strlen($paddress2)>50){$errAddr2="Address Line 2 must be no more than 50 characters";}
		if((strlen($pphone)>20 || strlen($pphone)<8||(is_numeric($pphone))==false)){$errPh="Phone number must at least 8 and no more than 20 numeric characters";}
		if((preg_match("/^[a-zA-Z]\w+(\.\w+)*\@\w+(\.[0-9a-zA-Z]+)*\.[a-zA-Z]{2,4}$/", $pemail) === 0)){$errEmail="Please enter valid email address";}
		if(strlen($ppassword)>20 || strlen($ppassword)<6){$errPass="Patient's password must be at least 6 and no more than 20 characters";}
	//include county?Email verification
	}
	else if(mysql_num_rows($checkQuery)==1){
		$errID = "The ID you have entered is already in use.";
	}
	else if($ppassword!=$p2){
		$errPass2="The passwords you have entered does not match";}
	else{
	echo("the Patient now EXISTS!!");
	$hashpass = md5($ppassword);
	$newPsql = "INSERT INTO Patient (PatientID, FName, LName, Address1, Address2, County, DOB, Phone, EMail, Password)
				VALUES ('{$pid}', '{$pname1}', '{$pname2}', '{$paddress1}', '{$paddress2}', '{$pcounty}', '{$dob}', '{$pphone}', '{$pemail}', '{$hashpass}')";
	$sqlNewPat = mysql_query($newPsql) or die(mysql_error()); //SOME SORT OF ISSUE WITH THE VALUES
	
	
	$newRelSql = "INSERT INTO UserAccess (PatientID, StaffID, Date, Verified, Relationship) 
	VALUES ('{$pid}', '{$_SESSION['login']}', now(), '0', '{$rltnshp}')";
	
	$sqlNewRel = mysql_query($newRelSql) or die(mysql_error());
		/*
		This code does seem to work, but it takes such an incredible length to process that I've commented it out for now.
		
		if(isset($_POST['wantEmail'])){
			if($_POST['wantEmail']=="Yes"){
				$f = "Your Login Details for the CheckUp Passport are\n================================\n\n";
				$f .= "PatientID: ".$pid;
				$f .= "\nPassword: ".$ppassword; 
				
				include 'plugins/sendMyMail.php';
					
				sendMyMail('padraig_f@hotmail.com', $pemail, "Web Admin", $f, "Registration Details for Website");
				
			}
		}
		*/
	$_SESSION['patient'] = $pid;
	header("Location: PracPatient.php");}//to go into current patient field?
	
}
else{
	$pid = "";
	$pname1 = "";
	$pname2 = "";
	$date = "";
	$month = "";
	$year = "";
	$paddress1 = "";
	$paddress2 = "";
	$pcounty = "";
	$pphone = "";
	$pemail = "";
	$ppassword = "";
	$clozapine = "";
	$rltnshp = "";

}
?>
<h3>Create Patient</br></br>
<form action=NewPatient.php method=post name=createP>
<table class="log">
<tr class="logrow"><td class="logcol1">Patient ID: </td><td><input type="text" name=patientID value=<?php echo $pid ?>></td></tr>
<?php
 if (isset($_POST['create'])){
	if(strlen($pid)>20 || strlen($pid)<6 ||$pid==""){
			echo("<tr><td colspan='3' class='alertrow'>{$errID}</td></tr>");
	 }//this plugs in the variable from above in the case of an error
 }
?>
<tr class="logrow"><td class="logcol1">First Name: </td><td><input type="text" name=pnames1 value=<?php echo $pname1 ?>></td></tr>
<?php
if (isset($_POST['create'])){
	 if(strlen($pname1)>30 || strlen($pname1)<2||$pname1==""){
			echo("<tr><td colspan='3' class='alertrow'>{$errSName}</td></tr>");
	 }
 }
?>
<tr class="logrow"><td class="logcol1">Surname: </td><td><input type="text" name=pnames2 value=<?php echo $pname2 ?>></td></tr>
<?php
if (isset($_POST['create'])){
	 if(strlen($pname2)>30 || strlen($pname2)<2||$pname2==""){
			echo("<tr><td colspan='3' class='alertrow'>{$errSName}</td></tr>");
	 }
 }
?>
<tr class="logrow"><td class="logcol1">Date of Birth: </td><td id="dates">
		<select style="float:right; width:33.3%;" name=pyear>
			<option value="YYYY">YYYY</option>
			<?php //loops to list out values for the option lists
			for($a=2013; $a>=1900; $a--){
				$selected="";
				if($year==$a){ $selected = " selected='selected'"; }//this helps hold the date entered if there is a problem with the form
				echo ("<option value='".$a."'".$selected.">".$a."</option>\n");
			}
			?>
		</select>
		<select style="float:right; width:33.3%;" name=pmonth>
			<option value="MM">MM</option>
			<?php
			for($b=1; $b<=12; $b++){
				$selected="";
				if($month==$b){ $selected = " selected='selected'"; }
				echo ("<option value='".$b."'".$selected.">".$b."</option>\n");
			}
			?>
		</select>
		<select style="float:right; width:33.3%;" name=pdate>
			<option value="DD">DD</option>
			<?php
			for($c=1; $c<=31; $c++){
				$selected="";
				if($date==$c){ $selected = " selected='selected'"; }
				echo ("<option value='".$c."'".$selected.">".$c."</option>\n");
			}
			?>
		</select>
		</td></tr>
<?php
if (isset($_POST['create'])){
 if(!checkdate((int)$month, (int)$date, (int)$year)){
	
	$errDOB = "Please enter a valid date of birth";
		echo("<tr><td colspan='3' class='alertrow'>{$errDOB}</td></tr>");
 }
 }
?>
<tr class="logrow"><td class="logcol1">Address Line 1: </td><td><input type="text" name=paddress1 value="<?php echo $paddress1 ?>"></td></tr>
<?php
if (isset($_POST['create'])){
 if(strlen($paddress1)>50 || strlen($paddress1)<6||$paddress=""){
	$errAddr = "The address you have entered is not valid, please enter an address with at least 6 and no more than 50 characters";
		echo("<tr><td colspan='3' class='alertrow'>{$errAddr}</td></tr>");
 }
 }
?>
<tr class="logrow"><td class="logcol1">Address Line 2: </td><td><input type="text" name=paddress2 value="<?php echo $paddress2 ?>"></td></tr>
<?php
if (isset($_POST['create'])){
 if(strlen($paddress1)>50){
		echo("<tr><td colspan='3' class='alertrow'>{$errAddr2}</td></tr>");
 }
 }
?>
<tr class="logrow"><td class="logcol1">County: </td><td><select name="pcounty">
	<option value="blank">Select an option</option>
	<option <?php if($pcounty=="Clare"){echo("selected='selected'");}?> value="Clare">Clare</option>
	<option <?php if($pcounty=="Donegal"){echo("selected='selected'");}?> value="Donegal">Donegal</option>
	<option <?php if($pcounty=="Galway"){echo("selected='selected'");}?> value="Galway">Galway</option>
	<option <?php if($pcounty=="Leitrim"){echo("selected='selected'");}?> value="Leitrim">Leitrim</option>
	<option <?php if($pcounty=="Limerick"){echo("selected='selected'");}?> value="Limerick">Limerick</option>
	<option <?php if($pcounty=="Mayo"){echo("selected='selected'");}?> value="Mayo">Mayo</option>
	<option <?php if($pcounty=="Sligo"){echo("selected='selected'");}?> value="Sligo">Sligo</option>
	<option <?php if($pcounty=="Tipperary"){echo("selected='selected'");}?> value="Tipperary">Tipperary</option>
</select></td></tr><!-- Option drop down containing HSE West counties-->
<?php
if (isset($_POST['create'])){
 if($pcounty=="blank"){
		echo("<tr><td colspan='3' class='alertrow'>{$errCo}</td></tr>");
 }
 }
?>
<tr class="logrow"><td class="logcol1">Phone No: </td><td><input type="text" name=pphoneNo value="<?php echo $pphone ?>"></td></tr>
<?php
if (isset($_POST['create'])){
 if(strlen($pphone)>20 || strlen($pphone)<8||(is_numeric($pphone))==false){
 
	$errPh = "Please enter a valid numeric phone number";
		echo("<tr><td colspan='3' class='alertrow'>{$errPh}</td></tr>");
 }
 }
?>
<tr class="logrow"><td class="logcol1">E-Mail: </td><td><input type="text" name=pemail value="<?php echo $pemail ?>"></td></tr>
<?php
if (isset($_POST['create'])){
 if(((preg_match("/^[a-zA-Z]\w+(\.\w+)*\@\w+(\.[0-9a-zA-Z]+)*\.[a-zA-Z]{2,4}$/", $pemail) === 0))||$pemail=""){
		echo("<tr><td colspan='3' class='alertrow'>{$errEmail}</td></tr>");
 }
 }
?>
<tr class="logrow"><td class="logcol1">Password: </td><td><input type=password name=ppassword value=></td></tr>
<?php
if (isset($_POST['create'])){
 if(strlen($ppassword)>20 || strlen($ppassword)<6){
		echo("<tr><td colspan='3' class='alertrow'>{$errPass}</td></tr>");
 }
 }
?>
<tr class="logrow"><td class="logcol1">Password(again): </td><td><input type=password name=ppassword2></td></tr>
<?php
if (isset($_POST['create'])){
 if($ppassword!=$p2){
		echo("<tr><td colspan='3' class='alertrow'>{$errPass2}</td></tr>");
 }
 }
?>
<tr class="logrow"><td class="logcol1"></td><td></td></tr><tr class="logrow"><td class="logcol1">Your Relationship: </td><td>
		<select name="relationship">
			<option value="blank">Select a relationship</option>
			<option <?php if($rltnshp=="GP"){echo("selected='selected'");}?> value="GP">GP</option>
			<option <?php if($rltnshp=="Consultant"){echo("selected='selected'");}?> value="Consultant">Consultant</option>
			<option <?php if($rltnshp=="Care Co-Ordinator"){echo("selected='selected'");}?> value="Care Co-Ordinator">Care Co-ordinator</option>
			<option <?php if($rltnshp=="Other"){echo("selected='selected'");}?> value="Other">Other</option>
		</select></td></tr>
<?php
if (isset($_POST['create'])){
 if($rltnshp=="blank"){
		echo("<tr><td colspan='3' class='alertrow'>{$errRel}</td></tr>");
 }
 }
?>
<tr class="logrow"><td colspan=2  style="font-size:12px; text-align:right;">Do you wish to receive an email containing your log in details (Passwords cannot be retrieved after this point)<input type=checkbox name="wantEmail" value="Yes"></td></tr>
<tr class="logrow"><td colspan=2><input style="font-size:20px;" type=submit name="create" value="Create Patient"></td></tr>
</table>
</form> 
<?php
include "footer.php";
?>