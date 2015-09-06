<?php 
/**
	Plug-in page to display information of a given profession, withhold some information for patient login perhaps?
	
**/
if($_SESSION['practitioner']!="blank"){
$docDeets = "Select s.StaffID, s.FName, s.LName, s.DOB, s.Phone, s.EMail, s.Position, s.Location, l.Address1, l.Address2, l.County 
	FROM Staff AS s LEFT JOIN Location AS l ON s.Location = l.LocName 
	WHERE StaffID='".$_SESSION['practitioner']."'";
	
$details = mysql_query($docDeets); 
if($details){ 
while ($result = mysql_fetch_array($details)) {?>
<table class="info">	
		<tr><th colspan="2" class="infoheader">Personal Information</th></tr>
		<tr><td class="infocol1">ID</td><td class="infocol2"><?php echo("{$result[0]}");?></td></tr>
		<tr><td class="infocol1">Name</td><td class="infocol2"><?php echo("{$result[1]} {$result[2]}");?></td></tr>
		<tr><td class="infocol1">Date of Birth</td><td class="infocol2"><?php echo("{$result[3]}");?></td></tr>
		<tr><td class="infocol1">Phone</td><td class="infocol2"><?php echo("{$result[4]}");?></td></tr>
		<tr><td class="infocol1">Email</td><td class="infocol2"><?php echo("{$result[5]}");?></td></tr>
		<tr><td colspan="2"></td></tr>
		<tr><th colspan="2" class="infoheader">Work Details</th></tr>
		<tr><td class="infocol1">Position</td><td class="infocol2"><?php echo("{$result[6]}");?></td></tr>
		<tr><td class="infocol1">Workplace</td><td class="infocol2"><?php echo("{$result[7]}");?></td></tr>
		<tr><td class="infocol1">Address</td><td class="infocol2"><?php echo("{$result[8]}");?></td></tr>
</table>
<?php 
	$docname = $result[1]." ".$result[2];
	}
	mysql_free_result($details);
}
else{
}
}
?>