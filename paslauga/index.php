<?php
//NAUJOS PASLAUGOS REGISTRAVIMAS
error_reporting(E_WARNING | E_PARSE); // nerodomi notice's
include ("mysql.php");
// 1. atsiranda lentele su duombazes selectu
$SQL_SELECT="SELECT * FROM `paslauga` ORDER BY `PASLAUGA_ID`"; 
$SQL_REQ=mysqli_query($con, $SQL_SELECT);
$ROWS=mysqli_num_rows($SQL_REQ);
while ($row = mysqli_fetch_assoc($SQL_REQ)) {
$tb_v.=' <tr> 
    <td>'.$row['PASLAUGA_ID'].'</td>
    <td>'.$row['PASLAUGA_PAVADINIMAS'].'</td> 
    <td>'.$row['PASLAUGA_KAINA'].'</td>
	<td>'.$row['PASLAUGA_TRUKME'].'</td>
	<td><a href="index.php?red='.$row['PASLAUGA_ID'].'">Redaguoti</a></td>
    <td><a href="index.php?del='.$row['PASLAUGA_ID'].'">Ištrinti</a></td>	
  </tr>';
}
$tab_full='<table style="border: 1px solid#000">'.$tb_v.'</table>';
//2. paspaudus redaguoti prie eilutes - nukreipimas I eilutes redagavimo laukelius
if ($_GET['red']!= "") {
	$SQL_SELECT2="SELECT * FROM `paslauga` WHERE PASLAUGA_ID='".$_GET['red']."'";
$SQL_REQ2=mysqli_query($con,$SQL_SELECT2);
$RED_EDIT=mysqli_fetch_array($SQL_REQ2); //atrenka norima eilute
print_r ($RED_EDIT); //array atvaizdavimas
}
//4. issaugotu duomenu uzkrovimas
$ivesti = "
<form action='' method='post'>
      Paslauga: <input name='paslauga' maxlength='40' value='$RED_EDIT[1]' /><br>
		Kaina: <input name='kaina' maxlength='50' value='$RED_EDIT[2]'/><br>
		Trukmė: <input name='trukme' maxlength='40' value='$RED_EDIT[3]'/><br><br>
		<input type='submit' value='Įvesti' name='ivesti'>  
				 <br><br>
</form>
";
echo $ivesti;
echo $tab_full;
// 3. ivedamos reiksmes I laukelius paspaudus ivesti
if ($_POST['ivesti']) { 
$paslauga = $_POST['paslauga'];
$kaina = $_POST['kaina'];
$atsakymas = $_POST['trukme'];
if ($_POST['paslauga'] == "") { $er = "Klaida: neįvedėte paslaugos pavadinim";}
 else 
	 if ($_GET['red'] == "") {	//iraso duomenis i MySQL is formos
		$er = "Registracija sekminga";
		mysqli_query($con, "INSERT INTO paslauga (PASLAUGA_PAVADINIMAS,PASLAUGA_KAINA,PASLAUGA_TRUKME) VALUES ('$paslauga','$kaina','$trukme')") or 	mysqli_error($con, "Klaida ".__FILE__.' Eilute '.__LINE__ );
	
	echo "
	<h2>Registracijos ataskaita</h2><hr>
	$er<hr>
	<a href='index.php?psl=paslauga'>Atgal</a><br>
	<a href='index.php'>Į pradžią</a>
	"; 
}
//5. issaugotu duomenu redagavimas
    else {
	if ($_GET['red'] != "") {
	//atnaujina duomenis
$er = "Registracija sekminga";
		mysqli_query($con, "UPDATE `paslauga` SET `PASLAUGA_PAVADINIMAS`='$paslauga',`PASLAUGA_KAINA`='$kaina',`PASLAUGA_TRUKME`='$trukme' WHERE `PASLAUGA_ID`='".$_GET['red']."'") or 	mysqli_error($con, "Klaida ".__FILE__.' Eilute '.__LINE__ );
	
	echo "
	<h2>Registracijos ataskaita</h2><hr>
	$er<hr>
	<a href='index.php?psl=paslauga'>Atgal</a><br>
	<a href='index.php'>Į pradžią</a>";
//6. atsinaujina irasai perkrovus, virsuj pasirasant ob_start();
        } 
    } header("location: index.php");
}
//7. duomenu istrynimas
if ($_GET['del'] != "") { 
	mysqli_query($con, "DELETE FROM `paslauga` WHERE `PASLAUGA_ID`='".$_GET['del']."'") or 	mysqli_error($con, "Klaida ".__FILE__.' Eilute '.__LINE__ );
	header("location: index.php");
}  
mysqli_close($con);
?>