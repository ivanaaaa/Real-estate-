<?php
/**
 * Created by PhpStorm.
 * User: Ivana
 * Date: 08-Jan-18
 * Time: 4:48 PM
 */
include "connection.php";
require_once("./include/korisnicka_strana.php");


if (!$fgmembersite->CheckLogin()) {
    $fgmembersite->RedirectToURL("login.php");
    exit;
}

include "najaveniHeader.php";

$oglasi = "";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
    <title>Почетна страна</title>
    <link rel="STYLESHEET" type="text/css" href="style/fg_korisnicka_strana.css">
</head>
<body>
<div id='fg_membersite_content'>
    <div class="container">

        <div class = "left">
		<?php
		{
			$zapisi_naStrana =12;


			if(isset($_POST['filter'])){

				$kategorija = $tip_objekt = $enterier = $grad = "";


				if(isset($_POST['kategorija']))
					$kategorija = mysqli_real_escape_string($conn,$_POST['kategorija']);
				if(isset($_POST['tip_objekti']))
					$tip_objekt = mysqli_real_escape_string($conn,$_POST['tip_objekti']);
				if(isset($_POST['enterier']))
					$enterier = mysqli_real_escape_string($conn,$_POST['enterier']);
				if(isset($_POST['grad']))
					$grad = mysqli_real_escape_string($conn,$_POST['grad']);

				$cenaOd = mysqli_real_escape_string($conn,$_POST['cenaOd']);
				$cenaDo = mysqli_real_escape_string($conn,$_POST['cenaDo']);
				$povrshinaOd = mysqli_real_escape_string($conn,$_POST['povrshinaOd']);
				$povrshinaDo = mysqli_real_escape_string($conn,$_POST['povrshinaDo']);
				$brSobi = mysqli_real_escape_string($conn,$_POST['brSobi']);

				
				
				$sql = "SELECT *
						FROM oglasi INNER JOIN sliki ON (oglasi.oglasID = sliki.oglasID),kategorija,tip_objekt,enterier	
						WHERE oglasi.kategorija_id = kategorija.kategorija_id AND oglasi.tip_objekt_id = tip_objekt.tip_objekt_id AND oglasi.enterier_id = enterier.enterier_id AND oglasi.odobren = '1' ";

				$conditions = array();

				if(!empty($kategorija))
					$conditions[] = "kategorija.ime_kategorija = '$kategorija'";
				if(!empty($tip_objekt))
					$conditions[] = "tip_objekt.ime_objekt='$tip_objekt'";
				if(!empty($enterier))
					$conditions[] = "enterier.ime_enterier = '$enterier'";
				if(!empty($grad))
					$conditions[] = "oglasi.grad = '$grad'";
				if(!empty($cenaOd) &&  !empty($cenaDo))
					$conditions[] = "oglasi.cena BETWEEN '$cenaOd' AND '$cenaDo'";
				if(!empty($povrshinaOd) &&  !empty($povrshinaDo))
					$conditions[] = "oglasi.cena BETWEEN '$povrshinaOd' AND '$povrshinaDo'";
				if(!empty($brSobi))
					$conditions[] = "oglasi.broj_sobi >= '$brSobi'";

				$query = $sql;
				if(count($conditions) > 0){
					$sql .= " AND ".implode(' AND ',$conditions);
				}
				$sql .= " AND oglasi.odobren = '1' GROUP BY sliki.oglasID";  // LIMIT " . $stranaOD.','.$zapisi_naStrana;
				
				
				$sendSQL =  mysqli_query($conn,$sql)or die("Error");
				$oglasi = mysqli_num_rows($sendSQL);
				echo '<p style="margin-left:10px; margin-right:20px;margin-top:20px;font-size:20px; color:white; background-color:#dd6464;padding:5px; border-radius:4px;" ><strong>Вкупно огласи: ';
				echo $oglasi;
				echo '</strong></p>';
				
				$brojStrani = ceil($oglasi/$zapisi_naStrana);
				//tekovna strana
				if(!isset($_GET['strana'])){
					$strana = 1;
				}else{
					$strana = $_GET['strana'];
				}
				$stranaOD = ($strana-1)*$zapisi_naStrana;
				
				$sql .= " LIMIT " . $stranaOD.','.$zapisi_naStrana;

				$sendSQL =  mysqli_query($conn,$sql)or die("Error");		
				
				while ($row = mysqli_fetch_array($sendSQL)){
					echo "<a href='najaveniOglas.php?id=".$row['oglasID']. "'>";
					echo "<div class ='oglas'>";
					echo "<img  id='oglas_Slika' src='uploads/".$row['imeSlika']."' />";
					echo '<div class="oglas-text">';
					echo $row['naslov'];
					//if($row['cena'] == 0)
					switch($row['tip_cena']){
						case 'Евра': echo '<br>Цена: <div style="height:30px;padding:5px;display: inline; border-radius:4px; background-color:green;">'.$row['cena'] . ' &euro; </div>'; break;
						case 'По договор': echo '<br>Цена: <div style="height:30px;padding:5px;display: inline; border-radius:4px; background-color:yellow; color:black;">По договор</div>'; break;
					}
					echo "</div>";
					echo "</div>";
					echo "</a>";
				}
			}
			else if(isset($_POST['baraj_po_klucenZbor'])){
				
				$klucenZbor = mysqli_real_escape_string($conn,$_POST['klucenZbor']);
				
				$sql = mysqli_query($conn,"SELECT *
				FROM oglasi 
				INNER JOIN sliki ON (oglasi.oglasID = sliki.oglasID)
				WHERE oglasi.naslov LIKE '%$klucenZbor%' AND oglasi.odobren = '1' 
				GROUP BY sliki.oglasID
				") or die("Error");
				
				$oglasi = mysqli_num_rows($sql);
				echo '<p style="margin-left:10px; margin-right:20px;margin-top:20px;font-size:20px; color:white; background-color:#dd6464;padding:5px; border-radius:4px;" ><strong>Вкупно огласи: ';
				echo $oglasi;
				echo '</strong></p>';
				
				$brojStrani = ceil($oglasi/$zapisi_naStrana);
				//tekovna strana
				if(!isset($_GET['strana'])){
					$strana = 1;
				}else{
					$strana = $_GET['strana'];
				}
				$stranaOD = ($strana-1)*$zapisi_naStrana;
								
				
				$sql = mysqli_query($conn,"SELECT *
				FROM oglasi 
				INNER JOIN sliki ON (oglasi.oglasID = sliki.oglasID)
				WHERE oglasi.naslov LIKE '%$klucenZbor%' AND oglasi.odobren = '1'
				GROUP BY sliki.oglasID
				LIMIT ".$stranaOD.','.$zapisi_naStrana) or die("Error");
				
				while ($row = mysqli_fetch_array($sql)){
					echo "<a href='najaveniOglas.php?id=".$row['oglasID']. "'>";
					echo "<div class ='oglas'>";
					echo "<img id='oglas_Slika' src='uploads/".$row['imeSlika']."' />";
					echo '<div class="oglas-text">';
					echo $row['naslov'];
					//if($row['cena'] == 0)
					switch($row['tip_cena']){
						case 'Евра': echo '<br>Цена: <div style="height:30px;padding:5px;display: inline; border-radius:4px; background-color:green;">'.$row['cena'] . ' &euro; </div>'; break;
						case 'По договор': echo '<br>Цена: <div style="height:30px;padding:5px;display: inline; border-radius:4px; background-color:yellow; color:black;">По договор</div>'; break;
					}
					echo "</div>";
					echo "</div>";
					echo "</a>";
				}
			}
			else {
				$sql = mysqli_query($conn,"SELECT *
				FROM oglasi 
				INNER JOIN sliki ON (oglasi.oglasID = sliki.oglasID)
				WHERE  oglasi.odobren = '1'
				GROUP BY sliki.oglasID 
				") or die("Error");
				
				$oglasi = mysqli_num_rows($sql);
				echo '<p style="margin-left:10px; margin-right:20px;margin-top:20px;font-size:20px; color:white; background-color:#dd6464;padding:5px; border-radius:4px;" ><strong>Вкупно огласи: ';
				echo $oglasi;
				echo '</strong></p>';
				
				$brojStrani = ceil($oglasi/$zapisi_naStrana);
				//tekovna strana
				if(!isset($_GET['strana'])){
					$strana = 1;
				}else{
					$strana = $_GET['strana'];
				}
				$stranaOD = ($strana-1)*$zapisi_naStrana;
				
				$sql = mysqli_query($conn,"SELECT *
				FROM oglasi 
				INNER JOIN sliki ON (oglasi.oglasID = sliki.oglasID)
				WHERE  oglasi.odobren = '1'
				GROUP BY sliki.oglasID
				LIMIT ".$stranaOD.','.$zapisi_naStrana) or die("Error");
				

				
				while ($row = mysqli_fetch_array($sql)){
					echo "<a href='najaveniOglas.php?id=".$row['oglasID']. "'>";
					echo "<div class ='oglas'>";
					echo "<img id='oglas_Slika' src='uploads/".$row['imeSlika']."' />";
					echo '<div class="oglas-text">';
					echo $row['naslov'];
					//if($row['cena'] == 0)
					switch($row['tip_cena']){
						case 'Евра': echo '<br>Цена: <div style="height:30px;padding:5px;display: inline; border-radius:4px; background-color:green;">'.$row['cena'] . ' &euro; </div>'; break;
						case 'По договор': echo '<br>Цена: <div style="height:30px;padding:5px;display: inline; border-radius:4px; background-color:yellow; color:black;">По договор</div>'; break;
					}
					echo "</div>";
					echo "</div>";
					echo "</a>";
				}
			}
		}
		?>
		<div class="text-center" >

			<ul class="pagination">
				<li>
					<?php
					// echo '<a href="href = "index.php?strana='.($strana-1).'" aria-label="Previous">';
					// echo    '<span aria-hidden="true">&laquo;</span>';
					// echo '</a>';
					?>
				</li>
				<?php
				for($strana = 1;$strana <= $brojStrani;$strana++){

					//echo ' <li ><a href = "index.php?strana='.$strana.'">'.$strana.'</a></li>';
					?>
					<li <?php 
							if(isset($_GET['strana']) && $_GET['strana'] == $strana)
								echo 'class="active"';
							  elseif(!isset($_GET['strana']) && $strana == 1)
								  echo 'class="active"';
						
						?> >
					
					<?php echo '<a href = "login-home.php?strana='.$strana.'">'.$strana.'</a>'; ?>
					
					 </li>
				<?php	
				}
				//mkdir("testing");
				?>

			</ul>

		</div>
		




	</div>
	<div class="right ">
		<p class="text-primary text-center" style="font-size:20px;">Пребарување</p>

		<form action="login-home.php" method="post">
			<p class="text-muted" style="font-size:15px;margin:5px;"> Категорија:</p>

			<select name="kategorija" class=" selectpicker">
				<option  selected disabled>Изберете категорија</option>
				<?php
				$result=mysqli_query($conn,"SELECT ime_kategorija FROM kategorija");
				while($row = mysqli_fetch_array($result)){
				?>
				<option value="<?= $row['ime_kategorija'] ?>"><?= $row['ime_kategorija'] ?></option>
				<?php
				} //end while
				?>
			</select>
			<p class="text-muted" style="font-size:15px;margin:5px;"> Тип на недвижнина:</p>
			<select name="tip_objekti" name="kategorija" class="selectpicker">
				<option  selected disabled>Изберете објект</option>
				<?php
				$result=mysqli_query($conn,"SELECT * FROM tip_objekt");
				while($row = mysqli_fetch_array($result)){
				?>
				<option value="<?= $row['ime_objekt'] ?>"><?= $row['ime_objekt'] ?></option>
				<?php
				} //end while
				?>
			</select>
			<p class="text-muted" style="font-size:15px;margin:5px;"> Ентериер:</p>
			<select name="enterier" name="kategorija" class="selectpicker">
				<option  selected disabled>Изберете ентериер</option>
				<?php
				$result=mysqli_query($conn,"SELECT * FROM enterier");
				while($row = mysqli_fetch_array($result)){
				?>
				<option value="<?= $row['ime_enterier'] ?>"><?= $row['ime_enterier'] ?></option>
				<?php
				} //end while
				?>
			</select>
			<p class="text-muted" style="font-size:15px;margin:5px;"> Град:</p>
			<select name="grad" name="kategorija" class="selectpicker">
				<option  selected disabled>Изберете град</option>
				<?php
				$result=mysqli_query($conn,"SELECT  DISTINCT grad FROM oglasi");
				while($row = mysqli_fetch_array($result)){
				?>
				<option value="<?= $row['grad'] ?>"><?= $row['grad'] ?></option>
				<?php
				} //end while
				?>
			</select>
			<p class="text-muted" style="font-size:15px;margin:5px;">Цена:</p>

			<table>
				<tr>
					<td ><input type='text' size="4" placeholder="Од" name="cenaOd" class="form-control"></td>
					<td ><input type='text' size="4" placeholder="До" name="cenaDo" class="form-control"></td>
				</tr>
			</table>

			<p class="text-muted" style="font-size:15px;margin:5px;">Површина:</p>

			<table>
				<tr>
					<td ><input type='text' size="4" placeholder="Од" name="povrshinaOd" class="form-control"></td>
					<td ><input type='text' size="4" placeholder="До" name="povrshinaDo" class="form-control"></td>
				</tr>
			</table>


			<p class="text-muted" style="font-size:15px;margin:5px;">Број на соби:</p>
			<input type='text'plaseholder="Внесете број на соби" name="brSobi" class="form-control"><br>


			<input type="submit" value="Барај" name="filter" class="btn btn-default btn-success">

		</form>
	</div>
</div>

<footer class="panel-footer">
	<center>		
		<h4>COPYRIGHT 	&copy; SMESTI-SE.МК 2018</h4>
		<a href="pravila.php">ПРАВИЛА И УСЛОВИ</a>
	</center>
	
</footer>
</html>