<?php
//KLIENTO REGISTRAVIMAS
error_reporting(E_WARNING | E_PARSE); // nerodomi notice's
include ("mysql.php");
// 1. atsiranda lentele su duombazes selectu
$SQL_SELECT="SELECT * FROM `klientas` ORDER BY `KLIENTAS_ID`"; 
$SQL_REQ=mysqli_query($con, $SQL_SELECT);
$ROWS=mysqli_num_rows($SQL_REQ);
while ($row = mysqli_fetch_assoc($SQL_REQ)) {
$tb_v.=' <tr> 
<td style="border: 1px solid #000">'.$row['KLIENTAS_ID'].'</td>
    <td style="border: 1px solid #000">'.$row['KLIENTAS_VARDAS'].'</td> 
    <td style="border: 1px solid #000">'.$row['KLIENTAS_PAVARDE'].'</td>
	<td style="border: 1px solid #000">'.$row['KLIENTAS_KODAS'].'</td>
	<td style="border: 1px solid #000">'.$row['KLIENTAS_ADRESAS'].'</td>
	<td style="border: 1px solid #000">'.$row['KLIENTAS_PVM_KODAS'].'</td>
	<td style="border: 1px solid #000">'.$row['KLIENTAS_SASKAITA'].'</td>
	<td><a href="index.php?red='.$row['KLIENTAS_ID'].'">Redaguoti</a></td>
    <td><a href="index.php?del='.$row['KLIENTAS_ID'].'">Ištrinti</a></td>	
  </tr>';
}
$tab_full='<table style="border: 1px solid#000; border-collapse: collapse;">'.$tb_v.'</table>';
//2. paspaudus redaguoti prie eilutes - nukreipimas I eilutes redagavimo laukelius
if ($_GET['red']!= "") {
	$SQL_SELECT2="SELECT * FROM `klientas` WHERE `KLIENTAS_ID`='".$_GET['red']."'";
$SQL_REQ2=mysqli_query($con,$SQL_SELECT2);
$RED_EDIT=mysqli_fetch_array($SQL_REQ2); //atrenka norima eilute
print_r ($RED_EDIT); //array atvaizdavimas
}
//4. issaugotu duomenu uzkrovimas
$ivesti = "
<form action='' method='post'>
      Vardas: <input name='vardas' maxlength='40' value='$RED_EDIT[1]' /><br>
		Pavardė: <input name='pavarde' maxlength='50' value='$RED_EDIT[2]' /><br>
		Kodas: <input name='kodas' maxlength='40' value='$RED_EDIT[7]'/><br>
		Telefonas: <input name='telefonas' value='$RED_EDIT[3]'/><br>
		Adresas: <input name='adresas' value='$RED_EDIT[4]' /><br>
		PVM Kodas: <input name='pvm' value='$RED_EDIT[5]'/><br>
		Saskaita: <input name='saskaita' value='$RED_EDIT[6]'/><br><br>
		<input type='submit' value='Įvesti' name='ivesti'>  
				 <br><br>
</form>
";
echo $ivesti;
echo $tab_full;
// 3. ivedamos reiksmes I laukelius paspaudus ivesti
if ($_POST['ivesti']) { 
$vardas = $_POST['vardas'];
$pavarde = $_POST['pavarde'];
$telefonas = $_POST['telefonas'];
$adresas = $_POST['adresas'];
$pvm = $_POST['pvm'];
$saskaita = $_POST['saskaita'];
$kodas = $_POST['kodas'];
if ($_POST['vardas'] == "") { $er = "Klaida: neįvedėte kliento vardo";}
 else 
	 if ($_GET['red'] == "") {	//iraso duomenis i MySQL is formos
		$er = "Registracija sekminga";
		mysqli_query($con, "INSERT INTO klientas (KLIENTAS_VARDAS,KLIENTAS_PAVARDE,KLIENTAS_TELEFONAS,KLIENTAS_KODAS,KLIENTAS_ADRESAS,KLIENTAS_PVM_KODAS,KLIENTAS_SASKAITA) VALUES ('$vardas','$pavarde','$telefonas','$kodas','$adresas','$pvm','$saskaita')") or mysqli_error($con, "Klaida ".__FILE__.' Eilute '.__LINE__ );
	
	echo "
	<h2>Registracijos ataskaita</h2><hr>
	$er<hr>
	<a href='index.php'>Į pradžią</a>
	"; 
}
//5. issaugotu duomenu redagavimas
    else {
	if ($_GET['red'] != "") {
	//atnaujina duomenis
$er = "Registracija sekminga";
		mysqli_query($con, "UPDATE `klientas` SET KLIENTAS_VARDAS='$vardas',KLIENTAS_PAVARDE='$pavarde',KLIENTAS_TELEFONAS='$telefonas',KLIENTAS_KODAS='$kodas',KLIENTAS_ADRESAS='$adresas',KLIENTAS_PVM_KODAS='$pvm',KLIENTAS_SASKAITA='$saskaita' WHERE `KLIENTAS_ID`='".$_GET['red']."'") or mysqli_error($con, "Klaida ".__FILE__.' Eilute '.__LINE__ );
	
	echo "
	<h2>Registracijos ataskaita</h2><hr>
	$er<hr>
	<a href='index.php'>Į pradžią</a>";
//6. atsinaujina irasai perkrovus, virsuj pasirasant ob_start();
        } 
    } header("location: index.php");
}
//7. duomenu istrynimas
if ($_GET['del'] != "") { 
	mysqli_query($con, "DELETE FROM `klientas` WHERE `KLENTAS_ID`='".$_GET['del']."'") or 	mysqli_error($con, "Klaida ".__FILE__.' Eilute '.__LINE__ );
	header("location: index.php");
}  
mysqli_close($con);
?>