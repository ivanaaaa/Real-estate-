<?php 
include"connection.php";
include"najaveniHeader.php";

?>

<div class = "container">
	<div class = "left">		


		<?php
		{
			
			
			if(isset($_GET['id'])){

				$naslov = "";
				$opis = '';
				$lokacija = '';
				$grad = '';
				$tip_objekt = '';
				$brSobi = '';
				$godIzgradba = $enterier = $tip_greenje = $tip_cena = $objavenNa =$sprat= "";
				$kvadratura = $cena =  0;



				$id = mysqli_real_escape_string($conn,$_GET["id"]);
				
				//session_start();
				$_SESSION['oglasID'] = $id;
				
				

				
				$sql = mysqli_query($conn,"SELECT oglasID,naslov,opis,broj_sobi,lokacija,grad,cena,tip_cena,kvadratura,objaven_na,godina_izgradba,enterier.ime_enterier,tip_greenje.ime_tip,tip_objekt.ime_objekt, korisnici.ime,korisnici.email,korisnici.telefon

				FROM oglasi,enterier,tip_objekt,tip_greenje,korisnici

				WHERE oglasi.enterier_id=enterier.enterier_id AND oglasi.tip_objekt_id = tip_objekt.tip_objekt_id AND oglasi.tip_greenje_id=tip_greenje.tip_greenje_id AND oglasi.korisnik_id = korisnici.id
				AND oglasID = '$id'
				") or die("Error query".mysqli_connect_error());

				if($row = mysqli_fetch_array($sql)){
					$naslov = $row['naslov'];

					$opis = $row['opis'];
					$lokacija = $row['lokacija'];
					$grad = $row['grad'];
					$tip_cena = $row['tip_cena'];
					$cena = $row['cena'];
					$kvadratura=$row['kvadratura'];
					$tip_objekt = $row['ime_objekt'];					
					//$sprat  = $row['sprat'];

					$objavenNa = $row['objaven_na'];
					$godIzgradba = $row['godina_izgradba'];
					$enterier = $row['ime_enterier'];
					$tip_greenje = $row['ime_tip'];
					$ime_objekt = $row['ime_objekt'];
					$oglasuvac = $row['ime'];
					$mail = $row['email'];
					$telefon = $row['telefon'];
					$brSobi = $row['broj_sobi'];

				}

				$sql = mysqli_query($conn,"SELECT imeSlika
											FROM sliki
											WHERE oglasID = '$id'");
				$sliki = array();
				while($row = mysqli_fetch_array($sql)){
					$sliki[] = $row;
				}

				$prvaGolemaSlika =  reset($sliki);



			}
		}
		
		
		?>
		
		<h2 style='margin-left:30px; margin-bottom:20px;'><?= $naslov ?></h2>
		<div id='Holder'>
			<div id='golemaSlika-Holder'>
				<?php 
	$path = "uploads/" . $prvaGolemaSlika['imeSlika'];
			echo "<img src = '".$path."' id = 'golemaSlika'>";
				?>

			</div>
			<div id='malaSlika-Holder'>
				<?php 
				foreach($sliki as &$slika){
					$path = "uploads/" . $slika['imeSlika'];
					echo "<img src = '".$path."' class = 'malaSlika'>";
				}
				?>
			</div>
		</div>
		<div style="height:70px; width:700px;margin-left: 30px; background-color:#E0E0E0;  margin-top:10px;margin-right:20px;
					padding:20px;">
			<p style="font-size:20px;display:inline-block;"><strong>Цена:</strong>
				<?php 
				switch($tip_cena){
					case 'Евра': echo $cena . ' &euro;'; break;
					case 'По договор': echo ' По договор'; break;
				}
				?>
			</p>
		</div>
		<h4 style='margin-left:30px;'><strong>Опис:</strong></h4>
		<p style='margin-left:30px;'><?= $opis ?></p> 
		<hr style="border-top: 1px solid #989898; 
				   width:700px;
				   margin-left: 30px;">
		<h4 style='margin-left:30px;'> <strong>Локација:</strong></h4>
		<p style='margin-left:30px;'><?= $lokacija . ",". $grad ?></p> 
		<hr style="border-top: 1px solid #989898; 
				   width:700px;
				   margin-left: 30px;">


		<table>
			<tr>
				<th>
					Лице за контакт:
				</th>
				<th>
					Телефонски број:
				</th>
				<th>
					Електронска пошта:
				</th>
			</tr>
			<tr>
				<td>
					<?=$oglasuvac; ?>
				</td>
				<td><?=

	$telefon;

					?>
				</td>
				<td style="color:blue;">
					<?=$mail; ?>
				</td>
				<td>
					<a href="mailto:<?=$mail?>" class="btn btn-primary" >Испрати порака</a> 
				</td>
			</tr>

		</table>
		<hr style="border-top: 1px solid #989898; 
				   width:700px;
				   margin-left: 30px;">

		<table >
			<tr class="row">
				<td class="col-xs-6">
					Тип на објект:
				</td>
				<td class="col-xs-6">
					<?=$tip_objekt?>
				</td>
			</tr>
			<tr class="row" >
				<td class="col-xs-6">
					Површина:
				</td>
				<td class="col-xs-6">
					<?=$kvadratura?> m<sup>2</sup>
				</td>
			</tr>
			<tr class="row">
				<td class="col-xs-6">
					Број на соби:
				</td>
				<td class="col-xs-6">
					<?=$brSobi?>
				</td>
			</tr>
			<tr class="row">
				<td class="col-xs-6">
					Година на изградба:
				</td>
				<td class="col-xs-6">
					<?=$godIzgradba?>
				</td>
			</tr>
			<tr class="row">
				<td class="col-xs-6">
					Ентериер:
				</td>
				<td class="col-xs-6">
					<?=$enterier?>
				</td>
			</tr>
			<tr class="row">
				<td class="col-xs-6">
					Греење:
				</td>
				<td class="col-xs-6">
					<?=$tip_greenje?>
				</td>
			</tr>
		</table>
		<hr style="border-top: 1px solid #989898; 
				   width:700px;
				   margin-left: 30px;">
		<h4 style='margin-left:30px;'><strong>Објавен на:</strong></h4>
		<div style="margin-left:30px; ">
			<p ><?= $objavenNa; ?></p> 

		
		<?php 
			
			if($fgmembersite->User_type() == 'корисник'){
				
			
			?>
			<form action="zacuvajOglas.php" method="post">
				
				<input type="submit" class="btn btn-primary" value="Зачувај го огласот">
			</form>
		
			<input type="submit" data-toggle="modal" data-target="#ispratiPoraka" style="margin-top:10px;" class="btn btn-danger" value="Пријави злоупотреба" >
		
		<?php } ?>
		
		
			
		</div>


		<hr style="border-top: 1px solid #989898; 
				   width:700px;
				   margin-left: 30px;">	  


	</div>
</div>
	<!-- Modal -->
  <div class="modal fade" id="ispratiPoraka" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Пријави злоупотреба</h4>
        </div>
        
        <div class="modal-body">
        
         <form action="prijaviZloupotreba.php" method="post">
         	<table style='margin:0 auto'>
          	
          	<tr><td style = 'padding:10px;'>Наслов: </td>
          	<td style = 'padding:10px;'><input required type='text' name="mail_naslov" class="form-control"></td>
          	</tr>
          	<tr><td style = 'padding:10px;'>Порака:</td><td style = 'padding:10px;'>
          						<div class="form-group">
							<textarea required class="form-control" name = 'mail_poraka' id="exampleFormControlTextarea1" rows="5"></textarea>
						</div>
          	</td></tr>
          </table>
          
          <div class="modal-footer">
          <input type="submit" class="btn btn-success"  name="isprati_poraka" value="Испрати">
          <button type="button" class="btn btn-default" data-dismiss="modal">Затвори</button>
        </div>
          <input type="hidden" name="oglas_id" value="<?=$id; ?>"> 
         </form>
          
        </div>
        
        
      </div>
        
	  </div>
</div>
<script type="text/javascript">
	window.addEventListener('load',imgFunc,false);
	function imgFunc(){
		var golemaSlika = document.getElementById('golemaSlika');
		var maliSliki = document.getElementsByClassName('malaSlika');

		for (var i=0;i<maliSliki.length;i++) {		
			maliSliki[i].addEventListener('click',function(){
				golemaSlika.src = event.target.src;	
			},false);
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






























