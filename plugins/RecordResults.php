<?php if((isset($_POST['findMed']))||(isset($_POST['record']))){

/*this form is largely irrelevant now, but it contains some code I had issues with which you may find of some use if you need to highlight that kind of thing
//the idea was to dynamically produce all of the tests relating to the selected type of medication within this form without having to make
//specific forms for each type.
//this recorded the use of php arrays as the php code showed extensive errors with plugging in php variables from sql results into the 
<input type='text' name={variable}> part. so instead I was using a variable name as the intake
*/

			$result=array(); //an array to record the result of each test
			$testnames=array(); //another to record the name of the test
 ?>
<form name="NewResults"	method=post action="<?php echo($action);?>">
<table>
<tr><td>Brand of <?php echo($medication);?></td>
	<td><select name="brand">
			<option value="blank">Select a brand</option>
<?php
$getBrands="SELECT MedBrand From Medications WHERE Medication='{$medication}'";
	$brandList = mysql_query($getBrands);
		if($brandList){
			while($listBrands = mysql_fetch_array($brandList)){
				$selected="";
				if($brand==$listBrands[0]){ $selected = " selected='selected'"; }
				echo ("<option value='".$listBrands[0]."'".$selected.">".$listBrands[0]."</option>\n");
			}
			mysql_free_result($brandList);}
		else{
			echo "An error occurred ".mysql_error();}
?>
		</select>
	</td></tr>
<tr><td>Dose(mg)</td><td><input type="text" name="dosage"></td></tr></table>
<?php
if(isset($_POST['record'])){
	$d = date('Y/m/d G:i:s', time());
	$brand = $_POST['brand'];
	$dosage = $_POST['dosage'];
	$comment = $_POST['comments'];
	
	if($brand=="blank"||$dosage==""||$comment==""){
		echo("fill in details");
	}
	else if($brand=="blank"||$dosage==""||$comment==""){
		echo("with the right stuff in them");
	}
	else{
	$insertRecord = "INSERT INTO RecordsDetail (PatientID, DoctorID, Date, MedBrand, Dose, Comments) 
	VALUES ('{$_SESSION['patient']}', '{$_SESSION['doctor']}', '{$d}', '{$brand}', {$dosage}, '{$comment}')";	
	$insertRecordSQL = mysql_query($insertRecord) or die(mysql_error());}
}
else{
	$date="";
	$brand="";
	$dosage="";
	$comment="";
}

$findTests="SELECT td.TestName, td.Reason, td.PriorToStarting, td.MinimumFrequency, td.YearlyCheck, tr.Result, td.Exception, td.Medication
			FROM TestDescription AS td 
			LEFT JOIN TestRecords AS tr ON tr.TestName=td.TestName AND tr.Medication=td.Medication 
			LEFT JOIN RecordsDetail AS rd ON tr.RecordID=rd.RecordID
			WHERE td.Medication='{$medication}' GROUP BY td.TestName";
			
	$listTests=mysql_query($findTests);
			if($listTests){	
?>	
			<table><tr><th>Test Name</th><th>Most Recent Result</th><th>New Result</th></tr>
<?php			while($testList = mysql_fetch_array($listTests)){
					echo("<tr><td>{$testList[0]}</td><td>{$testList[7]}</td><td><input type='text' name='testresult[]'></td></tr>");
					$testnames[] = $testList[0]; 
				}
				mysql_free_result($listTests);
			}
			else{
				echo "An error occurred ".mysql_error();}
?>	</table>
<?php	
			if(isset($_POST['record'])){
				$findRecordID = "SELECT MAX(RecordID) FROM RecordsDetail WHERE PatientID='{$_SESSION['patient']}'";
				$RecordID = mysql_query($findRecordID);
				list($record_id) = mysql_fetch_array($RecordID);
				for($i=0; $i<count($_POST['testresult']); $i++){
					$result[$i] = $_POST['testresult'][$i];
					//$_POST['testresult'] takes in an array due to the [] located after, and the results array should also be taking in a value
				}
				for($j=0; $j<count($result[]); $j++){
					if($result[$j]!=""){
					$InsertResult = "INSERT INTO TestRecords (RecordID, TestName, Medication, Result) 
					VALUES({$record_id}, '{$testnames[$j]}', '{$medication}', {$result[$j]})"; 
					//testname and medication show undefined offset errors, but it received the first value
					//for some completely unfathomable reason, it doesn't take in the testname or medication no matter what varient I tried
					$sqlInsert = mysql_query($InsertResult) or die(mysql_error());
					}
				}
			}
?>
<table><tr><th colspan=2>Comments</th></tr>
<tr><td colspan=2><textarea name="comments"></textarea></td></tr>
<tr><td></td><td><input type="submit" name="record" value="Record Details"></td></tr>
</table>
</form>
<?php }
else{
	include "zFindMed.php";
	$date="";
	$brand="";
	$dosage="";
	$comment="";
} 
?>