<?php
error_reporting(E_WARNING | E_PARSE); // nerodomi notice's
include ("mysql.php");
@extract($_GET);
if (isset($psl)) {
	$psl = $psl;
} else {
	$psl = "";
}
if ($psl == "") {
	echo "
	<h2>Inzinerijos puslapis</h2><hr>
	<b>Navigacija</b><br><br>
	<a href='index.php?psl=paslauga'>Nauja paslauga<br></a> 
	<a href='index.php?psl=uzsakymas'>Naujas užsakymas<br></a>
	<a href='index.php?psl=klientas'>Kliento duomenys</a>
	";
}
//Naujos PASLAUGOS ivedimas
if ($psl == "paslauga") {
	echo "
	<h2>Nauja paslauga</h2><hr>
	<form action='index.php?psl=paslauga2' method='post'>
	    Paslauga: <input name='paslauga' maxlength='40' /><br>
		Kaina: <input name='kaina' maxlength='50' /><br>
		Trukmė: <input name='trukme' maxlength='40' /><br><br>
		<input type='submit' value='Įvesti' name='ivesti'>
	</form>
	<hr>
	<a href='index.php'>I pradzia</a>
	";
}
if ($psl == "paslauga2") {
	@extract($_POST);
	if (isset($ivesti)) {
		$row = mysqli_fetch_assoc(mysqli_query($con, "select * from paslauga where PASLAUGA_PAVADINIMAS='$paslauga'"));
	}
	if ($paslauga == "") { $er = "Klaida: neįvedėte paslaugos pavadinimo"; }
	elseif ($kaina == "") { $er = "Klaida: neįvedėte kainos"; }
	elseif ($trukme == "") { $er = "Klaida: neįvedėte paslaugos"; }
	else {
		$er = "Registracija sekminga";
		mysqli_query($con, "INSERT INTO paslauga (PASLAUGA_PAVADINIMAS,PASLAUGA_KAINA,PASLAUGA_TRUKME) VALUES ('$paslauga','$kaina','$trukme')") or mysqli_error($con, "Klaida ".__FILE__.' Eilute '.__LINE__ );
	}
	echo "
	<h2>Registracijos ataskaita</h2><hr>
	$er<hr>
	<a href='index.php?psl=paslauga'>Atgal</a><br>
	<a href='index.php'>Į pradžią</a><br>
	"; 
//atsiranda `paslauga` lentele is duombazes 
$SQL_SELECT="SELECT * FROM `paslauga` ORDER BY `PASLAUGA_ID`"; 
 $SQL_REQ=mysqli_query($con, $SQL_SELECT);
 $ROWS=mysqli_num_rows($SQL_REQ);
 while ($row = mysqli_fetch_assoc($SQL_REQ)) {
$tb_v.=' 
<tr> 
    <td>'.$row['PASLAUGA_ID'].'</td>
    <td>'.$row['PASLAUGA_PAVADINIMAS'].'</td> 
    <td>'.$row['PASLAUGA_KAINA'].'</td>
	<td>'.$row['PASLAUGA_TRUKME'].'</td>
  </tr>';
}
$tab_full='<br><table style="border: 1px solid#000"><th>ID<th/>'.$tb_v.'</table>';
echo $tab_full;
}
//UZSAKYMO registravimas
if ($psl == "uzsakymas") {
	echo "
	<h2>Užsakymas</h2><hr>
	<form action='index.php?psl=uzsakymas2' method='post'>
	    Užsakymas: <input name='uzsakymas' maxlength='40' /><br>
		Patvirtinimo data: <input name='data' maxlength='50' /><br>
		Kiekis: <input name='kiekis' maxlength='40' /><br><br>
		<input type='submit' value='Įvesti' name='ivesti'>
	</form>
	<hr>
	<a href='index.php'>I pradzia</a>
	";
}
if ($psl == "uzsakymas2") {
	if ($_POST['ivesti']) { 
$uzsakymas = $_POST['uzsakymas'];
$data = $_POST['data'];
$kiekis = $_POST['kiekis'];
	
	$row = mysqli_fetch_assoc(mysqli_query($con, "Select * from `uzsakymas` WHERE UZSAKYMAS_PAVADINIMAS='$uzsakymas'"));
	if ($uzsakymas == "") { $er = "Klaida: neįvedėte uzsakymo pavadinimo"; }
	elseif ($kiekis == "") { $er = "Klaida: neįvedėte uzsakymų kiekio"; }
	else {
		$er = "Registracija sekminga";
		mysqli_query($con, "INSERT INTO `uzsakymas` (`UZSAKYMAS_ID`, `UZSAKYMAS_PAVADINIMAS`, `UZSAKYMAS_KIEKIS`, `UZSAKYMAS_PATVIRT_DATA`, `KLIENTAS_ID`, `BUSENA_ID`, `GEDIMAS_ID`, `DARBUOTOJAS_ID`, `PASLAUGA_ID`) VALUES (NULL, '$uzsakymas', '$kiekis', '$data', NULL, NULL, NULL, NULL, NULL);") or mysqli_error($con, "Klaida ".__FILE__.' Eilute '.__LINE__ );
	}
	echo "
	<h2>Registracijos ataskaita</h2><hr>
	$er<hr>
	<a href='index.php?psl=uzsakymas'>Atgal</a><br>
	<a href='index.php'>Į pradžią</a>
	"; 
	}
	//atsiranda `uzsakymas` lentele is phpmyadmin duombazes 
$SQL_SELECT="SELECT * FROM `uzsakymas` ORDER BY `UZSAKYMAS_ID`"; 
 $SQL_REQ=mysqli_query($con, $SQL_SELECT);
 $ROWS=mysqli_num_rows($SQL_REQ);
 while ($row = mysqli_fetch_assoc($SQL_REQ)) {
$tb_v.=' <tr> 
    <td style="border: 1px solid #000">'.$row['UZSAKYMAS_ID'].'</td>
    <td style="border: 1px solid #000">'.$row['UZSAKYMAS_PAVADINIMAS'].'</td> 
    <td style="border: 1px solid #000">'.$row['UZSAKYMAS_KIEKIS'].'</td>
	<td style="border: 1px solid #000">'.$row['UZSAKYMAS_PATVIRT_DATA'].'</td>
  </tr>';
}
$tab_full='<table style="border: 1px solid #000; border-collapse: collapse;">'.$tb_v.'</table>';
echo $tab_full;
}
if ($psl == "klientas") {
	echo "
	<h2>Kliento duomenų įvedimas</h2><hr>
	<form action='index.php?psl=klientas2' method='post'>
	    Vardas: <input name='vardas' maxlength='40' /><br>
		Pavardė: <input name='pavarde' maxlength='50' /><br>
		Kodas: <input name='kodas' maxlength='40' /><br>
		Telefonas: <input name='telefonas' /><br>
		Adresas: <input name='adresas' /><br>
		PVM Kodas: <input name='pvm' /><br>
		Saskaita: <input name='saskaita' /><br><br>
		<input type='submit' value='Įvesti' name='ivesti'>
	</form>
	<hr>
	<a href='index.php'>I pradzia</a>
	";
}
//KLIENTO duomenu ivedimas
if ($psl == "klientas2") {
	@extract($_POST);
	if (isset($ivesti)) {
		$row = mysqli_fetch_assoc(mysqli_query($con, "select * from klientas where KLIENTAS_VARDAS='$vardas'"));
	}
	if ($vardas == "") { $er = "Klaida: neįvedėte paslaugos pavadinimo"; }
	// elseif ($kaina == "") { $er = "Klaida: neįvedėte kainos"; }
	// elseif ($trukme == "") { $er = "Klaida: neįvedėte paslaugos"; }
	else {
		$er = "Registracija sekminga";
		mysqli_query($con, "INSERT INTO klientas (KLIENTAS_VARDAS,KLIENTAS_PAVARDE,KLIENTAS_TELEFONAS,KLIENTAS_KODAS,KLIENTAS_ADRESAS,KLIENTAS_PVM_KODAS,KLIENTAS_SASKAITA) VALUES ('$vardas','$pavarde','$telefonas','$kodas','$adresas','$pvm','$saskaita')") or mysqli_error($con, "Klaida ".__FILE__.' Eilute '.__LINE__ );
	}
	echo "
	<h2>Registracijos ataskaita</h2><hr>
	$er<hr>
	<a href='index.php?psl=klientas'>Atgal</a><br>
	<a href='index.php'>Į pradžią</a>
	"; 
	//atsiranda `uzsakymas` lentele is phpmyadmin duombazes 
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
  </tr>';
}
$tab_full='<br><table style="border: 1px solid #000; border-collapse: collapse;">'.$tb_v.'</table>';
echo $tab_full;
}
mysqli_close($con);
?>