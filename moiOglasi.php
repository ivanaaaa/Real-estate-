<?php


include "najaveniHeader.php";
include "connection.php";
require_once("./include/korisnicka_strana.php");

if(!$fgmembersite->CheckLogin())
{
	$fgmembersite->RedirectToURL("login.php");
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
	<head>
	</head>
	<body>
		<div class="container" style="margin:30 auto;background-color:whitesmoke;border-radius:4px;padding-left: 40px;
									  padding-right: 40px;">
			<?php

	
			
			$user_id =  $fgmembersite->User_id();

			//echo $user_id;
			$oglasi = "";
			$zapisi_naStrana =12;


			$sql = mysqli_query($conn,"SELECT *
				FROM oglasi
				INNER JOIN sliki ON (oglasi.oglasID = sliki.oglasID)
				WHERE oglasi.korisnik_id = '$user_id' AND odobren = '1'
				GROUP BY sliki.oglasID
				") or die("Error");

			$oglasi = mysqli_num_rows($sql);
			echo '<p style="margin-left:10px; margin-right:20px;margin-top:20px;font-size:20px; color:white; background-color:#dd6464;padding:5px; border-radius:4px;" ><strong>Мои објавени огласи: ';
			echo $oglasi;

			$brojStrani = ceil($oglasi/$zapisi_naStrana);
			//tekovna strana
			if(!isset($_GET['strana'])){
				$strana = 1;
			}else{
				$strana = $_GET['strana'];
			}
			$stranaOD = ($strana-1)*$zapisi_naStrana;

			echo '</strong></p>';
			
			$sql = mysqli_query($conn,"SELECT *
				FROM oglasi
				INNER JOIN sliki ON (oglasi.oglasID = sliki.oglasID)
				WHERE oglasi.korisnik_id = '$user_id' AND odobren = '1'
				GROUP BY sliki.oglasID
				LIMIT ".$stranaOD.','.$zapisi_naStrana) or die("Error");
			
			while ($row = mysqli_fetch_array($sql)){
				echo "<a href='najaveniOglas.php?id=".$row['oglasID']. "' style='text-decoration : none; color : #fff;'  >";
				echo "<div class ='oglas'>";
				echo "<img id='oglas_Slika' src='uploads/".$row['imeSlika']."' />";
				echo '<div class="oglas-text">';
				echo $row['naslov'];
				//if($row['cena'] == 0)
				switch($row['tip_cena']){
					case 'Евра': echo '<br>Цена: <div style="height:30px;padding:5px;display: inline; border-radius:4px; background-color:green;">'.$row['cena'] . ' &euro; </div>'; break;
					case 'По договор': echo '<br>Цена: <div style="height:30px;padding:5px;display: inline; border-radius:4px; background-color:yellow; color:black;">По договор</div>'; break;
				}
				echo "</a>";
				echo "<br>";
			?>

			<input type='image' src='assets/thrash.png'  onClick='izbrishiMojOglas(<?= $row["oglasID"];?>)'/>
			<input type='image' src='assets/edit.png'  onClick='izmeniMojOglas(<?= $row["oglasID"];?>)'/>
			<?php
				//echo "</a>";

				echo "</div>";
				echo "</div>";
				
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
					
					<?php echo '<a href = "moiOglasi.php?strana='.$strana.'">'.$strana.'</a>'; ?>
					
					 </li>
				<?php	
				}
				//mkdir("testing");
				?>

			</ul>

		</div>

		</div>
		<script language='javascript'>
			function izbrishiMojOglas(oglasID){
				if(confirm("Дали сте сигурни дека сакате да го избришете вашиот оглас?")){
					window.location.href='izbrishiMojOglas.php?izbrishiMojOglas_id='+oglasID+'';
					return true;
				}
			}
			function izmeniMojOglas(oglasID){			
					window.location.href='izmeniMojOglas.php?id='+oglasID+'';
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
