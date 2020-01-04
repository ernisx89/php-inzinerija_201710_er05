<?php //prisijungimas prie duombazes phpmyadmin
$con = mysqli_connect("localhost", "root", "") or die("Klaida, prisijunti nepavyko");
mysqli_select_db($con, "inzinerija");
?>