<?php include "header.php";

if(isset($_SESSION['patient'])){
	unset($_SESSION['patient']);
	unset($_SESSION['verified']);
	header("Location: DropPatient.php");}
	//a page for unsetting patient variables, meant to contain a timed redirect but I must have forgotten
?>
You have logged out of this patient.
<?php
include "footer.php";
header("Location: PracHome.php");
?>