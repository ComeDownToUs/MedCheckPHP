<?php
include "header.php";

/*the tests page contains all of the data which relates to the test records table in the database, 
it also connects to the $_SESSION['medication']'s input from the plug ins folder
*/
	if(isset($_POST['record'])){
		$date = date('Y/m/d G:i:s', time());
		$brand = $_POST['brand'];
		$dosage = $_POST['dosage'];
		$comment = $_POST['comments'];
		$validation=0;
	}
	else{
		$date = "";
		$brand = "blank";
		$dosage = "";
		$comment = "";
	}
?>
<form name="NewResults"	method=post action="Tests.php">
<table class="log">
<tr><td>Brand of <?php echo("{$_SESSION['medication']}");?></td><td></td>
	<td><select name="brand">
			<option value="blank">Select a brand</option>
<?php
//this lists out all of the different brands of the type of medication which has been selected
	$getBrands="SELECT MedBrand From Medications WHERE Medication='{$_SESSION['medication']}'";
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
<?php
if($brand=="blank"){
	echo("<tr><td colspan='3' class='alertrow'>Please select a brand of {$_SESSION['medication']}</td></tr>");
}
?>
<tr><td>Dose(mg)</td><td></td><td><input type="text" name="dosage" value="<?php echo $dosage;?>"></td></tr>
<?php
if($dosage==""||!is_numeric($dosage)){
	echo("<tr><td colspan='3' class='alertrow'>Please enter a valid numeric value into the dosage field</td></tr>");
}
else if(is_numeric($dosage)&&($dosage>2000)){
	echo("<tr><td colspan='3' class='alertrow'>Please enter a numeric value into the dosage field between 0 and 2000</td></tr>");
}
?>
<tr><th>Name</th><td></td><th>Input New Result</th></tr>

<?php 
/*
Using the include functions and a consistent file naming setup within the plugins folder, we are able to reuse this much of the data for
recording the tests for numerous medications, if desired.
*/	
	
	include "plugins/{$_SESSION['medication']}.php"; //ie. "include 'plugins/Clozapine.php'"
?>

<tr><th colspan=3>Comments</th></tr>
<tr><td colspan=3><textarea name="comments" style="width: 100%;"><?php echo $comment;?></textarea></td></tr>
<?php
if($comment==""||strlen($comment)<10){
	echo("<tr><td colspan='3' class='alertrow'>Please enter a comment of at least 10 characters</td></tr>");
}
?>
<tr><td colspan=3><input type="submit" name="record" value="Record Details"></td></tr>
</table>
</form>
<?php
	if($recordss=="submitted"){
		header("Location: PracPatient.php");
	}//redirects from the page once a record has been submitted to ensure the $recordss variable is reset for the next entry

include "footer.php";
?>

