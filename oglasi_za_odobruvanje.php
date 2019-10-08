<?php
include "najaveniHeader.php";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
	<head>
	</head>
	<body>
		<div class="container" style="margin:30 auto;background-color:whitesmoke;border-radius:4px;    padding-left: 40px;
									  padding-right: 40px;">
			<?php
			$user_id =  $fgmembersite->User_id();

			//echo $user_id;
			$oglasi = "";
			$zapisi_naStrana =12;


			$sql = mysqli_query($conn,"SELECT *
				FROM oglasi
				INNER JOIN sliki ON (oglasi.oglasID = sliki.oglasID)
				WHERE oglasi.odobren = '0'
				GROUP BY sliki.oglasID
				") or die("Error");

			$oglasi = mysqli_num_rows($sql);
			echo '<p style="margin-left:10px; margin-right:20px;margin-top:20px;font-size:20px; color:white; background-color:#dd6464;padding:5px; border-radius:4px;" ><strong>Огласи со барање за објава: ';
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
				WHERE oglasi.odobren = '0'
				GROUP BY sliki.oglasID
				LIMIT ".$stranaOD.','.$zapisi_naStrana) or die("Error");
			
			while ($row = mysqli_fetch_array($sql)){
				echo "<a href='najaveniOglas.php?id=".$row['oglasID']. "' style='text-decoration : none; color : #fff;' >";
				echo "<div class ='oglas'>";
				echo "<img id='oglas_Slika' src='uploads/".$row['imeSlika']."' />";

				echo '<div class="oglas-text">';
				echo $row['naslov'];

				switch($row['tip_cena']){
					case 'Евра': echo '<br>Цена: <div style="height:30px;padding:5px;display: inline; border-radius:4px; background-color:green;">'.$row['cena'] . ' &euro; </div>'; break;
					case 'По договор': echo '<br>Цена: <div style="height:30px;padding:5px;display: inline; border-radius:4px; background-color:yellow; color:black;">По договор</div>'; break;
				}

				echo "<a href='odobriOglas.php?id=".$row['oglasID']. "'  >";
				echo "<img src='assets/check.png' style='margin-left:10px;' title='Одобри оглас' />";
				echo "</a>";

				echo "<br>";

				//echo "<a href='odobriOglas.php?erase_id=".$row['oglasID']. "'  >";
			?>

			<input type='image' src='assets/thrash.png' style='margin-left:10px;' onClick='izbrishi_oglas(<?= $row["oglasID"];?>)'/>
			<?php
				//echo "</a>";

				echo "</div>";
				echo "</div>";
				echo "</a>";
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
					
					<?php echo '<a href = "oglasi_za_odobruvanje.php?strana='.$strana.'">'.$strana.'</a>'; ?>
					
					 </li>
				<?php	
				}
				//mkdir("testing");
				?>

			</ul>

		</div>

		</div>
		<script language='javascript'>
			function izbrishi_oglas(oglasID){
				if(confirm("Дали сте сигурни дека сакате да го избришете огласов?")){
					window.location.href='odobriOglas.php?oglas_id='+oglasID+'';
					return true;
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
