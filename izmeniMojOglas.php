<?php
include("connection.php");
include "najaveniHeader.php";

require_once("./include/korisnicka_strana.php");

if(!$fgmembersite->CheckLogin())
{
	$fgmembersite->RedirectToURL("login.php");
	exit;
}



if(isset($_POST['vnesiOglas'])){	

	$oglasID = $_SESSION['oglas_id'];

	
	$kategorija = mysqli_real_escape_string($conn,$_POST['kategorija']);
	$tip_objekt = mysqli_real_escape_string($conn,$_POST['tip_objekt']);
	switch($kategorija){
		case 'Издавање': $kategorija=1;break;
		case 'Продажба': $kategorija=2;break;
	}
	switch($tip_objekt){
		case 'Стан': $tip_objekt=1;break;
		case 'Спрат од куќа': $tip_objekt=2;break;
		case 'Куќа': $tip_objekt=3;break;
	}
	$grad = mysqli_real_escape_string($conn,$_POST['grad']);
	$naslov = mysqli_real_escape_string($conn,$_POST['naslov']);
	$opis = mysqli_real_escape_string($conn,$_POST['opis']);
	$godina_izgradba = mysqli_real_escape_string($conn,$_POST['godina_izgradba']);
	$kvadratura = mysqli_real_escape_string($conn,$_POST['kvadratura']);
	$enterier = mysqli_real_escape_string($conn,$_POST['enterier']);
	switch($enterier){
		case 'наместен': $enterier=1;break;
		case 'делумно наместен': $enterier=2;break;
		case 'ненаместен': $enterier=3;break;
	}
	$brSobi = mysqli_real_escape_string($conn,$_POST['brSobi']);
	$greenje = mysqli_real_escape_string($conn,$_POST['greenje']);
	switch($greenje){
		case 'Нема': $greenje=1;break;
		case 'Централно': $greenje=2;break;
		case 'Струја': $greenje=3;break;
		case 'Дрва': $greenje=4;break;
		case 'Друго': $greenje=5;break;
	}


	if(isset($_POST['cena']))
		$cena = mysqli_real_escape_string($conn,$_POST['cena']);
	else
		$cena=0;

	$tip_cena = mysqli_real_escape_string($conn,$_POST['tip_cena']);
	$lokacija = mysqli_real_escape_string($conn,$_POST['lokacija']);

	$objaven_na = date("Y.m.d");
	$korisnik =  $fgmembersite->User_id(); 


	//echo $tip_objekt.$kategorija.$korisnik.$naslov.$opis.$kvadratura.$godina_izgradba.$brSobi.$enterier.$greenje.$tip_cena.$lokacija.$grad.$objaven_na;

	//echo $cena;



	// Vnesi vo bazata oglasi
	$sql = "UPDATE oglasi

			SET tip_objekt_id = '$tip_objekt',kategorija_id='$kategorija',korisnik_id='$korisnik',naslov='$naslov',opis='$opis',kvadratura='$kvadratura',godina_izgradba='$godina_izgradba',broj_sobi='$brSobi',
			enterier_id='$enterier',tip_greenje_id='$greenje',tip_cena='$tip_cena',cena='$cena',lokacija='$lokacija',grad='$grad',objaven_na = '$objaven_na'

			WHERE oglasi.oglasID = '$oglasID'
";
	$result = mysqli_query($conn,$sql);



	if($_POST['sliki'] == "Да")
	{
		// zemi gi site sliki od bazata -> vo niza i izbrishi gi od uploads
		$sql = "
			SELECT * FROM sliki
			WHERE sliki.oglasID = '$oglasID'
			";
		$result = mysqli_query($conn,$sql);


		$sliki_zaBrishenje = array();

		while($row = mysqli_fetch_array($result)){
			$sliki_zaBrishenje[] = $row['imeSlika'];
		}


		foreach($sliki_zaBrishenje as $imeSlika){
			unlink('uploads/'.$imeSlika);
		}



		// treba da gi izbrishe momentalnite vo bazata
		$sql = "
			DELETE from sliki
			WHERE sliki.oglasID = '$oglasID'
			";
		$result = mysqli_query($conn,$sql);


		// Vnesi vo bazata sliki
		$id_naUpdateOglas = $oglasID;

		// za povekje sliki 
		$target_dir = "uploads/";
		for($i=0;$i<count($_FILES["prikaciSlika"]["name"]);$i++){
			$target_file = $target_dir . basename($_FILES["prikaciSlika"]["name"][$i]);
			// zemi ja ekstenzijata
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			// validni ekstenzii
			$extensions_arr = array("jpg","jpeg","png","gif");
			// Check extension
			if( in_array($imageFileType,$extensions_arr) ){
				// vo bazata na sliki
				$sql = "INSERT INTO sliki(oglasID) VALUES('$id_naUpdateOglas')";
				mysqli_query($conn,$sql);
				$idSlika = mysqli_insert_id($conn); //zema go id-to na zapiso
				$vnesenaSlika = $idSlika.".".$imageFileType; // ime na slikata e id.extenzija
				$sql = "UPDATE sliki SET imeSlika = '$vnesenaSlika' WHERE id = '$idSlika'";
				mysqli_query($conn,$sql);
				// smesti go fajlot vo papkata uploads
				move_uploaded_file($_FILES['prikaciSlika']['tmp_name'][$i],$target_dir.$vnesenaSlika);
			}
		}


	}
	if($result){
		header("Location: moiOglasi.php");
	}

}

else{
	// za da gi izvlece momentalnite podatoci za oglasot
	$_SESSION['oglas_id'] =  $_GET['id'];

	$oglasID = $_SESSION['oglas_id'];
	$sql = "select * from oglasi where oglasID = '$oglasID' ";
	$result = mysqli_query($conn,$sql);
	$row = mysqli_fetch_array($result);
}

?>

<div class="container">

	<div class = "left">
		<table>
			<form method="post" action="<?=$_SERVER['PHP_SELF']?>" enctype='multipart/form-data'>
				<tr>
					<td style="font-size:15px;margin:5px;">Изберете Категорија</td>
					<td>
						<select required  name="kategorija"  class="form-control selectpicker show-tick">
							<option value="Издавање" <?php if($row['kategorija_id']=="1") echo 'selected="selected"'; ?>>Издавање</option>
							<option value="Продажба" <?php if($row['kategorija_id']=="2") echo 'selected="selected"';?> >Продажба</option>
						</select>
					</td>

				</tr>
				<tr>
					<td style="font-size:15px;margin:5px;">Недвижнина:</td>
					<td>
						<select required name="tip_objekt" class="form-control selectpicker show-tick">
							<option value="Стан"<?php if($row['tip_objekt_id']=="1") echo 'selected="selected"'; ?>>Стан</option>
							<option value="Спрат од куќа"<?php if($row['tip_objekt_id']=="2") echo 'selected="selected"'; ?>>Спрат од куќа</option>
							<option value="Куќа" <?php if($row['tip_objekt_id']=="3") echo 'selected="selected"'; ?>>Куќа</option>						
						</select>
					</td>

				</tr>
				<tr>
					<td style="font-size:15px;margin:5px;">Град</td>
					<td>
						<select required  name="grad" class="form-control  show-tick">
							<option value="Берово"<?php if($row['grad']=="Берово") echo 'selected="selected"'; ?>>Берово</option>grad
							<option value="Битола"<?php if($row['grad']=="Битола") echo 'selected="selected"'; ?>>Битола</option>
							<option value="Богданци"<?php if($row['grad']=="Богданци") echo 'selected="selected"'; ?>>Богданци</option>
							<option value="Валандово"<?php if($row['grad']=="Валандово") echo 'selected="selected"'; ?>>Валандово</option>
							<option value="Велес"<?php if($row['grad']=="Велес") echo 'selected="selected"'; ?>>Велес</option>
							<option value="Виница"<?php if($row['grad']=="Виница") echo 'selected="selected"'; ?>>Виница</option>
							<option value="Гевгелија"<?php if($row['grad']=="Гевгелија") echo 'selected="selected"'; ?>>Гевгелија</option>
							<option value="Гостивар"<?php if($row['grad']=="Гостивар") echo 'selected="selected"'; ?>>Гостивар</option>
							<option value="Дебар"<?php if($row['grad']=="Дебар") echo 'selected="selected"'; ?>>Дебар</option>
							<option value="Делчево"<?php if($row['grad']=="Делчево") echo 'selected="selected"'; ?>>Делчево</option>
							<option value="Демир Капија"<?php if($row['grad']=="Демир Капија") echo 'selected="selected"'; ?>>Демир Капија</option>
							<option value="Демир Хисар"<?php if($row['grad']=="Демир Хисар") echo 'selected="selected"'; ?>>Демир Хисар</option>
							<option value="Кавадарци"<?php if($row['grad']=="Кавадарци") echo 'selected="selected"'; ?>>Кавадарци</option>
							<option value="Кичево"<?php if($row['grad']=="Кичево") echo 'selected="selected"'; ?>>Кичево</option>
							<option value="Кочани"<?php if($row['grad']=="Кочани") echo 'selected="selected"'; ?>>Кочани</option>
							<option value="Кратово"<?php if($row['grad']=="Кратово") echo 'selected="selected"'; ?>>Кратово</option>
							<option value="Крива Паланка"<?php if($row['grad']=="Крива Паланка") echo 'selected="selected"'; ?>>Крива Паланка</option>
							<option value="Крушево"<?php if($row['grad']=="Крушево") echo 'selected="selected"'; ?>>Крушево</option>
							<option value="Куманово"<?php if($row['grad']=="Куманово") echo 'selected="selected"'; ?>>Куманово</option>
							<option value="Македонски Брод"<?php if($row['grad']=="Македонски Брод") echo 'selected="selected"'; ?>>Македонски Брод</option>
							<option value="Македонска Каменица"<?php if($row['grad']=="Македонска Каменица") echo 'selected="selected"'; ?>>Македонска Каменица</option>
							<option value="Неготино"<?php if($row['grad']=="Неготино") echo 'selected="selected"'; ?>>Неготино</option>
							<option value="Охрид"<?php if($row['grad']=="Охрид") echo 'selected="selected"'; ?>>Охрид</option>
							<option value="Пехчево"<?php if($row['grad']=="Пехчево") echo 'selected="selected"'; ?>>Пехчево</option>
							<option value="Прилеп"<?php if($row['grad']=="Прилеп") echo 'selected="selected"'; ?>>Прилеп</option>
							<option value="Пробиштип"<?php if($row['grad']=="Пробиштип") echo 'selected="selected"'; ?>>Пробиштип</option>
							<option value="Радовиш"<?php if($row['grad']=="Радовиш") echo 'selected="selected"'; ?>>Радовиш</option>
							<option value="Ресен"<?php if($row['grad']=="Ресен") echo 'selected="selected"'; ?>>Ресен</option>
							<option value="Свети Николе"<?php if($row['grad']=="Свети Николе") echo 'selected="selected"'; ?>>Свети Николе</option>
							<option value="Скопје"<?php if($row['grad']=="Скопје") echo 'selected="selected"'; ?>>Скопје</option>
							<option value="Струга"<?php if($row['grad']=="Струга") echo 'selected="selected"'; ?>>Струга</option>
							<option value="Струмица"<?php if($row['grad']=="Струмица") echo 'selected="selected"'; ?>>Струмица</option>
							<option value="Тетово"<?php if($row['grad']=="Тетово") echo 'selected="selected"'; ?>>Тетово</option>
							<option value="Штип"<?php if($row['grad']=="Штип") echo 'selected="selected"'; ?>>Штип</option>
						</select>
					</td>

				</tr>
				<tr>

					<td  style="font-size:15px;margin:5px;">Наслов на огласот:</td>
					<td><input required type='text' name="naslov" class="form-control" value="<?=$row['naslov']?>"></td>
				</tr>
				<tr>
					<td style="font-size:15px;margin:5px;">Опис:</td>
					<td >

						<div class="form-group">
							<textarea required class="form-control" name = 'opis' id="exampleFormControlTextarea1" rows="5"><?=$row['opis']?> </textarea>
						</div>


				</tr>

				<tr >
					<td style="font-size:15px;margin:5px;" >Површина:</td>	
					<td ><input required type='text' name="kvadratura" class="form-control"value="<?=$row['kvadratura']?>"></td>	
					<td style="font-size:15px;margin:5px;">m<sup>2</sup></td>

				</tr>
				<tr>
					<td style="font-size:15px;margin:5px;">Година на изградба: </td>
					<td ><input required type='text' name="godina_izgradba" class="form-control"value="<?=$row['godina_izgradba']?>"></td>
				</tr>
				<tr>
					<td style="font-size:15px;margin:5px;">
						Ентриер:
					</td>
					<td>
						<select required  name="enterier" class="form-control selectpicker show-tick">
							<option value ="наместен" <?php if($row['enterier_id']=="1") echo 'selected="selected"'; ?> >наместен</option>
							<option value="делумно наместен"<?php if($row['enterier_id']=="2") echo 'selected="selected"'; ?>>делумно наместен</option>
							<option value="ненаместен"<?php if($row['enterier_id']=="3") echo 'selected="selected"'; ?>>ненаместен</option>

						</select>	
					</td>	
				</tr>
				<tr>
					<td style="font-size:15px;margin:5px;">Соби:</td>
					<td>
						<select required name="brSobi" class="form-control selectpicker show-tick">
							<option value="1" <?php if($row['broj_sobi']=="1") echo 'selected="selected"'; ?> >1</option>
							<option value="2"<?php if($row['broj_sobi']=="2") echo 'selected="selected"'; ?> >2</option>
							<option value="3"<?php if($row['broj_sobi']=="3") echo 'selected="selected"'; ?> >3</option>
							<option value="4"<?php if($row['broj_sobi']=="4") echo 'selected="selected"'; ?> >4</option>
							<option value="5"<?php if($row['broj_sobi']=="5") echo 'selected="selected"'; ?> >5</option>
							<option value="6"<?php if($row['broj_sobi']=="6") echo 'selected="selected"'; ?> >6</option>
							<option value="7"<?php if($row['broj_sobi']=="7") echo 'selected="selected"'; ?> >7</option>
							<option value="8"<?php if($row['broj_sobi']=="8") echo 'selected="selected"'; ?> >8</option>
							<option value="над 9"<?php if($row['broj_sobi']=="над 9") echo 'selected="selected"'; ?> >над 9</option>

						</select>	
					</td>	
				</tr>
				<tr>
					<td style="font-size:15px;margin:5px;">Греење:</td>
					<td>
						<select required  name = 'greenje' class="form-control selectpicker show-tick">
							<option value="Нема"<?php if($row['tip_greenje_id']=="1") echo 'selected="selected"'; ?>>Нема</option>
							<option value="Централно"<?php if($row['tip_greenje_id']=="2") echo 'selected="selected"'; ?>>Централно</option>
							<option value="Струја"<?php if($row['tip_greenje_id']=="3") echo 'selected="selected"'; ?>>Струја</option>
							<option value="Дрва"<?php if($row['tip_greenje_id']=="4") echo 'selected="selected"'; ?> >Дрва</option>
							<option value="Друго"<?php if($row['tip_greenje_id']=="5") echo 'selected="selected"'; ?>>Друго</option>								
						</select>	
					</td>	
				</tr>

				<tr>
					<td style="font-size:15px;margin:5px;">Адреса:</td>	
					<td ><input required type='text'name='lokacija' class="form-control" value="<?=$row['lokacija']?>"></td>	

				</tr>		

				<tr>
					<td  style="font-size:15px;margin:5px;">Цена:</td>	
					<td ><input required type='text' name="cena" class="form-control" id = "cenaVnes"
								<?php 

								if($row['tip_cena'] == 'По договор')
									echo 'disabled';

								?>
								placeholder="<?php if($row['tip_cena'] == 'Евра') echo $row['cena']; ?>"
								 >
					</td>
					<td>
						<select required name="tip_cena" class="form-control selectpicker show-tick" id="tipCena" onchange="cenaDisable()">
							<option value="Евра"<?php if($row['tip_cena']=="Евра") echo 'selected="selected"'; ?>>Евра</option>						
							<option value="По договор"<?php if($row['tip_cena']=="По договор") echo 'selected="selected"'; ?> >По договор</option>						
						</select>	
					</td>		

				</tr>
				<tr>
					<td>
						Дали сакате да внесете нови слики?
					</td>
					<td>
						<select  name="sliki" class="form-control selectpicker show-tick" id="novi_sliki" onchange="noviSliki_vnes()">
							<option value="Не">Не</option>
							<option value="Да">Да</option>
						</select>	
					</td>
				</tr>

				<tr id="noviSliki" style='visibility:hidden;'>
					<td style="font-size:15px;margin:5px;">Прикачи слики:</td>	
					<td><input id = "prikaciSliki" type="file" class="btn btn-default" name="prikaciSlika[]" multiple value="Прикачи"></td>	

				</tr>
				<tr >
					<td>

						<input type="submit" class="btn btn-default btn-success" name="vnesiOglas" value="Зачувај ги промените">

					</td>
				</tr>
			</form>
		</table>

	</div>
</div>
<script type="text/javascript">
	function cenaDisable(){
		if(document.getElementById("tipCena").value == "По договор"){
			document.getElementById("cenaVnes").disabled = true;			 
		}else{
			document.getElementById("cenaVnes").disabled = false;	
		}
	}
	function noviSliki_vnes(){
		if(document.getElementById("novi_sliki").value == "Да"){
			var elem = document.getElementById("noviSliki");
			elem.style.visibility = "visible";	
			document.getElementById("prikaciSliki").required = true;

		}else{
			var elem = document.getElementById("noviSliki");
			elem.style.visibility = "hidden";
			document.getElementById("prikaciSliki").required = false;
		}
	}

</script>
</body>
<footer class="panel-footer">
	<center>		
		<h4>COPYRIGHT 	&copy; SMESTI-SE.МК 2018</h4>
		<a href="pravila.php">ПРАВИЛА И УСЛОВИ</a>
	</center>

</footer>
</html>


