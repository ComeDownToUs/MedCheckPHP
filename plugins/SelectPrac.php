<br><br><?php
//select practioner form, this lists out all of the practitioners which have a relationship with the logged in patient

if(isset($_POST['PracView'])){
	$option = $_POST['pickPrac'];
	if($option!="blank"){
	$_SESSION['practitioner'] = $_POST['pickPrac'];
	header("Location: PatientPrac.php");
	}
}
?>
<form name="viewPrac" method="post" action="<?php echo "{$action}";?>">
<table class=log>
<tr><td>
	<select name="pickPrac">
		<option value="blank">Select a Prescriber</option>
<?php
$findPracs = "SELECT s.StaffID, s.FName, s.LName, s.Location FROM Staff AS s LEFT JOIN UserAccess AS ua ON s.StaffID=ua.StaffID WHERE ua.PatientID={$_SESSION['patient']}";
$findPracsSQL = mysql_query($findPracs);
if($findPracsSQL){
	while($row=mysql_fetch_array($findPracsSQL)){
		echo "<option value='{$row[0]}'>{$row[1]} {$row[2]} -- {$row[3]}</option>";
	}
	mysql_free_result($findPracsSQL);
}
else{
}

?>		
	</select></td>
<?php
if(isset($option)){
	if($option=="blank"){
	echo "<tr><td class='alertrow'>Please select a valid option</td></tr>";}
}
?>
<tr><td><input type=submit value="View Prescriber Details" name="PracView"></td></tr></table>
<br><br>