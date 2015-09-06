<?php
include "header.php";

//this page is by far the biggest example of using code that had uses in other places

//for some reason this update option doesn't work in either of the places I've put it. Absolutely no clue why, the code works on phpmyadmin
if(isset($_POST['verRel'])){
	$verRSQL="UPDATE UserAccess SET Verified=1 WHERE PatientID={$_SESSION['patient']} AND StaffID={$_SESSION['practitioner']}";
	$verSQL=mysql_query($verRSQL);
}
$action = "PatientPrac.php";
//the $action variable is used on plugin forms so that they process the form through the webpage which is including the form

include "plugins/SelectPrac.php"; //this form lists out the practitioners the patient is related to

include "plugins/PracDetails.php"; //this form lists out the details of the selected practitioner

include "plugins/PatRel.php"; //this form lists out the details of their relationship

include "footer.php";
?>