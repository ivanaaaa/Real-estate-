
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Продавај - Изнајми - Смести се</title>
		<link rel="stylesheet" type="text/css" href="style.css">
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/css/bootstrap-select.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/js/bootstrap-select.min.js"></script>

	</head>
	<body style="background-color:#E0E0E0;">



		<div class="container-fluid">

			<div style="width:100%;height:80px;">


				<form action="index.php" method="post" class="navbar-form" style="float:right; ">
					<div class="form-group">
						<input type="text" name="klucenZbor" class="form-control" placeholder="клучен збор">
					</div>

					<input type="submit" name="baraj_po_klucenZbor" class="btn btn-primary navbar-btn" value="Пребарај">


					<a href="login.php"  class="btn btn-primary  navbar-btn">Најави се</a>


				</form>		
			</div>
			<div style="width:100%;height:180px; background-color:whitesmoke; border-radius: 5px; margin-bottom:30px;">

				<img src="assets/logo.png" style="float:left; margin:10px;">

				<div class="nav-left">
				
					<a href="index.php" class="btn btn-default  navbar-btn" style="display: inline-block;">Сите огласи</a>
					
					<a href="vnesiOglas.php" class="btn btn-default  navbar-btn" style="display: inline-block;">Внеси оглас</a>
					
					<a href="pomos.php" class="btn btn-default  navbar-btn" style="
																		   display: inline-block;
																		   ">Помош</a>

					<a href="za_nas.php" class="btn btn-default  navbar-btn" style="
																		   display: inline-block;
																		   ">За нас</a>	
					<a href="registracija.php" class="btn btn-default  navbar-btn" style="
																						  display: inline-block;
																						  ">Регистрирај се</a>


				</div>
			</div>



		</div><!-- /.container-fluid -->

