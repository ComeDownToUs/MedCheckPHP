<?php
session_start();


//ADMINISTRATOR PAGE: LEAVE ALONE FOR NOW
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="GeneralLayout.css">
</head>
<body>
<?php 
mysql_connect("danu2.it.nuigalway.ie", "mydb1057", "mydb105730") or die("connection failure");
mysql_select_db("mydb1057");
/**
	Create Practitioner
	Create Test
	Add Medications
	View Security Threats
		- Failed Login Attempts
		- Unusual IP Addresses
		- Unusually high traffic
	

**/
if (isset($_POST['newPrac'])){
$pracID = $_POST['pracID'];
$pracName1 = $_POST['names1'];
$pracName2 = $_POST['names2'];
$date = $_POST['pdate'];
$month = $_POST['pmonth'];
$year = $_POST['pyear'];
$dob = "{$year}-{$month}-{$date}";
$pracLoc = $_POST['location'];
$pracPos = $_POST['position'];
$pracPh = $_POST['phoneNo'];//possibly exclude
$email = $_POST['email'];
$pass1 = $_POST['password'];
$pass2 = $_POST['password2'];
$selected = "";



	if($pracID==""||$pracName1==""||$pracName2==""||($date=="DD"&&$month=="MM"&&$year=="YYYY")||$pracLoc=="blank"||$pracPos==""||$pracPh==""||$email==""||$pass1==""){
		if($pracID==""&&$pracName==""&&($date=="DD"||$month=="MM"||$year=="YYYY")&&$pracLoc=="blank"&&$pracPos==""&&$pracPh==""&&$email==""&&$pass1==""){
		echo("Please fill in all fields");
		}
		else{
		echo("Please fill in the following fields: <ul>");
			if($pracID==""){
				echo("<li>Prac ID</li>");
			}
			if($pracName==""||$pracName2==""){
				echo("<li>Name</li>");
			}
			if($date=="DD"||$month=="MM"||$year=="YYYY"){
				echo("<li>Date of Birth</li>");
			}
			if($pracLoc=="blank"){
				echo("<li>Location</li>");
			}
			if($pracPh==""){
				echo("<li>Phone Number</li>");
			}
			if($email==""){
				echo("<li>Email</li>");
			}
			if($pass1==""||$pass2==""){
				echo("<li>Both password fields</li>");
			}
		echo("</ul>");
		}
	}
	else if(strlen($pracID)>20||strlen($pracName2)>40||strlen($pracName1)>40||strlen($pracPos)>20||strlen($pracPh)>20||strlen($pass1)>20||
			strlen($pracID)<6||strlen($pracName2)<2||strlen($pracName1)<2||strlen($pracPos)<6||strlen($pracPh)<8||strlen($pass1)<10||
			(preg_match("/^[a-zA-Z]\w+(\.\w+)*\@\w+(\.[0-9a-zA-Z]+)*\.[a-zA-Z]{2,4}$/", $email) === 0)||
			(checkdate((int)$month, (int)$date, (int)$year))==false||
			(is_numeric($pracPh))==false){
		echo("The submission must meet the following requirements: <ul>");
			if(strlen($pracID)>20||strlen($pracID)<6){echo("<li>Practitioner ID: Must be between 6 and 20 characters</li>");}
			if(strlen($pracName1)>40||strlen($pracName1)<2||strlen($pracName2)>40||strlen($pracName2)<2){echo("<li>Name: Both First Name and Surname must be between 2 and 40 characters</li>");}
			if((!checkdate((int)$month, (int)$date, (int)$year))){echo("<li>Date of Birth: The date entered does not exist</li>");}
			if(strlen($pracPos)>20||strlen($pracPos)<6){echo("<li>Position: Must be between 6 and 40 characters</li>");}
			if(strlen($pracPh)>20 || strlen($pracPh)<8 ||is_numeric($pracPh)==false){echo("<li>Phone: at least 8 and no more than 20 numeric characters</li>");}
			if((preg_match("/^[a-zA-Z]\w+(\.\w+)*\@\w+(\.[0-9a-zA-Z]+)*\.[a-zA-Z]{2,4}$/", $email) === 0)){echo("<li>A valid email address</li>");}
			if(strlen($pass1)>20||strlen($pass1)<10){echo("<li>Password: Must be between 10 and 20 characters</li>");}
		echo("</ul>");
	}//include valid date, valid email, valid phone? Locations from list, positions from list
	else if($pass1!=$pass2){
		echo("The two entries of password do not match, please try again");
	}
	else{
	$hashpass = md5($pass1);
	$CreatePrac = "INSERT INTO Staff(StaffID, FName, LName, DOB, Location, Position, Phone, Email, Password) VALUES ('{$pracID}', '{$pracName1}', '{$pracName2}', '{$dob}', '{$pracLoc}', '{$pracPos}', '{$pracPh}', '{$email}', '{$hashpass}')";
	echo($dob1);
	$input = mysql_query($CreatePrac)  or die(mysql_error());
	$_SESSION['practor'] = $pracID;
	header("Location: NewPractitionerDetails.php");
	}
}
else{
$pracID = "";
$pracName1 = "";
$pracName2 = "";
$date = "";
$month = "";
$year = "";
$pracLoc = "";
$pracPos = "";
$pracPh = "";
$email = "";
$pass1 = "";
$pass2 = "";
}
?>

Create Practitioner Account:</br>
<form action=admin.php method=post name=createP>
<table>
<tr><th class="2col1"></th><th class="2col2"></th></tr>
<tr><td>Staff ID: </td><td><input type="text" name=pracID value="<?php echo $pracID; ?>"></td></tr>
<tr><td>First Name: </td><td><input type="text" name=names1 value="<?php echo($pracName1);?>"></td></tr>
<tr><td>Surname: </td><td><input type="text" name=names2 value="<?php echo($pracName2);?>"></td></tr>
<tr><td>Date of Birth: </td><td>
		<select style="float:right; width:33.3%;" name=pyear>
			<option value="YYYY">YYYY</option>
			<?php
			for($a=2013; $a>=1900; $a--){
				if($year==$a){ $selected = " selected='selected'"; }
				else{$selected="";}
				echo ("<option value='".$a."'".$selected.">".$a."</option>\n");
			}
			?>
		</select>
		<select style="float:right; width:33.3%;" name=pmonth>
			<option value="MM">MM</option>
			<?php
			for($b=1; $b<=12; $b++){
				if($month==$b){ $selected = " selected='selected'"; }
				else{$selected="";}
				echo ("<option value='".$b."'".$selected.">".$b."</option>\n");
			}
			?>
		</select>
		<select style="float:right; width:33.3%;" name=pdate>
			<option value="DD">DD</option>
			<?php
			for($c=1; $c<=31; $c++){
				if($date==$c){ $selected = " selected='selected'"; }
				else{$selected="";}
				echo ("<option value='".$c."'".$selected.">".$c."</option>\n");
			}
			?>
		</select>
		</td></tr>
<tr><td>Location: </td><td>
	<select name="location">
		<option value="blank">Select a location</option>
		<?php
		
			$locName = "SELECT LocName FROM Location";
			$listLoc = mysql_query($locName);
			
			if ($listLoc) {
					while ($Locations = mysql_fetch_array($listLoc)) {
						$choice = "";
						if($pracLoc==$Locations[0]){$choice = " selected='selected'";}
						echo "<option value='{$Locations[0]}'".$choice.">{$Locations[0]}</option>\n";}
					mysql_free_result($listLoc);}
				else {
					echo "an error occurred".mysql_error();}
		?>
	</select>
	</td></tr>
<tr><td>Position: </td><td><input type="text" name=position value=<?php echo($pracPos);?>></td></tr>
<tr><td>Phone No: </td><td><input type="text" name=phoneNo value=<?php echo($pracPh); ?>></td></tr>
<tr><td>E-Mail: </td><td><input type="text" name=email value=<?php echo($email); ?>></td></tr>
<tr><td>Password: </td><td><input type="password" name=password value=<?php echo($pass1); ?>></td></tr>
<tr><td>Password: </td><td><input type="password" name=password2></td></tr>
<tr><td></td><td><input type="submit" value="Create Staff Account" name="newPrac"></td></tr>
</table>
</form>
Possible extra verifications?

<?php 
if(isset($_POST['newTest'])){
	$tname=$_POST['tname'];
	$medication=$_POST['medication'];
	$reason=$_POST['reason'];
	$freq=$_POST['freq'];
	$freqEx=$_POST['freqEx'];
	if(isset($_POST['prior'])){
		$prior=1;}
	else{
		$prior=0;}
	if(isset($_POST['yearly'])){
		$yearly=1;}
	else{
		$yearly=0;}
	if(isset($_POST['records'])){
		$records=1;}
	else{
		$records=0;}
	$exception = "";
	
	$findTest = "SELECT * FROM TestDescription WHERE TestName='".$tname."' AND Medication='".$medication."'";
	$findTestSQL = mysql_query($findTest);
	
	if($tname==""||$medication=="blank"||$reason==""||$freq=="blank"){
		if($tname==""&&$medication=="blank"&&$reason==""&&$freq=="blank"){
			echo("Please fill in all of the required fields.");
		}
		if($tname==""||$medication==""||$reason==""||$freq=="blank"){
			echo("Please fill in the following fields: <ul>");
			if($tname==""){
				echo("<li>Test Name</li>");
			}
			if($medication=="blank"){
				echo("<li>Medication</li>");
			}
			if($reason==""){
				echo("<li>The reason why patient's who are taken the selected medication need to take this test</li>");
			}
			if($freq=="blank"){
				echo("<li>The estimated frequency of the tests</li>");
			}
		}
	}
	else if(strlen($tname)>60||strlen($reason)<20||
			strlen($tname)<5||strlen($reason)>600){
		if(strlen($tname)>60||strlen($tname)<5){echo("Test name should be between 5 and 60 characters");}
		if(strlen($reason)<20||strlen($reason)>600){echo("The reason should be between 20 and 600 characters");}
	}
	else if(mysql_num_rows($findTestSQL)!=0){
		header("Location: testDetails.php");//specify test already exists
	}
	else{
		$newTest = "INSERT INTO TestDescription(TestName, Medication, Reason, MinimumFrequency, MinFreqElab, PriorToStarting, YearlyCheck, Exception, KeepRecord) 
					VALUES('".$tname."', '".$medication."', '".$reason."', '".$freq."', '".$freqEx."', ".$prior.", ".$yearly.", '".$exception."', '".$records."')";
		$insertTest = mysql_query($newTest);
		$_SESSION['testn'] = $tname;
		$_SESSION['testm'] = $medication;
		header("Location: newTestDetails.php");
	}
}
else{
	$tname="";
	$medication="";
	$reason="";
	$freq="";
	$freqEx="";
	$prior=0;
	$yearly=0;
	$record=0;
}
?>
Create a test:
Rules:
You must create an individual instance of a test for each type of medication which it is being used for as the reasons for the test and how it relates to a patient's treatment may vary.
<form action=admin.php method=post name=createt>
<table class="formtable">
<tr><td>Test Name: </td><td><input type="text" name="tname" value="<?php echo $tname; ?>"></td></tr>
<tr><td>Medication: </td><td>
	<select name="medication">
		<option value="blank">Select</option>
		<?php
		
			$medName = "SELECT m.Medication FROM Medications AS m GROUP BY m.Medication";
			$listMed = mysql_query($medName);
			
			if ($listMed) {
					while ($Medications = mysql_fetch_array($listMed)) {
						$choice = "";
						if($medication==$Medications[0]){$choice = " selected='selected'";}
						echo "<option value='{$Medications[0]}'".$choice.">{$Medications[0]}</option>\n";}
					mysql_free_result($listMed);}
				else {
					echo "an error occurred".mysql_error();}
		?>
	</select>
	</td></tr>
<tr><td>Reason: </td><td><textarea name="reason"><?php echo($reason);?></textarea></td></tr>
<tr><td>Minimum Frequency (first 6 months): </td><td>
	<select name="freq">
		<option value="blank">Months</option>
<?php
for($option=1; $option<7; $option++){
$selected="";
if($freq==$option){ $selected = " selected='selected'"; }
echo("	<option value='".$option."' ".$selected.">".$option." Months</option>");
}
?>
		<option value="no">Not Usually required</option>
	</select>
</td></tr>
<tr><td>Minimum Frequency Elabortion: </td><td><input type="text" name="freqEx" value="<?php echo($freqEx);?>"></td></tr>
<tr><td rowspan=3></td><td>Prior To Starting<input type="checkbox" name="prior" value=1></td></tr>
<tr><td>Yearly Check<input type="checkbox" name="yearly" value=1></td></tr>
<tr><td>Hold Records<input type="checkbox" name="records" value=1></td></tr>
<tr><td></td><td><input type="submit" value="Create New Test" name="newTest"></td></tr>

</table>
</form>

</body></html>

