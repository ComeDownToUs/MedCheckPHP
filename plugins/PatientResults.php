<br><br><?php
/**
	Selects a mediation for the session variable and forwards to the results page
**/

if($_SESSION['type']=="p"){$action = "PatientHome.php";} 
else{$action = "PracPatient.php";}


	if (isset($_POST['findMed'])){
		$_SESSION['medication'] = $_POST['medType'];
		if($_SESSION['medication']!="blank"){
			header("Location: Results.php");
	}
		else{
		unset($_POST['findMed']);
		echo("Select a valid option from the list");}
	}
	else{
		$medication = "";}
		
	if(!isset($_POST['findMed'])){
		include "FindMed.php";
	}
?>