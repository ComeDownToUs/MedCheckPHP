<?php include "header.php";

$pastresults = "blah";
if(!isset($_SESSION['medication'])&&!isset($_SESSION['patient'])&&!isset($_SESSION['login'])){
	header("Location: Login.php"); //redirects user if they do not meet the required criteria to view the page, login page redirects them to their actual home
}
else{
$action="Results.php";


/**	
	if getDate submit button is set, the system loads two while loops
	The first one displays the general information from the last recorded test
	The second loop, located within the first, displays the results of each test recorded on that date
	As there should be only one array entry for the first loop, it ends upon printing out all of the necessary data
**/
?>
		<form name="findDates" method="post" action="<?php echo($action);?>">
		<table class="log">
		<tr class="logrow"><td class="infocol1">Select Test Records</td>
		<td><select name="testDate">
				<option value="">Select a date</option>
				<?php 
					$testDates = "SELECT r.Date FROM RecordsDetail AS r LEFT JOIN Medications AS m ON r.MedBrand=m.MedBrand 
					WHERE r.PatientID='{$_SESSION['patient']}' AND m.Medication='{$_SESSION['medication']}'";
					
					$testQuery = mysql_query($testDates);
						if ($testQuery) {
							while ($rows = mysql_fetch_array($testQuery)) {
								echo "<option value='{$rows[0]}'>{$rows[0]}</option>";}
							mysql_free_result($testQuery);}
						else {
							echo "an error occurred".mysql_error();}
					
				?>
			</select></td></tr>
<?php
if(isset($_POST['getDate'])){
	$testDate = $_POST['testDate'];
	if($testDate==""){
		echo("<tr><td colspan='2' class='alertrow'>Please select a valid date option</td></tr>");
	}
}
?>
		<tr><td colspan=2><input type="submit" name="getDate" value="List Tests"></td></tr>
		</table>
		
		</form>
<?php
if(isset($_POST['getDate'])){
	$testDetails = "SELECT r.RecordID, r.Date, r.MedBrand, r.Dose, d.FName, d.LName, r.Comments FROM RecordsDetail AS r
	LEFT JOIN Staff AS d ON d.StaffID=r.DoctorID WHERE PatientID='".$_SESSION['patient']."' AND Date='".$testDate."'";

	$displayDetails = mysql_query($testDetails);

	if($displayDetails){
	while ($details = mysql_fetch_array($displayDetails)){
		$recordID = $details[0];
?>
		<table class="info">
		<tr><td class="infocol1">Test #</td><td class="infocol2"><?php echo("{$details[0]}"); ?></td></tr>
		<tr><td class="infocol1">Date</td><td class="infocol2"><?php echo("{$details[1]}");?></td></tr>
		<tr><td class="infocol1">Brand</td><td class="infocol2"><?php echo("{$details[2]}");?></td></tr>
		<tr><td class="infocol1">Dose (mg)</td><td class="infocol2"><?php echo("{$details[3]}");?></td></tr>
		<tr><td class="infocol1">Recorded by</td><td class="infocol2"><?php echo("{$details[4]} {$details[5]}");?></td></tr>
		</table></br>
<?php 

			$findResults = "SELECT d.TestName, d.Reason, d.MinFreqElab, r. Result
			FROM TestRecords AS r LEFT JOIN TestDescription AS d ON r.TestName=d.TestName
			WHERE RecordID='{$recordID}'";
			//Exception entry to create an if exception system to exclude things such as the full blood count if required

		$displayResults = mysql_query($findResults);

		if($displayResults){ ?>
			<table class=info>
			<tr><th class="infoheader">Test</th><th class="infoheader">Reasons</th><th class="infoheader">Results</th><tr>
<?php
			while ($results = mysql_fetch_array($displayResults)){
				if($results[0]=="Blood Sugar"){$dialogName="bs";}
				else if($results[0]=="Blood Lipids"){$dialogName="bl";}
				else if($results[0]=="Full Blood Counts"){$dialogName="fbc";}
				else if($results[0]=="Liver Checks"){$dialogName="lft";}
				else if($results[0]=="Urea and Electrolytes"){$dialogName="urea";}
				else if($results[0]=="Electrocardlogram"){$dialogName="ecg";}
				else if($results[0]=="Blood Pressure"){$dialogName="bp";}
				else if($results[0]=="Blood Pressure DIA"){$dialogName="bp";}
				else if($results[0]=="Pulse"){$dialogName="pulse";}
				else if($results[0]=="Weight/Body Mass Index"){$dialogName="bmi";}
			?>
				<tr><td class="infocol1"><?php echo("{$results[0]}");?></td>
				<td class="infocol1" style="float:right;"><?php if($results[0]!="Blood Pressure DIA") { ?>
				<a href='#' id='<?php echo("{$dialogName}");?>-link' class='dialog-link' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-newwin' style='float:right;'></span></a>
				<div id='<?php echo("{$dialogName}");?>' class='dialog' title='<?php echo("{$results[0]}");?>'>
					<p>Reason:<br><?php echo("{$results[1]}");?></p>
					<p>Minimum Frequency:<br><?php echo("{$results[2]}");?></p>
				</div><?php } ?></td>
				<td class="infocol2"<?php echo("{$results[3]}");?></td></tr>
<?php 		} ?>
			</table>
<?php			mysql_free_result($displayResults);
		}
		else {
			echo "an error occurred".mysql_error();
		} ?><br><br>
		<table class=log><tr><th class="infocol1">Comments</th></tr>
		<tr><td class="infocol2"><?php echo("{$details[5]}"); ?></td></tr>
		</table>
<?php
		} 
		mysql_free_result($displayDetails);
		}
		else {
			echo "an error occurred".mysql_error();
		}
	}
	else{
		$testDate = "";
	}
}
include "footer.php";

?>