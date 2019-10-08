<?php
include("connection.php");
include"najaveniHeader.php";

require_once("./include/korisnicka_strana.php");

if(!$fgmembersite->CheckLogin())
{
	$fgmembersite->RedirectToURL("login.php");
	exit;
}


if(isset($_POST['vnesiOglas'])){
	
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
	
	
	// Vnesi vo bazata oglasi
	$sql = "INSERT INTO `oglasi` ( `tip_objekt_id`, `kategorija_id`, `korisnik_id`, `naslov`, `opis`, `kvadratura`,`godina_izgradba`, `broj_sobi`, `enterier_id`, `tip_greenje_id`, `cena`, `tip_cena`, `lokacija`, `grad`, `objaven_na`)
	VALUES('$tip_objekt','$kategorija','$korisnik','$naslov','$opis','$kvadratura','$godina_izgradba','$brSobi','$enterier','$greenje','$cena','$tip_cena','$lokacija','$grad','$objaven_na')";
	$result = mysqli_query($conn,$sql);
	
	if($result){
		echo '<script type="text/javascript">alert("Вашиот оглас се чека да биде одобрен!");</script>';
	}
		
	
	// Vnesi vo bazata sliki
	$id_naVnesenOglas = mysqli_insert_id($conn);
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
			$sql = "INSERT INTO sliki(oglasID) VALUES('$id_naVnesenOglas')";
			mysqli_query($conn,$sql);
			$idSlika = mysqli_insert_id($conn); //zema go id-to na zapiso
			$vnesenaSlika = $idSlika.".".$imageFileType; // ime na slikata e id.extenzija
			$sql = "UPDATE sliki SET imeSlika = '$vnesenaSlika' WHERE id = '$idSlika'";
			mysqli_query($conn,$sql);
			// smesti go fajlot vo papkata uploads
			move_uploaded_file($_FILES['prikaciSlika']['tmp_name'][$i],$target_dir.$vnesenaSlika);
		}
	}
	//mysqli_close($conn);
}
?>

<div class="container">

	<div class = "left">
		<table>
			<form method="post" action="vnesiOglas.php" enctype='multipart/form-data'>
				<tr>
					<td style="font-size:15px;margin:5px;">Изберете Категорија</td>
					<td>
						<select required  name="kategorija"  class="form-control">
							<option value="" selected disabled></option>
							<option value="Издавање">Издавање</option>
							<option value="Продажба">Продажба</option>
						</select>
					</td>

				</tr>
				<tr>
					<td style="font-size:15px;margin:5px;">Недвижнина:</td>
					<td>
						<select required name="tip_objekt" class="form-control">
							<option value=""selected disabled ></option>
							<option value="Стан">Стан</option>
							<option value="Спрат од куќа">Спрат од куќа</option>
							<option value="Куќа">Куќа</option>						
						</select>
					</td>

				</tr>
				<tr>
					<td style="font-size:15px;margin:5px;">Град</td>
					<td>
						<select  required name="grad" class="form-control">
							<option value="selected disabled"></option>
                            <option value="Берово">Берово</option>
                            <option value="Битола">Битола</option>
                            <option value="Богданци">Богданци</option>
                            <option value="Валандово">Валандово</option>
                            <option value="Велес">Велес</option>
                            <option value="Виница">Виница</option>
                            <option value="Гевгелија">Гевгелија</option>
                            <option value="Гостивар">Гостивар</option>
                            <option value="Дебар">Дебар</option>
                            <option value="Делчево">Делчево</option>
                            <option value="Демир Капија">Демир Капија</option>
                            <option value="Демир Хисар">Демир Хисар</option>
                            <option value="Кавадарци">Кавадарци</option>
                            <option value="Кичево">Кичево</option>
                            <option value="Кочани">Кочани</option>
                            <option value="Кратово">Кратово</option>
                            <option value="Крива Паланка">Крива Паланка</option>
                            <option value="Крушево">Крушево</option>
                            <option value="Куманово">Куманово</option>
                            <option value="Македонски Брод">Македонски Брод</option>
                            <option value="Македонска Каменица">Македонска Каменица</option>
                            <option value="Неготино">Неготино</option>
                            <option value="Охрид">Охрид</option>
                            <option value="Пехчево">Пехчево</option>
                            <option value="Прилеп">Прилеп</option>
                            <option value="Пробиштип">Пробиштип</option>
                            <option value="Радовиш">Радовиш</option>
                            <option value="Ресен">Ресен</option>
                            <option value="Свети Николе">Свети Николе</option>
                            <option value="Скопје">Скопје</option>
                            <option value="Струга">Струга</option>
                            <option value="Струмица">Струмица</option>
                            <option value="Тетово">Тетово</option>
                            <option value="Штип">Штип</option>
						</select>
					</td>

				</tr>
				<tr>

					<td  style="font-size:15px;margin:5px;">Наслов на огласот:</td>
					<td><input required type='text' name="naslov" class="form-control"></td>
				</tr>
				<tr>
					<td style="font-size:15px;margin:5px;">Опис:</td>
					<td >

						<div class="form-group">
							<textarea required class="form-control" name = 'opis' id="exampleFormControlTextarea1" rows="5"></textarea>
						</div>


				</tr>

				<tr >
					<td style="font-size:15px;margin:5px;" >Површина:</td>	
					<td ><input required type='text' name="kvadratura" class="form-control"></td>	
					<td style="font-size:15px;margin:5px;">m<sup>2</sup></td>

				</tr>
                <tr>
                    <td style="font-size:15px;margin:5px;">Година на изградба: </td>
                    <td ><input required type='text' name="godina_izgradba" class="form-control"></td>
                </tr>
				<tr>
					<td style="font-size:15px;margin:5px;">
						Ентриер:
					</td>
					<td>
						<select required name="enterier" class="form-control">
							<option value="" selected disabled></option>
							<option value ="наместен" >наместен</option>
							<option value="делумно наместен">делумно наместен</option>
							<option value="ненаместен">ненаместен</option>

						</select>	
					</td>	
				</tr>
				<tr>
					<td style="font-size:15px;margin:5px;">Соби:</td>
					<td>
						<select required name="brSobi" class="form-control">
							<option value="" selected disabled>број на соби</option>
							<option value="1" >1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="над 9">над 9</option>

						</select>	
					</td>	
				</tr>
				<tr>
					<td style="font-size:15px;margin:5px;">Греење:</td>
					<td>
						<select required name = 'greenje' class="form-control">
							<option value="" selected disabled>Тип на греење</option>
							<option value="Нема">Нема</option>
							<option value="Централно">Централно</option>
							<option value="Струја">Струја</option>
							<option value="Дрва" >Дрва</option>
							<option value="Друго">Друго</option>								
						</select>	
					</td>	
				</tr>
			
				<tr>
					<td style="font-size:15px;margin:5px;">Адреса:</td>	
					<td ><input required type='text'name='lokacija' class="form-control"></td>	

				</tr>		
				
				<tr>
					<td  style="font-size:15px;margin:5px;">Цена:</td>	
					<td ><input required type='text' name="cena" class="form-control" id = "cenaVnes"></td>
					<td>
						<select required name="tip_cena" class="form-control" id="tipCena" onchange="cenaDisable()">
							<option selected disabled></option>
							<option value="Евра">Евра</option>						
							<option value="По договор" >По договор</option>						
						</select>	
					</td>		

				</tr>
				<tr>
					<td style="font-size:15px;margin:5px;">Прикачи слики:</td>	
					<td><input required type="file" class="btn btn-default" name="prikaciSlika[]" multiple value="Прикачи"></td>	

				</tr>
				<tr >
					<td>
					
						<input type="submit" class="btn btn-default btn-success" name="vnesiOglas" value="Внеси го огласот">
					
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
		 
</script>
</body>
<footer class="panel-footer">
	<center>		
		<h4>COPYRIGHT 	&copy; SMESTI-SE.МК 2018</h4>
		<a href="pravila.php">ПРАВИЛА И УСЛОВИ</a>
	</center>
	
</footer>
</html>


