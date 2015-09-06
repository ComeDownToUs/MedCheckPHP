<?php include "header.php";
//this page just kills the session and redirects to the login page.
session_unset();
session_destroy();

header("Location: Login.php");
?>
You have logged out, you are now being redirected to the log-in page.
<?php
include "footer.php";
?>