<?php 
//lists the results for the results page after a date has been selected
	$testDetails = "SELECT r.RecordID, r.Date, r.MedBrand, r.Dose, d.FName, d.LName, r.Comments FROM RecordsDetail AS r
	LEFT JOIN Staff AS d ON d.StaffID=r.DoctorID WHERE PatientID='".$_SESSION['patient']."' AND Date='".$testDate."'";

	$displayDetails = mysql_query($testDetails);

	if($displayDetails){
	while ($details = mysql_fetch_array($displayDetails)){
		$recordID = $details[0];
?>
		<table class="log">
		<tr class="logrow"><td class="logcol1">Test #</td><td><?php echo("{$details[0]}"); ?></td></tr>
		<tr class="logrow"><td class="logcol1">Date</td><td><?php echo("{$details[1]}");?></td></tr>
		<tr class="logrow"><td class="logcol1">Brand</td><td><?php echo("{$details[2]}");?></td></tr>
		<tr class="logrow"><td class="logcol1">Dose (mg)</td><td><?php echo("{$details[3]}");?></td></tr>
		<tr class="logrow"><td class="logcol1">Results Recorded by: </td><td><?php echo("{$details[4]} {$details[5]}");?></td></tr>
		</table></br>
<?php 

			$findResults = "SELECT d.TestName, d.Reason, d.MinFreqElab, r. Result
			FROM TestRecords AS r LEFT JOIN TestDescription AS d ON r.TestName=d.TestName
			WHERE RecordID='{$recordID}'";
			//Exception entry to create an if exception system to exclude things such as the full blood count if required

		$displayResults = mysql_query($findResults);

		if($displayResults){ ?>
			<table class=log>
			<tr><th>Test</th><th>Reasons</th><th>Results</th><tr>
<?php
			while ($results = mysql_fetch_array($displayResults)){
				if($results[0]=="Blood Sugar"){$dialogName="bs";}
				else if($results[0]=="Blood Lipids"){$dialogName="bl";}
				else if($results[0]=="Full Blood Counts"){$dialogName="fbc";}
				else if($results[0]=="Liver Checks"){$dialogName="lft";}
				else if($results[0]=="Urea and Electrolytes"){$dialogName="urea";}
				else if($results[0]=="Electrocardlogram"){$dialogName="ecg";}
				else if($results[0]=="Blood Pressure"){$dialogName="bp";}
				else if($results[0]=="Pulse"){$dialogName="pulse";}
				else if($results[0]=="Weight/Body Mass Index"){$dialogName="bmi";}
			?>
				<tr><td><?php echo("{$results[0]}");?></td>
				<td>
				<a href='#' id='<?php echo("{$dialogName}");?>-link' class='dialog-link' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-help ui-state-default' style='float:right;'></span></a>
				<div id='<?php echo("{$dialogName}");?>' class='dialog' title='<?php echo("{$results[0]}");?>'>
					<p>Reason:<br><?php echo("{$results[1]}");?></p>
					<p>Minimum Frequency:<br><?php echo("{$results[2]}");?></p>
				</div></td>
				<td><?php echo("{$results[3]}");?></td></tr>
<?php 		} ?>
			</table>
<?php			mysql_free_result($displayResults);
		}
		else {
			echo "an error occurred".mysql_error();
		} ?><br><br>
		<table class=log><tr><th>Comments</th></tr>
		<tr><td><?php echo("{$details[5]}"); ?></td></tr>
		</table>
<?php
		} 
		mysql_free_result($displayDetails);
		}
		else {
			echo "an error occurred".mysql_error();
		}
?>