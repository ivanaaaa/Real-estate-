<?php
/**
 * Created by PhpStorm.
 */

include "najaveniHeader.php";
include "connection.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
</head>
<body>
<div class="container urediProfil" style="margin:30 auto;background-color:whitesmoke;border-radius:4px;padding-left: 40px;
												  padding-right: 40px;">

    <table border="1"   width="800px" cellspacing="7" >
        <tr  style="color: #dd6464;">
            <th>ИД</th>  <th>ИМЕ</th>   <th>Е-ПОШТА</th>  <th>КОРИСНИЧКО ИМЕ</th> <th> ТИП КОРИСНИК </th> <th>ИЗБРИШИ</th>
        </tr>

        <?php

        $korisnici = "";
        $zapisi_naStrana =200;

        $sql = "SELECT id, ime, email, username, tip_korisnik FROM korisnici WHERE (tip_korisnik='модератор' OR tip_korisnik='корисник')";

        $sendSQL =  mysqli_query($conn,$sql)or die("Error");

        $korisnici = mysqli_num_rows($sendSQL);

        $brojStrani = ceil($korisnici/$zapisi_naStrana);

        //tekovna strana
        if(!isset($_GET['strana'])){
            $strana = 1;
        }else{
            $strana = $_GET['strana'];
        }
        $stranaOD = ($strana-1)*$zapisi_naStrana;





        $result = mysqli_query($conn,"SELECT id, ime, email, username, tip_korisnik FROM korisnici WHERE (tip_korisnik='корисник' OR tip_korisnik='модератор' )LIMIT ".$stranaOD.','.$zapisi_naStrana) or die("Error");


        if ($result->num_rows > 0) {
            // output data of each row

            while($row = $result->fetch_assoc()) {
                echo "<tr ><td>".$row["id"] ."</td><td>".$row["ime"]."</td><td>".$row["email"]."</td><td>".$row["username"] ."</td><td>".$row["tip_korisnik"] ."</td>";

                ?>


                <td>
                    <input type='image' src='assets/thrash.png' style='margin-left:10px;' onClick='izbrishi_korisnik(<?= $row["id"];?>)'/>

                </td>

               


                <?php

            }
        }


        ?>
    </table>

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
					
					<?php echo '<a href = "lista_admin.php?strana='.$strana.'">'.$strana.'</a>'; ?>
					
					 </li>
				<?php	
				}
				//mkdir("testing");
				?>

			</ul>

		</div>

</div>
<script language='javascript'>
    function izbrishi_korisnik(id){
        if(confirm("Дали сте сигурни дека сакате да ги одземете правата на овој корисник?")){
            window.location.href='izbrishiKorisnik.php?id='+id+'';
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

