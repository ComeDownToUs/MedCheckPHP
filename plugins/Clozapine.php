<?php //this page contains all of the tests for clozapine patients, thus allow the easy expandability for other medications

/**
First it records a variety of verifications, if it passes all of these, it will submit a new entry into the records

As Blood Pressure is generally recorded in a different manner to just a numeric result 
it records a maxmimum and minimum value, systolic and diastolic, I've set up the system to read it as two integers separated by a "/"

Just realised I've the results setting on the database as an int, it should probably be a double or float, to record decimal values

With the exception of blood pressure, the code for recording each result listed below is largely identical so I've only commented the first one

**/


if(isset($_POST['record'])){
	$recordss="";
	if(	($_POST['dosage']==""||!is_numeric($_POST['dosage']))&&
		($_POST['brand']=="blank")&&
		(($_POST['comments']=="")||strlen($_POST['comments'])<10)&&
		($_POST['bsugar']==""||!is_numeric($_POST['bsugar']))&&
		($_POST['blipids']==""||!is_numeric($_POST['blipids']))&&
		($_POST['lft']==""||!is_numeric($_POST['lft']))&&
		($_POST['urea']==""||!is_numeric($_POST['urea']))&&
		($_POST['ecg']==""||!is_numeric($_POST['ecg']))&&
		($_POST['bp']=="")&& //split? explode array?
		($_POST['pulse']==""||!is_numeric($_POST['pulse']))&&
		($_POST['bmi']==""||!is_numeric($_POST['bmi']))){
	
	}
	else if($validation==1){}
	else if($_POST['bp']==""){
					$newRecords = "INSERT INTO RecordsDetail (PatientID, DoctorID, Date, MedBrand, Dose, Comments)
									VALUES ('{$_SESSION['patient']}', '{$_SESSION['practitioner']}', '{$date}', '{$brand}', {$dosage}, '{$comment}')";
					$recordSQL=	mysql_query($newRecords);
					$recordss="submitted";
	}
	else{
	$bp=explode("/", $_POST['bp']);
		if((isset($bp[0])&&isset($bp[1]))){
			if(is_numeric($bp[0])&&is_numeric($bp[1])){
				if($bp[0]>300||$bp[0]<20||$bp[1]>300||$bp[1]<20){}
				else{
					$newRecords = "INSERT INTO RecordsDetail (PatientID, DoctorID, Date, MedBrand, Dose, Comments)
									VALUES ('{$_SESSION['patient']}', '{$_SESSION['practitioner']}', '{$date}', '{$brand}', {$dosage}, '{$comment}')";
					$recordSQL=	mysql_query($newRecords);
					$recordss="submitted";
				}
			}
		}
	}
}
else{
	$record_id = "";
	$resultBS = "";
	$resultBL = "";
	$resultLFT = "";
	$resultUrea= "";
	$resultECG= "";
	$resultBP= "";
	$resultPulse= "";
	$resultBMI= "";
	$recordss="";
	$validation="";
}

/**
Once the record has been submitted, the system needs that record number so it can place the results with the correct record
The if statement below does this.
**/

if($recordss=="submitted"){
	$findRecordID = "SELECT MAX(RecordID) FROM RecordsDetail WHERE PatientID='{$_SESSION['patient']}'"; //selects the higher RecordID value that the patient has, which should be the one which has been just entered
	$RecordID = mysql_query($findRecordID);
	list($record_id) = mysql_fetch_array($RecordID); //don't fully understand this one, but it converts the result into a string.
} 


?>

<?php //bloodsugar
$bsTest = "Blood Sugar";//for the SQL data

if(isset($_POST['bsugar'])){
	$resultBS=$_POST['bsugar'];
}//getting this set first so the value will be stored withing the input below

$resultsBS = "SELECT det.TestName, det.Reason, det.MinFreqElab, det.Fasting FROM TestDescription AS det 
			WHERE det.TestName='{$bsTest}'";
$testBS = mysql_query($resultsBS) or die(mysql_error());
if($testBS){
	while($testDeets = mysql_fetch_array($testBS)){//each line contains a check to see if fasting is recommended, the test results perhaps should also record that as not fasting would distort results
		echo("<tr><td>{$testDeets[0]}<br>"); if($testDeets[3]==1){echo("<div style='font-decoration:italics; font-size: 10px;'>fasting if possible</div>");}
		echo("</td><td><a href='#' id='bs-link' class='dialog-link' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-newwin' style='float:right;'></span></a>
<div id='bs' class='dialog' title='Blood Sugar'>
	<p>Reason:<br>{$testDeets[1]}</p>
	<p>Minimum Frequency:<br>{$testDeets[2]}</p>
</div></td><td><input type='text' name='bsugar' value='{$resultBS}'></td></tr>");}
	mysql_free_result($testBS);} //will explain the dialogs in the results page
else{
	echo "An error occurred ".mysql_error();}

if(isset($_POST['bsugar'])){
	if($resultBS!=""){
		if(!is_numeric($resultBS)){//"is_numeric" is a basic PHP function to ensure the value which has entered is numeric, this prevents SQL insertion errors
			echo("<tr><td colspan='3' class='alertrow'>Please insert a numeric value into the {$bsTest} field if you wish to record a result.</td></tr>");
		}//the "alertrow" class is used to create an additional row for occasions where the submission is unacceptable
		else if($_POST['bsugar']>50||$_POST['bsugar']<2){
			echo("<tr><td colspan='3' class='alertrow'>The value in the {$bsTest} must be between 2 and 50</td></tr>");
			$validation=1;
		}
		else if($recordss=="submitted"){
				$testInputBS = "INSERT INTO TestRecords (RecordID, TestName, Medication, Result) VALUES({$record_id}, '{$bsTest}', '{$_SESSION['medication']}', {$resultBS})";
				$insertBS = mysql_query($testInputBS) or die(mysql_error());
			} //only sumbits a result if the extensive verification at the top has been passed
		//the system is set up to ignore the inputs which are left blank, this prevents clutter in the view results page and the distortion of aggregated samples if they are ever needed
	}
}
?>

<?php //bloodlipids
$blTest = "Blood Lipids";

if(isset($_POST['record'])){
$resultBL=$_POST['blipids'];
}

$resultsBL = "SELECT det.TestName, det.Reason, det.MinFreqElab, det.Fasting FROM TestDescription AS det 
			WHERE det.TestName='{$blTest}'";
$testBL = mysql_query($resultsBL) or die(mysql_error());
if($testBL){
	while($testDeets = mysql_fetch_array($testBL)){
		echo("<tr><td>{$testDeets[0]}<br>"); if($testDeets[3]==1){echo("<div style='font-decoration:italics; font-size: 10px;'>fasting if possible</div>");}
		echo("</td><td><a href='#' id='bl-link' class='dialog-link' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-newwin' style='float:right;'></span></a>
<div id='bl' class='dialog' title='Blood Lipids'>
	<p>Reason:<br>{$testDeets[1]}</p>
	<p>Minimum Frequency:<br>{$testDeets[2]}</p>
</div></td><td><input type='text' name='blipids' value='{$resultBL}'></td></tr>");}
	mysql_free_result($testBL);}
else{
	echo "An error occurred ".mysql_error();}
	
if(isset($_POST['record'])){
	if($resultBL!=""){
		if(!is_numeric($resultBL)){
			echo("<tr><td colspan='3' class='alertrow'>Please insert a numeric value into the {$blTest} field if you wish to record a result.</td></tr>");
		}
		else if($_POST['blipids']>50||$_POST['blipids']<2){
			echo("<tr><td colspan='3' class='alertrow'>The value in the {$blTest} must be between 2 and 50</td></tr>");
			$validation=1;
		}
		else if($recordss=="submitted"){
			$testInputBL = "INSERT INTO TestRecords (RecordID, TestName, Medication, Result) VALUES({$record_id}, '{$blTest}', '{$_SESSION['medication']}', {$resultBL})";
			$insertBL = mysql_query($testInputBL) or die(mysql_error());
		}
	}
}
?>

<?php //fullbloodcounts
$fbcTest = "Full Blood Counts";

$resultsFBC = "SELECT det.TestName, det.Reason, det.MinFreqElab, det.Fasting FROM TestDescription AS det 
			WHERE det.TestName='{$fbcTest}'";
$testFBC = mysql_query($resultsFBC) or die(mysql_error());
if($testFBC){
	while($testDeets = mysql_fetch_array($testFBC)){
		echo("<tr><td>{$testDeets[0]}<br>"); if($testDeets[3]==1){echo("<div style='font-decoration:italics; font-size: 10px;'>fasting if possible</div>");}
		echo("</td><td><a href='#' id='fbc-link' class='dialog-link' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-newwin' style='float:right;'></span></a>
<div id='fbc' class='dialog' title='Full Blood Counts (FBCs)'>
	<p>Reason:<br>{$testDeets[1]}</p>
	<p>Minimum Frequency:<br>{$testDeets[2]}</p>
</div></td><td></td></tr>");}
	mysql_free_result($testFBC);}
else{
	echo "An error occurred ".mysql_error();}
?>

<?php //lft
$lftTest = "Liver Checks";

if(isset($_POST['lft'])){
$resultLFT=$_POST['lft'];
}

$resultsLFT = "SELECT det.TestName, det.Reason, det.MinFreqElab, det.Fasting FROM TestDescription AS det 
			WHERE det.TestName='{$lftTest}'";
$testLFT = mysql_query($resultsLFT) or die(mysql_error());
if($testLFT){
	while($testDeets = mysql_fetch_array($testLFT)){
		echo("<tr><td>{$testDeets[0]}<br>"); if($testDeets[3]==1){echo("<div style='font-decoration:italics; font-size: 10px;'>fasting if possible</div>");}
		echo("</td><td><a href='#' id='lft-link' class='dialog-link' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-newwin' style='float:right;'></span></a>
<div id='lft' class='dialog' title='Liver Checks (LFTs)'>
	<p>Reason:<br>{$testDeets[1]}</p>
	<p>Minimum Frequency:<br>{$testDeets[2]}</p>
</div></td><td><input type='text' name='lft' value='{$resultLFT}'></td></tr>");}
	mysql_free_result($testLFT);}
else{
	echo "An error occurred ".mysql_error();}

if(isset($_POST['lft'])){
	if($resultLFT!=""){
		if(!is_numeric($resultLFT)){
			echo("<tr><td colspan='3' class='alertrow'>Please insert a numeric value into the {$lftTest} field if you wish to record a result.</td></tr>");
		}
		else if($_POST['lft']>50||$_POST['lft']<2){
			echo("<tr><td colspan='3' class='alertrow'>The value in the {$lftTest} must be between 2 and 50</td></tr>");
			$validation=1;
		}
		else if($recordss=="submitted"){
			$testInputLFT = "INSERT INTO TestRecords (RecordID, TestName, Medication, Result) VALUES({$record_id}, '{$lftTest}', '{$_SESSION['medication']}', {$resultLFT})";
			$insertLFT = mysql_query($testInputLFT) or die(mysql_error());
		}
	}
}
?>

<?php //urea
$ureaTest = "Urea and Electrolytes";

if(isset($_POST['urea'])){
	$resultUrea= $_POST['urea'];
}

$resultsUrea = "SELECT det.TestName, det.Reason, det.MinFreqElab, det.Fasting FROM TestDescription AS det 
			WHERE det.TestName='{$ureaTest}'";
$testUrea = mysql_query($resultsUrea) or die(mysql_error());
if($testUrea){
	while($testDeets = mysql_fetch_array($testUrea)){
		echo("<tr><td>{$testDeets[0]}<br>"); if($testDeets[3]==1){echo("<div style='font-decoration:italics; font-size: 10px;'>fasting if possible</div>");}
		echo("</td><td><a href='#' id='urea-link' class='dialog-link' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-newwin' style='float:right;'></span></a>
<div id='urea' class='dialog' title='Urea and Electrolytes (U&Es)'>
	<p>Reason:<br>{$testDeets[1]}</p>
	<p>Minimum Frequency:<br>{$testDeets[2]}</p>
</div></td><td><input type='text' name='urea' value='{$resultUrea}'></td></tr>");}
	mysql_free_result($testUrea);}
else{
	echo "An error occurred ".mysql_error();}
	
if(isset($_POST['urea'])){
	if($resultUrea!=""){
		if(!is_numeric($resultUrea)){
			echo("<tr><td colspan='3' class='alertrow'>Please insert a numeric value into the {$ureaTest} field if you wish to record a result.</td></tr>");
		}
		else if($_POST['urea']>50||$_POST['urea']<2){
			echo("<tr><td colspan='3' class='alertrow'>The value in the {$ureaTest} must be between 2 and 50</td></tr>");
			$validation=1;
		}
		else if($recordss=="submitted"){
			$testInputUrea = "INSERT INTO TestRecords (RecordID, TestName, Medication, Result) VALUES({$record_id}, '{$ureaTest}', '{$_SESSION['medication']}', {$resultUrea})";
			$insertUrea = mysql_query($testInputUrea) or die(mysql_error());
		}
	}
}
?>

<?php //ecg
$ecgTest = "Electrocardlogram";

if(isset($_POST['ecg'])){
	$resultECG = $_POST['ecg'];
}

$resultsECG = "SELECT det.TestName, det.Reason, det.MinFreqElab, det.Fasting FROM TestDescription AS det 
			WHERE det.TestName='{$ecgTest}'";
$testECG = mysql_query($resultsECG) or die(mysql_error());
if($testECG){
	while($testDeets = mysql_fetch_array($testECG)){
		echo("<tr><td>{$testDeets[0]}<br>"); if($testDeets[3]==1){echo("<div style='font-decoration:italics; font-size: 10px;'>fasting if possible</div>");}
		echo("</td><td><a href='#' id='ecg-link' class='dialog-link' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-newwin' style='float:right;'></span></a>
<div id='ecg' class='dialog' title='Electrocardlogram (ECG)'>
	<p>Reason:<br>{$testDeets[1]}</p>
	<p>Minimum Frequency:<br>{$testDeets[2]}</p>
</div></td><td><input type='text' name='ecg' value='{$resultECG}'></td></tr>");}
	mysql_free_result($testECG);}
else{
	echo "An error occurred ".mysql_error();}


if(isset($_POST['ecg'])){
	if($resultECG!=""){
		if(!is_numeric($resultECG)){
			echo("<tr><td colspan='3' class='alertrow'>Please insert a numeric value into the {$ecgTest} field if you wish to record a result.</td></tr>");
		}
		else if($_POST['ecg']>50||$_POST['ecg']<2){
			echo("<tr><td colspan='3' class='alertrow'>The value in the {$ecgTest} must be between 2 and 50</td></tr>");
			$validation=1;
		}
		else if($recordss=="submitted"){
			$testInputECG = "INSERT INTO TestRecords (RecordID, TestName, Medication, Result) VALUES({$record_id}, '{$ecgTest}', '{$_SESSION['medication']}', {$resultECG})";
			$insertECG = mysql_query($testInputECG);
		}
	}
}	
?>

<?php //bpressure
$bpTest = "Blood Pressure";

if(isset($_POST['bp'])){
	$resultBP = $_POST['bp'];
}

$resultsBP = "SELECT det.TestName, det.Reason, det.MinFreqElab, det.Fasting FROM TestDescription AS det 
			WHERE det.TestName='{$bpTest}'";
$testBP = mysql_query($resultsBP) or die(mysql_error());
if($testBP){
	while($testDeets = mysql_fetch_array($testBP)){
		echo("<tr><td>{$testDeets[0]}(SYS/DIA)<br>"); if($testDeets[3]==1){echo("<div style='font-decoration:italics; font-size: 10px;'>fasting if possible</div>");}
		echo("</td><td><a href='#' id='bp-link' class='dialog-link' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-newwin' style='float:right;'></span></a>
<div id='bp' class='dialog' title='Blood Pressure'>
	<p>Reason:<br>{$testDeets[1]}</p>
	<p>Minimum Frequency:<br>{$testDeets[2]}</p>
</div></td><td><input type='text' name='bp' value='{$resultBP}'></td></tr>");}
	mysql_free_result($testBP);}
else{
	echo "An error occurred ".mysql_error();}
	
if(isset($_POST['bp'])){
	if($resultBP!=""){
		$bp=explode("/", $_POST['bp']);//this splits the string entry into a string array containing values separated by "/", the verification will check the first two entries, there should be not more
		if(isset($bp[0])&&isset($bp[1])){
			if(is_numeric($bp[0])&&is_numeric($bp[1])){
				if($bp[0]>200||$bp[0]<30||$bp[1]>200||$bp[1]<30){//in actuality, Systolic should always be much higher, but all verification numbers are either rough estimates or random guesses.
					echo("<tr><td colspan='3' class='alertrow'>Results for the {$bpTest} field must follow the specifications.\nNeither value should exceed realistic human levels at rest(ie. Under 30 or over 200)</td></tr>");
			$validation=1;
				}
				else if($recordss=="submitted"){
					$testInputBP1 = "INSERT INTO TestRecords (RecordID, TestName, Medication, Result) VALUES({$record_id}, '{$bpTest}', '{$_SESSION['medication']}', {$bp[0]})";
					$insertBP1 = mysql_query($testInputBP1);
					$testInputBP2 = "INSERT INTO TestRecords (RecordID, TestName, Medication, Result) VALUES({$record_id}, '{$bpTest} DIA', '{$_SESSION['medication']}', {$bp[1]})";
					$insertBP2 = mysql_query($testInputBP2);
				}
			}
		}
		else{
			echo("<tr><td colspan='3' class='alertrow'>Results for the {$bpTest} must be include systolic and dystolic rates, split by '/'</td></tr>");
		}
	}
}
?>

<?php //pulse
$pulseTest = "Pulse";

if(isset($_POST['pulse'])){
	$resultPulse = $_POST['pulse'];
}

$resultsPulse = "SELECT det.TestName, det.Reason, det.MinFreqElab, det.Fasting FROM TestDescription AS det 
			WHERE det.TestName='{$pulseTest}'";
$testPulse = mysql_query($resultsPulse) or die(mysql_error());
if($testPulse){
	while($testDeets = mysql_fetch_array($testPulse)){
		echo("<tr><td>{$testDeets[0]}<br>"); if($testDeets[3]==1){echo("<div style='font-decoration:italics; font-size: 10px;'>fasting if possible</div>");}
		echo("</td><td><a href='#' id='pulse-link' class='dialog-link' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-newwin' style='float:right;'></span></a>
<div id='pulse' class='dialog' title='Pulse'>
	<p>Reason:<br>{$testDeets[1]}</p>
	<p>Minimum Frequency:<br>{$testDeets[2]}</p>
</div></td><td><input type='text' name='pulse' value='{$resultPulse}'></td></tr>");}
	mysql_free_result($testPulse);}
else{
	echo "An error occurred ".mysql_error();}
	
if(isset($_POST['pulse'])){
	if($resultPulse!=""){	
		if(!is_numeric($resultPulse)){
			echo("<tr><td colspan='3' class='alertrow'>Please insert a numeric value into the {$pulseTest} field if you wish to record a result.</td></tr>");
		}
		else if($_POST['pulse']>250||$_POST['pulse']<2){
			echo("<tr><td colspan='3' class='alertrow'>The value in the {$pulseTest} must be between 2 and 50</td></tr>");
			$validation=1;
		}
		else if($recordss=="submitted"){
			$testInputPulse = "INSERT INTO TestRecords (RecordID, TestName, Medication, Result) VALUES({$record_id}, '{$pulseTest}', '{$_SESSION['medication']}', {$resultPulse})";
			$insertPulse = mysql_query($testInputPulse);
		}
	}
}
?>

<?php //bmi
$bmiTest = "Weight/Body Mass Index";

if(isset($_POST['bmi'])){
	$resultBMI = $_POST['bmi'];
}

$resultsBMI = "SELECT det.TestName, det.Reason, det.MinFreqElab, det.Fasting FROM TestDescription AS det 
			WHERE det.TestName='{$bmiTest}'";
$testBMI = mysql_query($resultsBMI) or die(mysql_error());
if($testBMI){
	while($testDeets = mysql_fetch_array($testBMI)){
		echo("<tr><td>{$testDeets[0]}<br>"); if($testDeets[3]==1){echo("<div style='font-decoration:italics; font-size: 10px;'>fasting if possible</div>");}
		echo("</td><td><a href='#' id='bmi-link' class='dialog-link' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-newwin' style='float:right;'></span></a>
<div id='bmi' class='dialog' title='Weight/Body Mass Index'>
	<p>Reason:<br>{$testDeets[1]}</p>
	<p>Minimum Frequency:<br>{$testDeets[2]}</p>
</div></td><td><input type='text' name='bmi' value='{$resultBMI}'></td></tr>");}
	mysql_free_result($testBMI);}
else{
	echo "An error occurred ".mysql_error();}
	
	
if(isset($_POST['bmi'])){
	if($resultBMI!=""){
		if(!is_numeric($resultBMI)){
			echo("<tr><td colspan='3' class='alertrow'>Please insert a numeric value into the {$bmiTest} field if you wish to record a result.</td></tr>");
		}
		else if($_POST['bmi']>50||$_POST['bmi']<10){
			echo("<tr><td colspan='3' class='alertrow'>The value in the {$bmiTest} must be between 2 and 50</td></tr>");
			$validation=1;
		}
		else if($recordss=="submitted"){
			$testInputBMI = "INSERT INTO TestRecords (RecordID, TestName, Medication, Result) VALUES({$record_id}, '{$bmiTest}', '{$_SESSION['medication']}', {$resultBMI})";
			$insertBMI = mysql_query($testInputBMI);
		}
	}
}

?>
