<form name="findMedication" method="post" action="<?php echo($action);?>">
<table class="log">
<tr class="logrow"><td class="logcol1">Medication Type</td>
	<td><select name="medType">
		<option value="blank">Select a type</option>
		<?php 
			$medList = "SELECT Medication FROM PatientMedication WHERE PatientID='".$_SESSION['patient']."' GROUP BY Medication";
			
			$medQuery = mysql_query($medList);
				if ($medQuery) {
					//lists all of a patients mediations so the one can be selected for whatever purpose.
					while ($row = mysql_fetch_array($medQuery)) {
						echo "<option value='{$row[0]}'>{$row[0]}</option>/n";}
					mysql_free_result($medQuery);}
				else {
					echo "an error occurred".mysql_error();}
			
		?>
	</select></td></tr>
	<td colspan=2><input type="submit" name="findMed" value="List Tests"></td></tr>
</table>
</form> 